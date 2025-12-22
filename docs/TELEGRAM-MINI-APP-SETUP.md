# Telegram Mini App Development Guide

This guide explains how to develop and deploy Telegram Mini Apps (WebApps) for your CRM system.

## What are Telegram Mini Apps?

Telegram Mini Apps are **web applications that run inside Telegram**. They provide:
- ✅ Full web UI capabilities (HTML, CSS, JavaScript, React, Vue, etc.)
- ✅ Access to Telegram user data
- ✅ Native Telegram theme integration
- ✅ Payment processing
- ✅ Cloud storage
- ✅ Better UX than traditional bots

**Traditional Bot:**
```
User: /catalog
Bot: 📁 Electronics
     📁 Clothing
     📁 Sports
```

**Mini App:**
```
User: [Opens Mini App]
→ Full interactive web interface with images, buttons, search, cart, etc.
```

---

## Architecture Overview

```
┌─────────────────────────────────────────┐
│         Telegram Client                 │
│  ┌───────────────────────────────────┐  │
│  │     Mini App (WebView)            │  │
│  │  - React/Vue/Vanilla JS           │  │
│  │  - Telegram WebApp API            │  │
│  │  - Your catalog UI                │  │
│  └───────────────────────────────────┘  │
│              ↕ HTTPS                     │
└─────────────────────────────────────────┘
                 ↕
┌─────────────────────────────────────────┐
│     Laravel Backend (Your Server)       │
│  - API endpoints for catalog            │
│  - User authentication                  │
│  - Business logic                       │
└─────────────────────────────────────────┘
```

---

## Local Development Setup

### Prerequisites

1. **HTTPS is REQUIRED** - Telegram Mini Apps only work over HTTPS
2. **Public URL needed** - Your local app must be accessible from the internet
3. **Telegram Bot** - You need a bot token from @BotFather

### Option 1: Using ngrok (Recommended for Local Dev)

#### Step 1: Install ngrok

```bash
# macOS
brew install ngrok

# Linux
snap install ngrok

# Or download from https://ngrok.com/download
```

#### Step 2: Start Your Laravel App

```bash
# Start Laravel Sail
vendor/bin/sail up -d

# Or standard Laravel
php artisan serve
```

#### Step 3: Create ngrok Tunnel

```bash
# Expose your local server (port 80 for Sail, or 8000 for artisan serve)
ngrok http 80

# You'll get output like:
# Forwarding  https://abc123.ngrok-free.app -> http://localhost:80
```

**Copy the HTTPS URL** (e.g., `https://abc123.ngrok-free.app`)

#### Step 4: Configure Your Bot

Open Telegram, find @BotFather, and run:

```
/setmenubutton
→ Select your bot
→ Send: Mini App URL
→ Paste: https://abc123.ngrok-free.app/telegram/app
→ Send: 🛍️ Open Catalog (button text)
```

#### Step 5: Test Your Mini App

1. Open your bot in Telegram
2. Click the **Menu Button** (≡) or type `/start`
3. Tap "🛍️ Open Catalog"
4. Your Mini App should open!

### Option 2: Using Laravel Valet (macOS Only)

```bash
# Install Valet
composer global require laravel/valet
valet install

# Secure your site with HTTPS
cd /path/to/your/project
valet secure

# Your app is now available at:
# https://your-project.test
```

Then configure @BotFather with `https://your-project.test/telegram/app`

---

## Production Setup

### Step 1: Deploy Your Laravel App

Deploy to any hosting service with HTTPS:
- DigitalOcean
- AWS
- Laravel Forge
- Heroku
- Vercel (for frontend)

**Your app MUST have:**
- ✅ Valid SSL certificate (Let's Encrypt is free)
- ✅ Public HTTPS URL (e.g., `https://yourapp.com`)

### Step 2: Configure Production Bot

```
/setmenubutton
→ Select your bot
→ Send: Mini App URL
→ Paste: https://yourapp.com/telegram/app
→ Send: 🛍️ Open Catalog
```

### Step 3: Set Environment Variables

Update your `.env` on production:

```env
APP_URL=https://yourapp.com
TELEGRAM_BOT_TOKEN=your_production_bot_token
```

---

## Frontend Implementation

### Option A: Vanilla JavaScript (Simple)

Create `resources/views/telegram/app.blade.php`:

```html
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalog</title>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--tg-theme-bg-color);
            color: var(--tg-theme-text-color);
        }
        .item {
            padding: 15px;
            border-radius: 12px;
            background: var(--tg-theme-secondary-bg-color);
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div id="app">
        <h1>Loading catalog...</h1>
    </div>

    <script>
        // Initialize Telegram WebApp
        const tg = window.Telegram.WebApp;
        tg.ready();
        tg.expand(); // Expand to full height

        // Get user data
        const user = tg.initDataUnsafe.user;
        console.log('User:', user);

        // Fetch catalog from your API
        fetch('/api/telegram/catalog', {
            headers: {
                'X-Telegram-Init-Data': tg.initData
            }
        })
        .then(res => res.json())
        .then(data => {
            displayCatalog(data);
        });

        function displayCatalog(items) {
            const app = document.getElementById('app');
            app.innerHTML = '<h1>🛍️ Catalog</h1>';

            items.forEach(item => {
                const div = document.createElement('div');
                div.className = 'item';
                div.innerHTML = `
                    <h3>${item.title}</h3>
                    <p>$${item.price}</p>
                `;
                div.onclick = () => showItem(item);
                app.appendChild(div);
            });
        }

        function showItem(item) {
            tg.showAlert(`You selected: ${item.title}`);
        }

        // Handle back button
        tg.BackButton.onClick(() => {
            tg.close();
        });
        tg.BackButton.show();
    </script>
</body>
</html>
```

### Option B: React/Vue (Advanced)

Use Vite to build your frontend:

```bash
# Install dependencies
npm install @twa-dev/sdk

# Create Mini App in resources/js/telegram-app/
```

---

## Backend API Routes

Create API endpoints in `routes/api.php`:

```php
use Illuminate\Http\Request;

Route::prefix('telegram')->group(function () {
    // Validate Telegram WebApp data
    Route::middleware('telegram.webapp')->group(function () {
        Route::get('/catalog', function (Request $request) {
            // $request->telegramUser contains validated user data
            return response()->json([
                'items' => Item::all()
            ]);
        });

        Route::get('/item/{id}', function (Request $request, $id) {
            return response()->json([
                'item' => Item::find($id)
            ]);
        });
    });
});
```

---

## Security: Validate Telegram Data

Create middleware to validate that requests come from Telegram:

```bash
php artisan make:middleware ValidateTelegramWebApp
```

```php
public function handle(Request $request, Closure $next)
{
    $initData = $request->header('X-Telegram-Init-Data');

    if (!$this->validateTelegramData($initData)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Parse and attach user data to request
    parse_str($initData, $data);
    $request->merge(['telegramUser' => json_decode($data['user'] ?? '{}')]);

    return $next($request);
}

private function validateTelegramData(string $initData): bool
{
    // Implement Telegram's data validation algorithm
    // See: https://core.telegram.org/bots/webapps#validating-data-received-via-the-mini-app
    $botToken = config('services.telegram.bot_token');

    parse_str($initData, $data);
    $hash = $data['hash'] ?? '';
    unset($data['hash']);

    ksort($data);
    $dataCheckString = implode("\n", array_map(
        fn($k, $v) => "$k=$v",
        array_keys($data),
        array_values($data)
    ));

    $secretKey = hash_hmac('sha256', $botToken, 'WebAppData', true);
    $calculatedHash = bin2hex(hash_hmac('sha256', $dataCheckString, $secretKey, true));

    return hash_equals($calculatedHash, $hash);
}
```

---

## Nutgram Integration

Update `app/Telegram/BotHandlers.php`:

```php
use SergiX44\Nutgram\Nutgram;

class BotHandlers
{
    public function registerHandlers(Nutgram $bot): void
    {
        // Start command launches Mini App
        $bot->onCommand('start', function (Nutgram $bot) {
            $bot->sendMessage(
                'Welcome! Tap the menu button below to open our catalog 🛍️',
                reply_markup: InlineKeyboardMarkup::make()->addRow(
                    InlineKeyboardButton::make(
                        '🛍️ Open Catalog',
                        url: config('app.url') . '/telegram/app'
                    )
                )
            );
        });
    }
}
```

---

## Testing Checklist

### Local Development
- [ ] ngrok tunnel is running
- [ ] Laravel Sail is running
- [ ] Bot menu button configured with ngrok URL
- [ ] Mini App opens in Telegram
- [ ] API calls work
- [ ] Telegram theme colors are applied

### Production
- [ ] HTTPS certificate is valid
- [ ] DNS is configured correctly
- [ ] Bot menu button points to production URL
- [ ] Environment variables are set
- [ ] API authentication works
- [ ] Mini App loads on mobile and desktop Telegram

---

## Common Issues

### Mini App doesn't open
- ✅ Check URL is HTTPS
- ✅ Check URL is publicly accessible
- ✅ Try `/mybots` → `Bot Settings` → `Menu Button` in @BotFather

### "Invalid data" errors
- ✅ Implement proper data validation
- ✅ Check `initData` is being sent correctly

### Styling looks wrong
- ✅ Use Telegram CSS variables (`var(--tg-theme-bg-color)`)
- ✅ Call `tg.ready()` before rendering

### ngrok session expired
- ✅ Free ngrok sessions expire after 2 hours
- ✅ Get a paid plan for persistent URLs ($8/month)
- ✅ Or use ngrok-skip-browser-warning header

---

## Next Steps

1. **Create the Mini App view** - `resources/views/telegram/app.blade.php`
2. **Create API routes** - `routes/api.php`
3. **Add validation middleware** - Secure your endpoints
4. **Test locally with ngrok**
5. **Deploy to production**
6. **Configure production bot**

## Resources

- [Telegram Mini Apps Docs](https://core.telegram.org/bots/webapps)
- [Telegram WebApp API](https://core.telegram.org/bots/webapps#initializing-mini-apps)
- [Nutgram Documentation](https://nutgram.dev)
- [@twa-dev/sdk](https://github.com/twa-dev/sdk) - TypeScript SDK
- [Mini Apps Examples](https://github.com/telegram-mini-apps)
