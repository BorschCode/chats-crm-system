# How to Find WhatsApp App Secret

## ⚠️ Important: App Secret vs Client Token

**DO NOT use the "Client Token"** - that's the wrong secret!

You need the **App Secret**, which is different from:
- ❌ Client Token (wrong)
- ❌ Access Token (wrong)
- ❌ Webhook Verify Token (wrong)
- ✅ **App Secret** (correct!)

---

## Step-by-Step Guide to Find App Secret

### 1. Go to Meta Developers Console
Open: https://developers.facebook.com/apps

### 2. Select Your WhatsApp Business App
Click on the app you're using for WhatsApp Business API

### 3. Navigate to Settings → Basic
- In the left sidebar, click **Settings**
- Click **Basic** under Settings

### 4. Locate App Secret
Scroll down to the **App Secret** field:

```
App secret
[Hidden value] [Show] [Reset]
```

### 5. Click "Show" Button
- Click the **Show** button next to the App Secret
- You may need to re-enter your Facebook password
- Copy the entire App Secret value (it's a long alphanumeric string)

### 6. Add to Your .env File
```env
WHATSAPP_APP_SECRET=your_actual_app_secret_here
```

**Example format:**
```env
WHATSAPP_APP_SECRET=a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6
```

---

## Screenshot Reference

```
┌─────────────────────────────────────────────────┐
│ Settings > Basic                                │
├─────────────────────────────────────────────────┤
│                                                 │
│ App ID                                          │
│ 123456789012345                                 │
│                                                 │
│ App secret                  ← THIS ONE!         │
│ ••••••••••••••••  [Show]  [Reset]              │
│                                                 │
│ Display name                                    │
│ My WhatsApp Business App                        │
│                                                 │
│ ⚠️ DO NOT USE THESE:                            │
│                                                 │
│ Additional Settings ▼                           │
│   Client token            ← WRONG!              │
│   •••••••••••                                   │
│                                                 │
└─────────────────────────────────────────────────┘
```

---

## Common Mistakes

### ❌ Mistake 1: Using Client Token
**Location**: Settings > Basic > Additional Settings > Client Token
**Why it's wrong**: Client Token is for client-side apps, not webhooks

### ❌ Mistake 2: Using Access Token
**Location**: WhatsApp > API Setup > Temporary access token
**Why it's wrong**: Access Token is for API calls, not signature verification

### ❌ Mistake 3: Using Webhook Verify Token
**Location**: Your own .env file `WHATSAPP_WEBHOOK_VERIFY_TOKEN`
**Why it's wrong**: This is for webhook setup, not signature verification

---

## Verify Your App Secret

After setting `WHATSAPP_APP_SECRET` in `.env`, check the logs:

### ✅ Success (Correct App Secret)
```
WhatsApp webhook signature verified successfully
```

### ❌ Failure (Wrong App Secret)
```
WhatsApp webhook signature verification failed
received_signature: sha256=abc123...
expected_signature: sha256=xyz789...
help: Verify WHATSAPP_APP_SECRET in .env matches App Secret in Meta Developers Console > Settings > Basic
```

---

## Troubleshooting Signature Verification

### Issue: Signatures Don't Match

**Check these:**

1. **Correct secret?**
   - Compare your `.env` value with Meta dashboard
   - No extra spaces or newlines
   - Copy the entire secret

2. **Correct app?**
   - Make sure you're using the secret from the same app
   - If you have multiple WhatsApp apps, verify you're using the right one

3. **Secret not updated?**
   - Restart your application after changing `.env`
   - Docker: `docker compose restart app`
   - Sail: `vendor/bin/sail restart`

4. **Clear config cache:**
   ```bash
   docker exec chats-crm-app php artisan config:clear
   ```

### Issue: Where to Find App Secret in Different Meta UI Versions

Meta occasionally updates their UI. If you can't find "Settings > Basic":

**Alternative 1: Direct Link**
```
https://developers.facebook.com/apps/{YOUR_APP_ID}/settings/basic/
```

**Alternative 2: Dashboard Search**
- Use the search bar in Meta Developer Console
- Search for "App Secret"

**Alternative 3: App Dashboard**
- Go to your app dashboard
- Look for "App Secret" in the main settings area

---

## Security Best Practices

### ✅ DO
- Keep App Secret in environment variables only
- Never commit App Secret to version control
- Use different App Secrets for dev/staging/production
- Rotate App Secret if compromised

### ❌ DON'T
- Don't share App Secret publicly
- Don't hardcode App Secret in your code
- Don't use the same App Secret across multiple apps
- Don't confuse App Secret with other tokens

---

## Quick Reference

| What | Where | Used For |
|------|-------|----------|
| **App Secret** | Settings > Basic | ✅ Webhook signature verification |
| Client Token | Settings > Additional | ❌ Not for webhooks |
| Access Token | WhatsApp > API Setup | ❌ For API calls only |
| Verify Token | Your .env | ❌ For webhook setup only |

---

## Need Help?

If signature verification still fails after using the correct App Secret:

1. Check the error logs for `app_secret_prefix` - verify it matches your secret
2. Ensure no spaces or special characters when copying the secret
3. Try resetting the App Secret in Meta dashboard and using the new one
4. Contact Meta support if the issue persists

---

**Last Updated**: 2025-12-19
