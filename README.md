# Catalog + Multi-Channel Messaging System  
Based on: https://github.com/BorschCode/chats-crm-system  
Powered by Laravel 12, Livewire 3, Sail, MySQL

## 🚀 Overview
This project is a lightweight, fully Dockerized Laravel 12 application that provides:

- A simple **read-only product catalog**
- Access through **web interface (Livewire 3)**
- Access through **Telegram**, **WhatsApp Cloud API**, and **Instagram Messaging API**
- A clean, modular messaging architecture extended from the `chats-crm-system` boilerplate
- No admin panel, no CRUD — all data comes from factories + seeders

It is ideal for:
- Small catalogs  
- Personal stores  
- Bots that send product listings  
- WhatsApp/Telegram/Instagram micro-commerce  
- MVP prototypes  

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

### Catalog
- Group listing  
- Item listing  
- Items by group  
- Item detail view  
- Simple, elegant Livewire UI  

### Messaging Integrations
- Telegram bot support (webhook)  
- WhatsApp Cloud API support  
- Instagram Messaging API support  
- Unified `CatalogService` for all integrations  

Supported bot commands:

#### Telegram
```

/start
/catalog
/groups
/items
/items {group_slug}
/item {item_slug}

```

#### WhatsApp / Instagram
```

catalog
groups
items
item <slug>

```

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

## 📡 Webhooks Setup (ngrok)

Webhook URLs when using Sail + ngrok:

```
https://your-ngrok-url/api/telegram/webhook
https://your-ngrok-url/api/whatsapp/webhook
https://your-ngrok-url/api/instagram/webhook
```

Register these in:

* Telegram Botfather
* WhatsApp Cloud API setup
* Facebook App (Instagram)

---

## 📱 Livewire Frontend

Available pages:

* `/groups`
* `/items`
* `/items/{group}`
* `/item/{slug}`

---

## 📘 License

MIT or choose your own.
