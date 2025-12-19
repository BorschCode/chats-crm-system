# WhatsApp Interactive List Messages

## Overview

Interactive List Messages provide a native, button-driven UI for WhatsApp conversations. Instead of typing commands, users tap a button and select from a menu.

## Features

### Current Implementation

✅ **Catalog Command** - Shows interactive list of product groups
✅ **Automatic Callback Handling** - Selecting a group automatically shows items
✅ **Error Handling** - Graceful fallbacks for errors
✅ **Pagination Support** - Handles >10 groups with text fallback

## How It Works

### User Flow

1. **User sends:** `catalog`
2. **Bot responds:** Interactive list message with button
3. **User taps:** "View Groups" button
4. **WhatsApp shows:** Native menu with product groups
5. **User selects:** A group (e.g., "Electronics")
6. **Bot responds:** Items in that group (with pagination if needed)

### Visual Example

```
┌─────────────────────────────────┐
│ Welcome to our catalog! 🛍️      │
│                                 │
│ Browse our product groups and   │
│ discover amazing items.         │
│                                 │
│ ┌─────────────────────────────┐ │
│ │    [View Groups]            │ │
│ └─────────────────────────────┘ │
└─────────────────────────────────┘

[User taps "View Groups"]

┌─────────────────────────────────┐
│ Product Groups                  │
├─────────────────────────────────┤
│ Electronics                     │
│ View items in Electronics       │
├─────────────────────────────────┤
│ Clothing                        │
│ View items in Clothing          │
├─────────────────────────────────┤
│ Home & Garden                   │
│ View items in Home & Garden     │
└─────────────────────────────────┘

[User selects "Electronics"]

Bot sends paginated list of electronics items...
```

## WhatsApp API Limits

| Component | Limit | Notes |
|-----------|-------|-------|
| Sections per message | 10 | We use 1 section |
| Rows per section | 10 | Max 10 groups in list |
| Title length | 24 chars | Auto-truncated |
| Description length | 72 chars | Auto-truncated |
| Body text | 1024 chars | Main message |
| Footer text | 60 chars | Optional |
| Button text | 20 chars | "View Groups" |

## Code Structure

### Sending Interactive Lists

```php
// app/Services/WhatsAppService.php

$this->sendInteractiveList($to, [
    'header' => 'Optional Header',           // Optional
    'body' => 'Main message text',           // Required
    'footer' => 'Optional footer',           // Optional
    'button' => 'Button Text',               // Required (max 20 chars)
    'sections' => [                          // Required (max 10 sections)
        [
            'title' => 'Section Title',      // Required
            'rows' => [                      // Required (max 10 rows)
                [
                    'id' => 'unique_id',     // Required (callback data)
                    'title' => 'Row Title',  // Required (max 24 chars)
                    'description' => 'Desc', // Optional (max 72 chars)
                ],
            ],
        ],
    ],
]);
```

### Handling Callbacks

```php
// app/Http/Controllers/WhatsAppWebhookController.php

protected function processInteractiveMessage(array $message, string $from): void
{
    $type = $message['interactive']['type'];

    if ($type === 'list_reply') {
        $selectedId = $message['interactive']['list_reply']['id'];

        // selectedId = "group_electronics"
        // Parse and handle the selection
    }
}
```

## Message Payload Structure

### Outgoing (To WhatsApp API)

```json
{
  "messaging_product": "whatsapp",
  "recipient_type": "individual",
  "to": "380999729396",
  "type": "interactive",
  "interactive": {
    "type": "list",
    "header": {
      "type": "text",
      "text": "Optional Header"
    },
    "body": {
      "text": "Welcome to our catalog! 🛍️\n\nBrowse our product groups."
    },
    "footer": {
      "text": "Optional footer text"
    },
    "action": {
      "button": "View Groups",
      "sections": [
        {
          "title": "Product Groups",
          "rows": [
            {
              "id": "group_electronics",
              "title": "Electronics",
              "description": "View items in Electronics"
            },
            {
              "id": "group_clothing",
              "title": "Clothing",
              "description": "View items in Clothing"
            }
          ]
        }
      ]
    }
  }
}
```

### Incoming (From WhatsApp Webhook)

When user selects an item:

```json
{
  "object": "whatsapp_business_account",
  "entry": [{
    "changes": [{
      "value": {
        "messages": [{
          "from": "380999729396",
          "id": "wamid.xxx",
          "timestamp": "1234567890",
          "type": "interactive",
          "interactive": {
            "type": "list_reply",
            "list_reply": {
              "id": "group_electronics",
              "title": "Electronics",
              "description": "View items in Electronics"
            }
          }
        }]
      }
    }]
  }]
}
```

## Testing

### Test with Postman - Catalog Request

**URL:** `https://your-webhook-url/api/webhook/whatsapp`
**Method:** `POST`
**Body:**

```json
{
  "object": "whatsapp_business_account",
  "entry": [{
    "changes": [{
      "value": {
        "messages": [{
          "from": "380999729396",
          "id": "test123",
          "timestamp": "1734598800",
          "type": "text",
          "text": {
            "body": "catalog"
          }
        }]
      }
    }]
  }]
}
```

**Expected:** WhatsApp message with interactive list

### Test Interactive Callback

**Body:**

```json
{
  "object": "whatsapp_business_account",
  "entry": [{
    "changes": [{
      "value": {
        "messages": [{
          "from": "380999729396",
          "id": "test124",
          "timestamp": "1734598801",
          "type": "interactive",
          "interactive": {
            "type": "list_reply",
            "list_reply": {
              "id": "group_electronics",
              "title": "Electronics",
              "description": "View items in Electronics"
            }
          }
        }]
      }
    }]
  }]
}
```

**Expected:** Items from electronics group with pagination

## Error Handling

### Common Errors

#### 1. Invalid Interactive Format

```json
{
  "error": {
    "message": "(#132000) Number of sections must be 1-10",
    "code": 132000
  }
}
```

**Solution:** Ensure 1-10 sections per message.

#### 2. Row Limit Exceeded

```json
{
  "error": {
    "message": "(#132000) Number of items must be 1-10",
    "code": 132000
  }
}
```

**Solution:** Max 10 rows per section. Use pagination or multiple sections.

#### 3. Text Too Long

```json
{
  "error": {
    "message": "(#132015) Parameter title must be 1-24 characters",
    "code": 132015
  }
}
```

**Solution:** Truncate with `substr($title, 0, 24)`.

### Fallback Strategy

If interactive list fails:
1. Log the error with full context
2. Fall back to text-based catalog
3. Notify user about the fallback

```php
try {
    $this->sendInteractiveList($to, $listData);
} catch (\Exception $e) {
    Log::error('Interactive list failed, falling back to text', [
        'error' => $e->getMessage(),
    ]);

    // Send text-based catalog instead
    $this->sendTextCatalog($to);
}
```

## Future Enhancements

Potential improvements:

- [ ] **Interactive Item Lists** - Select items directly from list
- [ ] **Multi-section Lists** - Group by categories
- [ ] **Button Messages** - Quick reply buttons for common actions
- [ ] **Product Messages** - Native product catalog integration
- [ ] **Order Flow** - Interactive order placement
- [ ] **Search Integration** - Interactive search results

## Best Practices

1. **Keep titles short** - Users see them in small UI
2. **Use descriptions wisely** - Provide context, not redundancy
3. **Meaningful IDs** - Use format like `{type}_{identifier}`
4. **Limit options** - Don't overwhelm users (5-7 items is ideal)
5. **Test on mobile** - Always test the actual WhatsApp experience
6. **Monitor analytics** - Track which options users select most

## Resources

- [WhatsApp Interactive Messages Docs](https://developers.facebook.com/docs/whatsapp/cloud-api/guides/send-messages#interactive-messages)
- [Interactive List Messages Spec](https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#interactive-object)
- [Error Codes Reference](https://developers.facebook.com/docs/whatsapp/cloud-api/support/error-codes)
