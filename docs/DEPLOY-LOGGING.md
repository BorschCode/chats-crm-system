# Deploy Logging Stack to Raspberry Pi

## Summary of Changes

### ✅ Already Applied (Option 1)
- **Named volume** for logs in `compose.raspberry.yml`
- Logs persist between app deployments
- Access via: `docker exec chats-crm-app tail -f /var/www/html/storage/logs/laravel.log`

### 🚀 Optional (Option 2) - Grafana Loki Stack

Professional log management with web UI, search, and automatic retention.

## Deploy Option 2: Loki Stack

### On your Raspberry Pi:

1. **Pull the latest code:**
   ```bash
   cd /projects/chats-crm-runner/_work/chats-crm-system/chats-crm-system
   git pull origin develop
   ```

2. **Start the logging stack:**
   ```bash
   docker compose -f compose.logging.yml up -d
   ```

3. **Verify it's running:**
   ```bash
   docker compose -f compose.logging.yml ps
   ```

   You should see:
   - `loki` (running)
   - `promtail` (running)
   - `grafana` (running)

4. **Access Grafana:**
   - Open: `http://your-raspberry-pi-ip:3000`
   - Login: `admin` / `admin`
   - Navigate to **Explore** → Select **Loki**
   - Query: `{container="chats-crm-app"}`

## View Logs in Grafana

### Basic Queries:

```logql
# All app logs
{container="chats-crm-app"}

# Only errors
{container="chats-crm-app"} |= "ERROR"

# Webhook logs
{container="chats-crm-app"} |= "Webhook received"

# Info logs
{container="chats-crm-app"} |= "INFO"
```

### Live Tail:
Click the "Live" button in Grafana Explore to see logs in real-time!

## Benefits of Loki Stack

✅ **Searchable**: Find logs by text, level, time range
✅ **Persistent**: Keeps 7 days of logs automatically
✅ **Auto-cleanup**: Old logs deleted automatically
✅ **Independent**: Survives app redeployments
✅ **Low memory**: Only ~250MB RAM on Raspberry Pi
✅ **Web UI**: Beautiful Grafana interface

## Backup & Maintenance

### Backup Loki data:
```bash
docker run --rm -v chats-crm-system_loki-data:/data -v $(pwd):/backup \
  ubuntu tar czf /backup/loki-backup.tar.gz /data
```

### Check disk usage:x
```bash
docker system df -v | grep loki
```

### Clear old data (if needed):
```bash
docker compose -f compose.logging.yml down
docker volume rm chats-crm-system_loki-data
docker compose -f compose.logging.yml up -d
```

## Stopping Logging Stack

```bash
# Stop but keep data
docker compose -f compose.logging.yml down

# Stop and remove all data
docker compose -f compose.logging.yml down -v
```

## Troubleshooting

### Logs not appearing in Grafana?

1. Check Promtail is running:
   ```bash
   docker logs promtail
   ```

2. Check Loki is receiving logs:
   ```bash
   curl -G -s "http://localhost:3100/loki/api/v1/query" \
     --data-urlencode 'query={container="chats-crm-app"}' | jq
   ```

3. Restart the stack:
   ```bash
   docker compose -f compose.logging.yml restart
   ```

### Out of memory?

Reduce retention in `docker/loki/config.yml`:
```yaml
limits_config:
  retention_period: 72h  # 3 days instead of 7
```

Then restart: `docker compose -f compose.logging.yml restart loki`
