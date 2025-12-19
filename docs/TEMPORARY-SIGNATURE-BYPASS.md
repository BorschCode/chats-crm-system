# Temporary Signature Verification Bypass

## Status: ⚠️ SECURITY VERIFICATION DISABLED

**Date**: 2025-12-19
**Reason**: Meta Developer Console UI broken - cannot access App Secret

---

## What Was Changed

Signature verification in `app/Http/Middleware/VerifyWhatsAppSignature.php` has been **temporarily disabled**.

### Current Behavior
- ✅ All WhatsApp webhook requests are **allowed through**
- ⚠️ **NO signature verification** is performed
- 📝 Everything is **logged** for manual verification later

### What's Being Logged

Every webhook request logs:
- `received_signature` - The signature sent by Meta
- `payload` - The full request body
- `payload_length` - Size of the payload
- `app_secret_configured` - Whether secret is set
- `app_secret_value` - Current secret value (for comparison)
- `headers` - All HTTP headers
- `ip` - Client IP address

---

## Security Implications

⚠️ **IMPORTANT**: Without signature verification:
- Anyone can send fake webhook requests to your endpoint
- Malicious actors could trigger unauthorized actions
- You're vulnerable to spoofed WhatsApp messages

**This is acceptable for:**
- ✅ Development and testing
- ✅ Short-term workaround (until Meta UI works)

**NOT acceptable for:**
- ❌ Production with real users
- ❌ Handling sensitive data
- ❌ Long-term deployment

---

## How to Re-Enable Signature Verification

Once Meta's UI works and you can access the App Secret:

### Step 1: Get App Secret
1. Open: https://developers.facebook.com/apps/1357527259252782/settings/basic/
2. Find "App secret" (not "Client token")
3. Click **[Show]** button
4. Copy the entire secret

### Step 2: Update .env
```env
WHATSAPP_APP_SECRET=paste_your_actual_app_secret_here
```

### Step 3: Clear Config Cache
```bash
docker exec chats-crm-app php artisan config:clear
```

### Step 4: Edit Middleware
Open: `app/Http/Middleware/VerifyWhatsAppSignature.php`

**Comment out the bypass section:**
```php
// BYPASSED: Allow all requests through without verification
// return $next($request);
```

**Uncomment the verification code:**
Remove the `/*` and `*/` around lines 50-96

### Step 5: Restart Application
```bash
docker compose -f compose.raspberry.yml restart app
```

### Step 6: Verify It Works
```bash
# Check configuration
docker exec chats-crm-app php artisan whatsapp:verify-config

# Send a test message via WhatsApp
# Check logs for:
docker logs chats-crm-app -f | grep "signature verified"
```

You should see:
```
WhatsApp webhook signature verified successfully
```

---

## Manual Verification (While Bypassed)

You can manually verify signatures using the logged data:

### Example Log Entry
```json
{
  "received_signature": "sha256=abc123...",
  "payload": "{\"entry\":[...]}",
  "app_secret_value": "your_secret_here"
}
```

### Verify Manually
```bash
# Using the logged values:
echo -n '<payload>' | openssl dgst -sha256 -hmac '<app_secret_value>'

# Should match the received_signature (without 'sha256=' prefix)
```

### PHP Test Script
```php
<?php
$payload = '{"entry":[...]}'; // From logs
$appSecret = 'your_secret_here'; // From logs
$receivedSignature = 'sha256=abc123...'; // From logs

$expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $appSecret);

if (hash_equals($expectedSignature, $receivedSignature)) {
    echo "✅ Signature valid!\n";
} else {
    echo "❌ Signature mismatch!\n";
    echo "Expected: $expectedSignature\n";
    echo "Received: $receivedSignature\n";
}
```

---

## Monitoring

While verification is disabled, monitor logs for:

### ⚠️ Warning Messages
```
WhatsApp webhook signature verification BYPASSED (Meta UI broken)
```

This appears on **every webhook request** - it's normal.

### 🔍 What to Check
- IP addresses (should be from Meta's servers, not random IPs)
- Payload format (should match WhatsApp webhook structure)
- Frequency (should match your actual WhatsApp activity)

### 🚨 Red Flags
- Requests from suspicious IPs
- Malformed payloads
- High frequency of requests (potential abuse)
- Unexpected message content

---

## Alternative: Use Meta's Test Mode

While signature verification is disabled, you can use Meta's test mode:

1. Go to WhatsApp > API Setup in Meta Console
2. Use the "Test number" feature
3. This limits who can send messages to your bot
4. Reduces security risk during development

---

## Contact Meta Support

If the UI issue persists:

1. Report the bug to Meta:
   - https://developers.facebook.com/support/bugs/

2. Include:
   - App ID: `1357527259252782`
   - Console errors from browser
   - Screenshot of broken page
   - Browser/OS details

3. Request:
   - Access to App Secret via alternative method
   - API endpoint to retrieve App Secret
   - Timeline for UI fix

---

## Files Modified

- `app/Http/Middleware/VerifyWhatsAppSignature.php` - Verification bypassed
- `docs/TEMPORARY-SIGNATURE-BYPASS.md` - This document

---

## Checklist

- [ ] Signature verification is currently **DISABLED**
- [ ] All webhook data is being **logged**
- [ ] Meta Developer Console UI issue is **blocking** App Secret access
- [ ] Security implications are **understood**
- [ ] Plan to **re-enable** verification once App Secret is available
- [ ] Monitoring logs for **suspicious activity**

---

**Last Updated**: 2025-12-19
**Status**: Awaiting Meta UI fix
