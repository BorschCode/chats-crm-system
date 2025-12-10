* Laravel **12**
* Livewire **3**
* Docker Sail
* MySQL only
* No Redis
* No Admin CRUD, only seeders
* Catalog + messaging integrations
* Built on top of *chats-crm-system* boilerplate

Below is your final combined instruction package.

---

# ✅ **LLM INSTRUCTION PACK (WITH BOILERPLATE REPO)**

Use this instruction for Manus.im, ChatGPT, Claude, or any LLM.

---

# 📦 PROJECT CONTEXT & BOILERPLATE SOURCE

This project is based on the following boilerplate:

**Repository:** [https://github.com/BorschCode/chats-crm-system](https://github.com/BorschCode/chats-crm-system)
**Description:**
A Laravel-based starter CRM for managing chat-driven communication. It provides a clean structure for message routing, webhook endpoints, simple service classes, and modular integration with multiple messengers. It does *not* include catalog functionality, ecommerce logic, or Livewire 3 UI. It serves only as the foundational code organization for messaging channels.

Your task is to **extend and transform** this boilerplate into a lightweight “catalog + messaging” system.

---

# 🧱 **TECH STACK REQUIREMENTS**

Generate everything using:

* Laravel **12**
* Livewire **3**
* PHP 8.3+
* Laravel Sail (Docker)
* MySQL **ONLY**
* Nginx (Sail default)
* Vite + TailwindCSS
* No Redis
* No Queue workers
* No Horizon
* No Admin Panel
* Only seeders populate data

---

# 🛒 **FEATURE: CATALOG**

A simple, read-only product catalog.

## **Models**

### Group

* id
* title
* slug
* description

### Item

* id
* title
* slug
* description
* price (decimal)
* image (nullable)
* group_id (nullable)

## **Relationships**

* Group → hasMany → Items
* Item → belongsTo → Group

## **Functionality**

* View all groups
* View all items
* View items by group
* Individual item details

## **NO admin CRUD**

Data comes only from:

* Migrations
* Factories
* Seeders

---

# 💬 **FEATURE: COMMUNICATION CHANNELS**

Extend the boilerplate’s messaging architecture to support:

### ✓ Telegram Bot API

Commands:
`/start`, `/catalog`, `/groups`, `/items`, `/items {group}`, `/item {slug}`

### ✓ WhatsApp Cloud API

Commands:
`catalog`, `groups`, `items`, `item <slug>`

### ✓ Instagram Messaging API

Same commands as WhatsApp.

## **Shared CatalogService**

Must provide:

* listGroups()
* listItems(groupSlug = null)
* getItem(slug)

## **Messaging Services**

Generate classes:

* TelegramService
* WhatsAppService
* InstagramService

Each must include:

* sendMessage(to, text)
* sendCatalog(to)
* sendGroups(to)
* sendItems(to, groupSlug?)
* sendItemDetails(to, Item)

## **Webhook Controllers**

* TelegramWebhookController
* WhatsAppWebhookController
* InstagramWebhookController

Use POST API routes similar to the boilerplate repo.

---

# 🌐 **FRONTEND (LIVEWIRE 3)**

Generate:

### Components

* `GroupList`
* `ItemList`
* `ItemShow`

### Features

* TailwindCSS UI
* Responsive
* No authentication needed
* Simple read-only browsing

Use:

* `wire:navigate`
* Livewire 3 syntax (no outdated “mount” patterns)

---

# 🐳 **DOCKER SETUP (SAIL + MYSQL ONLY)**

### Must include:

* docker-compose.yml
* sail alias setup
* `.env` using MySQL settings only
* No Redis env vars
* No queue or Horizon

Example minimal `.env` entries:

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=app
DB_USERNAME=sail
DB_PASSWORD=password
```

---

# 📜 **WHAT THE LLM MUST GENERATE**

1. **docker-compose.yml**
2. **.env.example** (cleaned from Redis; MySQL only)
3. **Migrations** (groups, items)
4. **Models** (Group, Item)
5. **Factories**
6. **Seeders with sample data**
7. **CatalogService**
8. **Messaging services**
9. **Webhook controllers**
10. **API Routes**
11. **Web Routes**
12. **Livewire 3 components + views**
13. **Integration with boilerplate structure from chats-crm-system**
14. **README.md** (see below)

All code must be **ready-to-run** inside Sail.

---

# 📘 **README.md (Generated for Your Project)**

Below is the complete README you can use on GitHub.

---
