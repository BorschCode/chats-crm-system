# WhatsApp Business API Test Mode

## Understanding Test Mode

When your WhatsApp Business API app is in **test/development mode**, you can only send messages to phone numbers that are on your **allowed list**.

### Error Code 131030

```
(#131030) Recipient phone number not in allowed list
```

This error means you're trying to send a message to a number that hasn't been whitelisted in your Meta Developer Console.

## Solutions

### Option 1: Add Numbers to Allowed List (Immediate)

1. Go to [Meta Developer Console](https://developers.facebook.com/apps/)
2. Select your WhatsApp app
3. Navigate to **WhatsApp** → **API Setup**
4. Scroll to **"To"** section (Send and receive messages)
5. Click **"Manage phone number list"**
6. Click **"Add phone number"**
7. Enter the phone number (with country code, e.g., `16315551181`)
8. Verify with the OTP code sent to that number
9. Save

**Limitations:**
- Maximum 5 phone numbers in test mode
- Each number must verify via OTP
- Only for testing, not production use

### Option 2: Move to Production (Permanent)

To send messages to **any** phone number:

1. **Complete Business Verification:**
   - Go to [Meta Business Manager](https://business.facebook.com/)
   - Verify your business with official documents
   - Usually takes 1-3 business days

2. **Submit App for Review:**
   - Go to your app in [Meta Developer Console](https://developers.facebook.com/apps/)
   - Navigate to **App Review** → **Permissions and Features**
   - Request **whatsapp_business_messaging** permission
   - Provide details about your use case
   - Submit for review

3. **Wait for Approval:**
   - Review typically takes 1-7 days
   - Meta will email you when approved

4. **Generate Production Access Token:**
   - After approval, generate a new long-lived access token
   - Update your `WHATSAPP_ACCESS_TOKEN` environment variable

## Testing Your Webhook

### With Test Numbers (Current Setup)

1. Add your phone number to the allowed list (Option 1)
2. Send a real WhatsApp message from your phone to your business number
3. Your webhook will receive it and can reply

### With Meta Test Webhook Tool

Meta provides test webhooks for debugging:

```bash
curl -X POST 'https://your-webhook-url/api/webhook/whatsapp' \
  -H 'Content-Type: application/json' \
  -d '{
    "object": "whatsapp_business_account",
    "entry": [{
      "id": "0",
      "changes": [{
        "field": "messages",
        "value": {
          "messaging_product": "whatsapp",
          "metadata": {
            "display_phone_number": "16505551111",
            "phone_number_id": "123456123"
          },
          "contacts": [{
            "profile": {"name": "test user name"},
            "wa_id": "16315551181"
          }],
          "messages": [{
            "from": "16315551181",
            "id": "ABGGFlA5Fpa",
            "timestamp": "1504902988",
            "type": "text",
            "text": {"body": "catalog"}
          }]
        }
      }]
    }]
  }'
```

**Note:** Your webhook will receive and process the message, but sending replies will fail with error 131030 unless the sender is whitelisted.

## Improved Error Handling

The application now provides clearer error messages:

### Before:
```
[ERROR] WhatsApp sendMessage error: Client error...
```

### After:
```
[WARNING] WhatsApp: Recipient not in allowed list (test mode restriction)
{
  "to": "16315551181",
  "error_code": 131030,
  "hint": "Add this number to your WhatsApp Business API allowed list in Meta Developer Console",
  "docs": "https://developers.facebook.com/docs/whatsapp/cloud-api/get-started#send-messages"
}
```

## Common Errors

| Error Code | Message | Solution |
|------------|---------|----------|
| 131030 | Recipient not in allowed list | Add number to allowed list or move to production |
| 190 | Access token expired | Generate new access token in Meta Console |
| 100 | Invalid phone number format | Ensure phone number includes country code |
| 131047 | Message undeliverable | Recipient has blocked your business number |

## Production Checklist

Before going to production:

- [ ] Business verified on Meta Business Manager
- [ ] App approved for `whatsapp_business_messaging` permission
- [ ] Long-lived access token generated (doesn't expire in 24h)
- [ ] Display name and profile photo set for your business
- [ ] Privacy policy URL configured
- [ ] Message templates created and approved (for proactive messages)
- [ ] Webhook is publicly accessible with HTTPS
- [ ] Rate limits understood (1000 messages/day for free tier)

## Resources

- [WhatsApp Business API Documentation](https://developers.facebook.com/docs/whatsapp/cloud-api/)
- [Get Started Guide](https://developers.facebook.com/docs/whatsapp/cloud-api/get-started)
- [Webhook Setup](https://developers.facebook.com/docs/whatsapp/cloud-api/webhooks/components)
- [Error Codes Reference](https://developers.facebook.com/docs/whatsapp/cloud-api/support/error-codes)
