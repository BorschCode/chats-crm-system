# Logging Stack for Raspberry Pi

This setup provides professional log management with Grafana Loki.

## What's Included

- **Loki**: Log aggregation database
- **Promtail**: Log collector (scrapes Docker logs)
- **Grafana**: Web UI for viewing/searching logs

## Quick Start

### 1. Start the logging stack

```bash
docker compose -f compose.logging.yml up -d
```

### 2. Access Grafana

Open http://your-raspberry-pi:3000

- Username: `admin`
- Password: `admin` (change via GRAFANA_PASSWORD env var)

### 3. View Logs

1. Go to **Explore** (compass icon)
2. Select **Loki** datasource
3. Use LogQL queries:
   ```logql
   {container="chats-crm-app"}
   ```

## Useful LogQL Queries

```logql
# All logs from your app
{container="chats-crm-app"}

# Only ERROR logs
{container="chats-crm-app"} |= "ERROR"

# Webhook-related logs
{container="chats-crm-app"} |= "Webhook received"

# Last 1 hour of logs
{container="chats-crm-app"} [1h]

# Filter by log level (if structured)
{container="chats-crm-app"} | json | level="error"
```

## Data Retention

- Logs are kept for **7 days** (configurable in docker/loki/config.yml)
- Old logs are automatically deleted
- Grafana dashboards persist forever

## Stopping the Stack

```bash
# Stop but keep data
docker compose -f compose.logging.yml down

# Stop and remove data
docker compose -f compose.logging.yml down -v
```

## Disk Usage

Check volume sizes:
```bash
docker system df -v
```

## Integration with Your App

The Loki stack runs **independently** from your app:
- Deploy/redeploy your app anytime - logs keep collecting
- Restart Raspberry Pi - logging stack auto-restarts
- Logs persist across app container recreations

## Memory Usage (Raspberry Pi)

This stack is optimized for Raspberry Pi (low memory):
- Loki: ~100MB RAM
- Promtail: ~50MB RAM
- Grafana: ~100MB RAM
- Total: ~250MB additional RAM usage
