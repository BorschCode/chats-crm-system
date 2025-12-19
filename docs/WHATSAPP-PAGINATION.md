# WhatsApp Message Pagination

## Overview

WhatsApp has a **4096 character limit** for text messages. When listing many items or groups, messages are automatically split into multiple pages.

## How It Works

### Automatic Pagination

The system automatically:
1. Monitors message length as items are added
2. Splits messages when approaching 3800 characters (safe limit)
3. Adds page numbers to each message
4. Sends messages with small delays to avoid rate limiting

### Example Output

**Small list (fits in one message):**
```
*Available Items in group 'electronics':*

🛍️ *Laptop Pro*
   Price: $1299.99
   View: item laptop-pro

🛍️ *Wireless Mouse*
   Price: $29.99
   View: item wireless-mouse
```

**Large list (split into pages):**
```
*Available Items:*

🛍️ *Item 1*
   Price: $99.99
   View: item item-1

🛍️ *Item 2*
   Price: $149.99
   View: item item-2

...

_Page 1 of 3_
```

Then a second message:
```
🛍️ *Item 50*
   Price: $199.99
   View: item item-50

...

_Page 2 of 3_
```

## Commands with Pagination

### `items` - List all items
```
items
```
Lists all items with automatic pagination.

### `items {group-slug}` - Items by group
```
items electronics
```
Lists items in a specific group with pagination.

### `groups` - List all groups
```
groups
```
Lists all product groups with pagination.

## Technical Details

### Character Limits

| Limit | Value | Purpose |
|-------|-------|---------|
| WhatsApp Max | 4096 | Absolute maximum |
| Safe Limit | 3800 | Used for splitting to allow page info |
| Reserved | 296 | For page numbers and formatting |

### Rate Limiting

Between paginated messages:
- **Delay:** 0.5 seconds (500ms)
- **Purpose:** Avoid Meta API rate limits
- **User Experience:** Messages arrive in quick succession

### Message Structure

Each message chunk contains:
1. Header (first page only or repeated)
2. Item/Group entries
3. Page indicator (if multiple pages): `_Page X of Y_`

## Code Implementation

### WhatsAppService::sendItems()

```php
// Build messages with pagination
$maxLength = 3800;
$messages = [];
$currentMessage = "*Available Items:*\n\n";

foreach ($items as $item) {
    $itemText = "🛍️ *{$item->title}*\n...";

    if (strlen($currentMessage . $itemText) > $maxLength) {
        $messages[] = $currentMessage;
        $currentMessage = $itemText;
    } else {
        $currentMessage .= $itemText;
    }
}

// Send with page numbers
foreach ($messages as $index => $message) {
    if ($totalPages > 1) {
        $message .= "\n_Page {$pageNumber} of {$totalPages}_";
    }
    $this->sendMessage($to, $message);
    usleep(500000); // 0.5s delay
}
```

## Error Handling

If a single item/group is too long (unlikely):
- Error code: 100
- Error message: "Param text['body'] must be at most 4096 characters long"
- Solution: Truncate individual item descriptions

### Error Log Example

```json
{
  "level": "ERROR",
  "message": "WhatsApp: Message too long (exceeds 4096 character limit)",
  "error_code": 100,
  "message_length": 5234,
  "hint": "Message should be split into multiple messages with pagination"
}
```

## Best Practices

### For Users
- Use specific group filters to reduce list size: `items electronics` instead of `items`
- View individual items for full details: `item laptop-pro`

### For Developers
- Keep item titles and descriptions concise
- Use pagination for any list that could grow
- Test with large datasets (100+ items)
- Monitor message lengths in logs

## Testing Pagination

### Create Test Data

```bash
# In Laravel Tinker
vendor/bin/sail artisan tinker

# Create 100 test items
factory(App\Models\Item::class, 100)->create();
```

### Test Commands

```
items                    # Should paginate
items electronics        # Should paginate if many items
groups                   # Should paginate if many groups
```

### Expected Behavior

- ✅ Multiple WhatsApp messages received
- ✅ Each message under 4096 characters
- ✅ Page numbers shown: "_Page 1 of 3_"
- ✅ Messages arrive within ~1 second of each other
- ✅ No errors in logs

## Troubleshooting

### Messages Still Too Long?

Check individual item descriptions:
```sql
SELECT title, LENGTH(description) as desc_length
FROM items
WHERE LENGTH(description) > 500
ORDER BY desc_length DESC;
```

### Pagination Not Working?

1. Check logs for errors
2. Verify `strlen()` is calculating correctly
3. Test with known large dataset
4. Ensure `usleep()` is not being skipped

### Rate Limit Errors?

Increase delay between messages:
```php
usleep(1000000); // 1 second instead of 0.5
```

## Future Improvements

Potential enhancements:
- [ ] Add "Next Page" interactive buttons (requires WhatsApp Business API templates)
- [ ] Limit items per page to fixed number (e.g., 10 items/page)
- [ ] Add summary counts: "Showing 1-10 of 53 items"
- [ ] Allow page navigation: `items page:2`
- [ ] Cache results for pagination navigation
