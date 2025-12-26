# Catalog + Multi-Channel Messaging System

[![WhatsApp API](https://img.shields.io/badge/WhatsApp-Cloud_API-brightgreen?logo=whatsapp)]()
[![Telegram Bot](https://img.shields.io/badge/Telegram-Bot-blue?logo=telegram)]()
[![Docker](https://img.shields.io/badge/Docker-ready-2496ED?logo=docker&logoColor=white)](https://www.docker.com/)
[![Livewire](https://img.shields.io/badge/Livewire-3.x-purple?logo=livewire&logoColor=white)](https://livewire.laravel.com)
[![Instagram Messaging](https://img.shields.io/badge/Instagram-Messaging-ff66b2?logo=instagram&logoColor=white)]()
[![Laravel](https://img.shields.io/badge/Laravel-12.x-red?logo=laravel&logoColor=white)](https://laravel.com)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-8.3-blue?logo=php&logoColor=white)](https://php.net)

Powered by **Laravel 12**, **Livewire 3**, **Sail**, **MySQL**

---

## 🚀 Overview

A modern, multi-channel product catalog system built with **Laravel 12** that delivers seamless shopping experiences across web and messaging platforms.

### ✨ Key Highlights

- 🌐 **Responsive Web Catalog** - Livewire 3 powered SPA
- 📱 **Telegram Mini App** - Native web app inside Telegram with full authentication
- 💬 **WhatsApp Interactive Lists** - Native UI components with pagination
- 🎨 **No Admin Panel** - Content managed via code (Factories + Seeders)
- 🐳 **Fully Dockerized** - Laravel Sail ready
- 🔒 **Production-Ready Security** - HMAC validation, signature verification
- 🚀 **Lightweight** - No Redis, queues, or Horizon overhead

### Perfect for:
- Small to medium catalogs
- Micro-commerce shops
- Telegram / WhatsApp sales bots
- MVPs & prototypes
- Personal digital storefronts
- Educational projects demonstrating modern Laravel patterns

---

## 🎯 Live Demo

**Try it now!** Interact with the live Telegram bot:

[![Telegram Bot](https://img.shields.io/badge/Try_Live_Demo-@cvbnmjkl__bot-blue?logo=telegram&style=for-the-badge)](https://t.me/cvbnmjkl_bot)

👉 **[Open in Telegram](https://t.me/cvbnmjkl_bot)**

---

## 🎥 Video Demo

Watch the WhatsApp catalog system in action:

<div align="center">

<details open>
  <summary>📱 Multi-channel catalog system demo (click to play)</summary>

  <video src="https://github.com/user-attachments/assets/8d776d3e-6aeb-4db1-9099-10a58ac96142" controls="controls" muted="muted" style="max-width: 100%;">
  </video>
</details>

*Also available on [YouTube Shorts](https://youtube.com/shorts/DiINnmHytQE?feature=share)*

### Video Highlights
<table>
  <tr>
    <td align="center" width="33%">
      <img src="./docs/whatsap/index.png" alt="WhatsApp Menu" width="200"/>
      <br/>
      <b>Interactive Menu</b>
    </td>
    <td align="center" width="33%">
      <img src="./docs/whatsap/item.png" alt="Product Details" width="200"/>
      <br/>
      <b>Product Details</b>
    </td>
    <td align="center" width="33%">
      <img src="./docs/telegram/items.png" alt="Telegram View" width="200"/>
      <br/>
      <b>Multi-Platform</b>
    </td>
  </tr>
</table>

</div>

---

## 📸 Screenshots

<table>
  <tr>
    <th>Platform</th>
    <th>Screenshot</th>
    <th>Description</th>
  </tr>
  <tr>
    <td><strong>Web Interface</strong></td>
    <td><img src="./docs/web/index-list.png" alt="Web Catalog" width="300"/></td>
    <td>Modern Livewire-powered catalog with responsive design and real-time filtering</td>
  </tr>
  <tr>
    <td><strong>Telegram Mini App</strong></td>
    <td><img src="./docs/telegram/telegram-index.png" alt="Telegram Index" width="300"/></td>
    <td>Native Telegram Mini App with tab navigation and product browsing</td>
  </tr>
  <tr>
    <td><strong>Telegram Items View</strong></td>
    <td><img src="./docs/telegram/items.png" alt="Telegram Items" width="300"/></td>
    <td>Interactive product cards with pricing and instant viewing</td>
  </tr>
  <tr>
    <td><strong>WhatsApp Catalog</strong></td>
    <td><img src="./docs/whatsap/index.png" alt="WhatsApp Index" width="300"/></td>
    <td>WhatsApp Cloud API integration with interactive lists and buttons</td>
  </tr>
  <tr>
    <td><strong>WhatsApp Item Details</strong></td>
    <td><img src="./docs/whatsap/item.png" alt="WhatsApp Item" width="300"/></td>
    <td>Rich product details with images, pricing, and navigation options</td>
  </tr>
</table>

---

## 🧱 Tech Stack

| Feature | Technology |
|--------|------------|
| Framework | Laravel 12 |
| Realtime UI | Livewire 3 |
| Styling | TailwindCSS |
| Docker | Laravel Sail |
| Database | MySQL 8 |
| Messaging Channels | Telegram, WhatsApp Cloud, Instagram Messaging |
| Boilerplate Base | chats-crm-system |

No Redis. No queues. No Horizon. No admin dashboard.

---

## 📦 Features

### 🌐 Web Catalog (Livewire 3)
- ✅ Group listing with descriptions
- ✅ Item listing with real-time filtering
- ✅ Items filtered by group
- ✅ Item detail view with images
- ✅ Responsive TailwindCSS design
- ✅ Simple, elegant UI with no admin overhead

### 📱 Telegram Mini App
- ✅ **Native Telegram Web App** - Full SPA running inside Telegram
- ✅ Tab-based navigation (Groups/Items)
- ✅ Card grid layout with product images
- ✅ Modal item details
- ✅ Automatic theme adaptation (light/dark mode)
- ✅ HMAC-SHA256 authentication
- ✅ Personalized greeting with user's name

### 💬 WhatsApp Cloud API Integration
- ✅ **Interactive List Messages** - Native WhatsApp UI components
- ✅ Pagination (9 items + "Next Page" button)
- ✅ Interactive buttons for navigation
- ✅ Rich media support (images in item details)
- ✅ Typing indicators & read receipts
- ✅ Webhook signature verification
- ✅ Command enum for type-safe routing

### 🔧 Architecture
- ✅ Unified `CatalogService` for all channels
- ✅ Service layer pattern for messaging
- ✅ Dedicated middleware for security
- ✅ Factory + Seeder data generation
- ✅ No admin panel - content managed via code

Supported bot commands:

#### Telegram
```
/start         - Opens the Telegram Mini App catalog
/catalog       - Alternative way to open the Mini App
```

#### WhatsApp (Interactive Lists & Buttons)
```
catalog        - View main menu with interactive buttons
groups         - Browse product categories with pagination
items          - View all items or items by category
item <slug>    - View detailed item information with image
```

*Note: WhatsApp uses interactive message components instead of plain text commands for better UX*

---

## 📂 Project Structure
```

app/
Models/Group.php
Models/Item.php
Services/
CatalogService.php
TelegramService.php
WhatsAppService.php
InstagramService.php
Http/
Controllers/
Web/
GroupController.php
ItemController.php
Api/
TelegramWebhookController.php
WhatsAppWebhookController.php
InstagramWebhookController.php
resources/
views/livewire/
group-list.blade.php
item-list.blade.php
item-show.blade.php
routes/
web.php
api.php
database/
migrations/
seeders/
factories/

````

---

## 🐳 Installation (Laravel Sail + MySQL)

```bash
git clone https://github.com/your-user/catalog-messaging
cd catalog-messaging

cp .env.example .env

composer install
npm install
npm run build

./vendor/bin/sail up -d

./vendor/bin/sail artisan migrate --seed
````

---

## 📡 Local Development with ngrok

When testing webhooks locally with ngrok:

```bash
ngrok http 80
```

Then use the ngrok HTTPS URL for webhook registration:

```
https://your-ngrok-url.ngrok.io/api/webhook/telegram
https://your-ngrok-url.ngrok.io/api/webhook/whatsapp
https://your-ngrok-url.ngrok.io/api/webhook/instagram
```

Don't forget to update `APP_URL` in `.env` to match your ngrok URL!

---

## 📱 Available Routes

### Web Interface (Livewire)
* `/` - Homepage with item list
* `/groups` - All product groups
* `/items` - All items
* `/items/{group}` - Items filtered by group
* `/item/{slug}` - Individual item details

### Telegram Mini App
* `/telegram/app` - Telegram Web App catalog interface
* Accessible via Telegram bot's inline button

### API Endpoints (for Mini App)
* `/api/telegram/groups` - Get all groups (authenticated)
* `/api/telegram/items` - Get all items (authenticated)
* `/api/telegram/items/{groupSlug}` - Get items by group (authenticated)
* `/api/telegram/item/{slug}` - Get item details (authenticated)

---

## 💬 Webhook Configuration

| Channel | Webhook URL (POST) | Method |
| :--- | :--- | :--- |
| **Telegram** | `/api/webhook/telegram` | POST |
| **WhatsApp** | `/api/webhook/whatsapp` | GET (verification), POST (messages) |
| **Instagram** | `/api/webhook/instagram` | POST |

### Setting up Webhooks

**Telegram:**
```bash
php artisan nutgram:hook:set https://your-domain.com/api/webhook/telegram
```

**WhatsApp:**
- Configure in Meta Business Suite / WhatsApp Cloud API Dashboard
- Webhook verification token is set in `.env` as `WHATSAPP_VERIFY_TOKEN`
- Includes signature verification middleware for security

**Instagram:**
- Configure in Facebook App Dashboard
- Currently placeholder implementation

---

## 📘 License

MIT or choose your own.


