#!/usr/bin/env bash
# Updates the APP_URL GitHub Environment variable with the current ngrok tunnel URL,
# then sets the Telegram webhook inside the running Docker container.
#
# Required env vars:
#   GH_TOKEN      — GitHub personal access token (repo scope)
#   GH_ENV_NAME   — GitHub environment name (e.g. "raspberry")
# Optional:
#   NGROK_API_URL       — defaults to http://127.0.0.1:4040/api/tunnels
#   COMPOSE_FILE        — defaults to compose.raspberry.yml
#   WEBHOOK_PATH        — defaults to /api/webhook/telegram
#   SKIP_GH_UPDATE      — set to "1" to skip the GitHub variable update
set -euo pipefail

NGROK_API="${NGROK_API_URL:-http://127.0.0.1:4040/api/tunnels}"
GH_REPO="BorschCode/chats-crm-system"
GH_VAR="APP_URL"
COMPOSE_FILE="${COMPOSE_FILE:-compose.raspberry.yml}"
WEBHOOK_PATH="${WEBHOOK_PATH:-/api/webhook/telegram}"

# ── Validate required vars ──────────────────────────────────────────────────
if [ -z "${GH_TOKEN:-}" ] && [ "${SKIP_GH_UPDATE:-0}" != "1" ]; then
    echo "ERROR: GH_TOKEN is not set" >&2
    exit 1
fi
if [ -z "${GH_ENV_NAME:-}" ] && [ "${SKIP_GH_UPDATE:-0}" != "1" ]; then
    echo "ERROR: GH_ENV_NAME is not set (e.g. export GH_ENV_NAME=raspberry)" >&2
    exit 1
fi

# ── Fetch current ngrok tunnel URL ──────────────────────────────────────────
echo "Fetching ngrok tunnel URL from $NGROK_API ..."
response=$(curl -sf "$NGROK_API") || {
    echo "ERROR: Could not reach ngrok API at $NGROK_API" >&2
    exit 1
}

public_url=$(echo "$response" | python3 -c "
import sys, json
tunnels = json.load(sys.stdin).get('tunnels', [])
https = [t['public_url'] for t in tunnels if t.get('proto') == 'https']
result = https[0] if https else (tunnels[0]['public_url'] if tunnels else '')
print(result, end='')
")

if [ -z "$public_url" ]; then
    echo "ERROR: No active tunnels found in ngrok response" >&2
    exit 1
fi

echo "Current ngrok URL: $public_url"

# ── Optionally update GitHub Environment variable ───────────────────────────
if [ "${SKIP_GH_UPDATE:-0}" != "1" ]; then
    gh_response=$(curl -sf \
        -H "Authorization: Bearer $GH_TOKEN" \
        -H "Accept: application/vnd.github+json" \
        -H "X-GitHub-Api-Version: 2022-11-28" \
        "https://api.github.com/repos/${GH_REPO}/environments/${GH_ENV_NAME}/variables/${GH_VAR}" 2>/dev/null || echo "{}")

    current_value=$(echo "$gh_response" | python3 -c "
import sys, json
print(json.load(sys.stdin).get('value', ''), end='')
" 2>/dev/null || echo "")

    if [ "$current_value" = "$public_url" ]; then
        echo "${GH_VAR} unchanged: $public_url"
    else
        http_code=$(curl -s -o /dev/null -w "%{http_code}" \
            -X PATCH \
            -H "Authorization: Bearer $GH_TOKEN" \
            -H "Accept: application/vnd.github+json" \
            -H "X-GitHub-Api-Version: 2022-11-28" \
            -H "Content-Type: application/json" \
            "https://api.github.com/repos/${GH_REPO}/environments/${GH_ENV_NAME}/variables/${GH_VAR}" \
            -d "{\"name\":\"${GH_VAR}\",\"value\":\"${public_url}\"}")

        if [ "$http_code" = "204" ]; then
            echo "${GH_VAR} updated: ${current_value:-<not set>} → $public_url"
        else
            echo "ERROR: GitHub API returned HTTP $http_code when updating ${GH_VAR}" >&2
            exit 1
        fi
    fi
fi

# ── Set Telegram webhook ─────────────────────────────────────────────────────
webhook_url="${public_url%/}${WEBHOOK_PATH}"
echo "Setting Telegram webhook to: $webhook_url"

docker compose -f "$COMPOSE_FILE" exec -T app \
    php artisan nutgram:hook:set "$webhook_url"

echo "Telegram webhook set successfully."
