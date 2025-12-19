# WhatsApp Webhook Security

## Overview

This document explains how WhatsApp webhook signature verification works and how to configure it properly to protect your application from malicious requests.

## ⚠️ Security Risk Without Verification

**Without signature verification, anyone can send fake WhatsApp messages to your webhook endpoint using tools like Postman or curl.** This could:

- Trigger unauthorized actions in your system
- Spam your users with fake messages
- Exploit business logic vulnerabilities
- Cause data corruption or unauthorized access

## How Signature Verification Works

Meta/Facebook signs every webhook request with a cryptographic signature using your **App Secret**. The signature is sent in the `X-Hub-Signature-256` header.

### Signature Process

1. **Meta creates signature**:
   ```
   signature = sha256(request_body + app_secret)
   ```

2. **Your app verifies signature**:
   ```
   expected_signature = sha256(received_body + your_app_secret)
   if (expected_signature === received_signature) {
       // Request is authentic
   }
   ```

3. **Middleware checks** happen **BEFORE** your controller processes the request

## Setup Instructions

### 1. Get Your App Secret from Meta

1. Go to [Facebook Developers Console](https://developers.facebook.com/)
2. Select your WhatsApp Business App
3. Navigate to **Settings > Basic**
4. Find your **App Secret** (click "Show" to reveal it)
5. Copy the App Secret

### 2. Add App Secret to Environment

Add the following to your `.env` file:

```env
WHATSAPP_APP_SECRET=your_app_secret_from_meta
```

**IMPORTANT**:
- Keep this secret secure - never commit it to version control
- Never share your App Secret publicly
- Rotate your App Secret if it's compromised

### 3. Verify Configuration

The signature verification middleware is already configured in `routes/api.php`:

```php
Route::post('/webhook/whatsapp', [WhatsAppWebhookController::class, 'handle'])
    ->middleware(VerifyWhatsAppSignature::class);
```

### 4. Test the Security

#### ✅ Valid Request (from Meta)
Meta signs all legitimate requests. These will pass verification:
```bash
# Meta sends requests with valid signature
X-Hub-Signature-256: sha256=abc123...
```

#### ❌ Invalid Request (from Postman/attacker)
Requests without valid signatures are **rejected with 401 Unauthorized**:

```bash
curl -X POST http://your-domain/api/webhook/whatsapp \
  -H "Content-Type: application/json" \
  -d '{"entry": [...]}'

# Response: 401 Unauthorized
# {"error": "Missing signature header"}
```

Even with a fake signature:
```bash
curl -X POST http://your-domain/api/webhook/whatsapp \
  -H "Content-Type: application/json" \
  -H "X-Hub-Signature-256: sha256=fake_signature" \
  -d '{"entry": [...]}'

# Response: 401 Unauthorized
# {"error": "Invalid signature"}
```

## Middleware Implementation

The `VerifyWhatsAppSignature` middleware (`app/Http/Middleware/VerifyWhatsAppSignature.php`) handles signature verification:

1. **Checks request method**: Only POST requests are verified (GET is for webhook setup)
2. **Retrieves App Secret**: Loads from `config('services.whatsapp.app_secret')`
3. **Extracts signature**: Gets `X-Hub-Signature-256` header from request
4. **Computes expected signature**: `sha256=hash_hmac('sha256', request_body, app_secret)`
5. **Compares signatures**: Uses timing-safe `hash_equals()` to prevent timing attacks
6. **Rejects invalid requests**: Returns 401 Unauthorized if signature doesn't match

## Security Best Practices

### ✅ DO

- **Always configure WHATSAPP_APP_SECRET** before going to production
- Use HTTPS for all webhook endpoints
- Keep your App Secret in environment variables, never in code
- Monitor logs for signature verification failures
- Rotate your App Secret if compromised

### ❌ DON'T

- Don't commit App Secret to version control
- Don't disable signature verification in production
- Don't share your App Secret with anyone
- Don't log the App Secret in plain text
- Don't use the same App Secret across multiple environments

## Backward Compatibility

If `WHATSAPP_APP_SECRET` is **not configured**, the middleware logs a warning but allows requests to pass:

```
WhatsApp App Secret is not configured.
Webhook signature verification is DISABLED.
This is a security risk!
```

**This is for development only.** You should configure the App Secret before deploying to production.

## Monitoring

### Check Logs

The middleware logs all signature verification attempts:

#### Success
```
WhatsApp webhook signature verified successfully
```

#### Missing Header
```
WhatsApp webhook request missing X-Hub-Signature-256 header
```

#### Invalid Signature
```
WhatsApp webhook signature verification failed
- received_signature: sha256=abc...
- expected_signature: sha256=xyz...
- ip: 1.2.3.4
```

### Alert on Failures

Consider setting up alerts for repeated signature verification failures, as this could indicate:
- An attack attempt
- Misconfigured App Secret
- Webhook configuration issues with Meta

## Testing in Development

### Option 1: Use Meta Test Mode
Meta's test mode still sends valid signatures. This is the recommended approach.

### Option 2: Disable Verification Temporarily
Only for **local development**:

1. Don't set `WHATSAPP_APP_SECRET` in `.env`
2. Middleware will log warnings but allow requests
3. **Remember to configure it before production!**

### Option 3: Generate Valid Signatures
For advanced testing, generate valid signatures:

```php
$payload = '{"entry":[...]}';
$appSecret = 'your_app_secret';
$signature = 'sha256=' . hash_hmac('sha256', $payload, $appSecret);

// Use in your test request:
// X-Hub-Signature-256: $signature
```

## Troubleshooting

### "Missing signature header" Error

**Cause**: Request doesn't include `X-Hub-Signature-256` header

**Solutions**:
1. Verify your webhook is receiving requests from Meta (not Postman)
2. Check Meta webhook configuration
3. Ensure you're using the correct webhook URL

### "Invalid signature" Error

**Cause**: Signature doesn't match computed signature

**Solutions**:
1. Verify `WHATSAPP_APP_SECRET` matches Meta dashboard
2. Ensure App Secret is for the correct app
3. Check that you're not modifying the request body before verification
4. Verify you copied the entire App Secret (no spaces/newlines)

### Webhook Setup Still Works (GET request)

**This is normal!** Signature verification only applies to POST requests (actual webhook events). GET requests are for webhook verification during setup and don't need signatures.

## Additional Security Layers

While signature verification prevents spoofed requests, consider additional security:

1. **Rate Limiting**: Prevent abuse from legitimate sources
2. **IP Whitelisting**: Restrict to Meta's IP ranges (if available)
3. **Request Validation**: Validate message structure and content
4. **Idempotency**: Store message IDs to prevent duplicate processing
5. **User Verification**: Verify sender phone numbers against your user database

## References

- [Meta Webhooks Security Documentation](https://developers.facebook.com/docs/graph-api/webhooks/getting-started#verification-requests)
- [WhatsApp Cloud API Documentation](https://developers.facebook.com/docs/whatsapp/cloud-api/webhooks/components)
- [OWASP Webhook Security](https://cheatsheetseries.owasp.org/cheatsheets/Webhook_Security_Cheat_Sheet.html)

---

**Last Updated**: 2025-12-19
**Applies To**: WhatsApp Cloud API v21.0+
