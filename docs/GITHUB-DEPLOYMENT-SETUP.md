# GitHub Deployment Setup

This document lists all GitHub Secrets and Variables required for the automated deployment to Raspberry Pi.

## Required GitHub Secrets

Configure these in: **Settings → Secrets and variables → Actions → Secrets**

| Secret Name | Description | Example/Notes |
|------------|-------------|---------------|
| `APP_KEY` | Laravel application key | Generate with `php artisan key:generate` |
| `DB_PASSWORD` | Database password | Strong password for MySQL |
| `DOCKER_USERNAME` | Docker Hub username | Your Docker Hub account |
| `DOCKER_PASSWORD` | Docker Hub password/token | Docker Hub access token |
| `MAIL_PASSWORD` | Email SMTP password | Only if using email |
| `TELEGRAM_TOKEN` | Telegram bot token | From @BotFather |
| `WHATSAPP_ACCESS_TOKEN` | WhatsApp access token | From Meta Developers Console |
| `WHATSAPP_WEBHOOK_VERIFY_TOKEN` | Webhook verification token | Generate: `openssl rand -hex 32` |
| `WHATSAPP_APP_SECRET` | ⚠️ **CRITICAL** WhatsApp app secret | From Meta Developers Console → Settings → Basic |

## Required GitHub Variables

Configure these in: **Settings → Secrets and variables → Actions → Variables**

| Variable Name | Description | Example |
|--------------|-------------|---------|
| `APP_NAME` | Application name | `Chats crm system` |
| `APP_DEBUG` | Debug mode | `false` (production) |
| `APP_URL` | Application URL | `https://yourdomain.com` |
| `APP_PORT` | Application port | `8053` |
| `LOG_LEVEL` | Logging level | `info` or `debug` |
| `DB_HOST` | Database host | `mysql` or IP address |
| `DB_DATABASE` | Database name | `bots-crm` |
| `DB_USERNAME` | Database username | `dbuser` |
| `MAIL_MAILER` | Mail driver | `smtp` or `log` |
| `MAIL_HOST` | SMTP host | `smtp.mailtrap.io` |
| `MAIL_PORT` | SMTP port | `2525` |
| `MAIL_USERNAME` | SMTP username | Your SMTP user |
| `MAIL_FROM_ADDRESS` | From email address | `hello@example.com` |
| `TELEGRAM_BOT_USERNAME` | Telegram bot username | `@your_bot_username` |
| `WHATSAPP_PHONE_NUMBER_ID` | WhatsApp phone number ID | From Meta Developers Console |
| `WHATSAPP_API_VERSION` | WhatsApp API version | `v24.0` |
| `WHATSAPP_BUSINESS_ACCOUNT_ID` | Business account ID | From Meta Developers Console |
| `WHATSAPP_BUSINESS_PHONE` | Business phone number | `16505551234` (with country code) |
| `INSTAGRAM_USERNAME` | Instagram business username | `your_instagram_username` |

## ⚠️ Critical Security Variables

### WHATSAPP_APP_SECRET

This is a **SECRET**, not a variable. It's critical for webhook security.

**How to get it:**
1. Go to [Facebook Developers Console](https://developers.facebook.com/)
2. Select your WhatsApp Business App
3. Navigate to **Settings → Basic**
4. Click **Show** next to **App Secret**
5. Copy the entire secret (usually a long alphanumeric string)
6. Add it to GitHub Secrets as `WHATSAPP_APP_SECRET`

**Why it's critical:**
- Without this, anyone can send fake webhook requests to your application
- Required for signature verification (see [WHATSAPP-WEBHOOK-SECURITY.md](./WHATSAPP-WEBHOOK-SECURITY.md))
- Production deployments will fail security checks without it

## Setup Instructions

### 1. Configure Secrets

```bash
# Navigate to your GitHub repository
# Go to: Settings → Secrets and variables → Actions → Secrets
# Click "New repository secret" for each secret listed above
```

### 2. Configure Variables

```bash
# Navigate to your GitHub repository
# Go to: Settings → Secrets and variables → Actions → Variables
# Click "New repository variable" for each variable listed above
```

### 3. Verify Configuration

After configuring all secrets and variables, test the deployment:

1. Go to **Actions** tab
2. Select **Deploy to Raspberry Pi** workflow
3. Click **Run workflow**
4. Select branch and image tag
5. Monitor the deployment logs

## Troubleshooting

### Missing Secrets/Variables

If deployment fails with "missing variable" errors:

1. Check the error message for which variable is missing
2. Verify it's configured in GitHub Settings
3. Ensure the variable name matches exactly (case-sensitive)
4. Re-run the workflow

### WhatsApp Signature Verification Failing

If you see "Invalid signature" errors in logs:

1. Verify `WHATSAPP_APP_SECRET` is set correctly
2. Ensure you copied the full secret from Meta dashboard
3. Check you're using the correct Meta app
4. See [WHATSAPP-WEBHOOK-SECURITY.md](./WHATSAPP-WEBHOOK-SECURITY.md) for detailed troubleshooting

### Database Connection Errors

1. Verify `DB_HOST`, `DB_DATABASE`, `DB_USERNAME` match your setup
2. Ensure `DB_PASSWORD` secret is set correctly
3. Check database container is running on Raspberry Pi

## Environment Checklist

Before deploying to production, verify all items are configured:

### Secrets
- [ ] `APP_KEY`
- [ ] `DB_PASSWORD`
- [ ] `DOCKER_USERNAME`
- [ ] `DOCKER_PASSWORD`
- [ ] `TELEGRAM_TOKEN`
- [ ] `WHATSAPP_ACCESS_TOKEN`
- [ ] `WHATSAPP_WEBHOOK_VERIFY_TOKEN`
- [ ] `WHATSAPP_APP_SECRET` ⚠️ **CRITICAL**
- [ ] `MAIL_PASSWORD` (if using email)

### Variables
- [ ] `APP_NAME`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL`
- [ ] `APP_PORT`
- [ ] `LOG_LEVEL`
- [ ] `DB_HOST`
- [ ] `DB_DATABASE`
- [ ] `DB_USERNAME`
- [ ] `WHATSAPP_PHONE_NUMBER_ID`
- [ ] `WHATSAPP_API_VERSION`
- [ ] `WHATSAPP_BUSINESS_ACCOUNT_ID`
- [ ] `WHATSAPP_BUSINESS_PHONE`
- [ ] `INSTAGRAM_USERNAME`
- [ ] Mail settings (if applicable)

## Related Documentation

- [Main Deployment Guide](./DEPLOY.md) - General deployment instructions
- [WhatsApp Webhook Security](./WHATSAPP-WEBHOOK-SECURITY.md) - Critical security setup
- [Logging Stack](./DEPLOY-LOGGING.md) - Grafana Loki setup

---

**Last Updated**: 2025-12-19
