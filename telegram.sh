#!/bin/bash

TELEGRAM_TOKEN="6360192292:AAHhJb6zZQoQx7hYV6Ywwz6B0lbXIRAd2sA"
CHAT_ID="-4120357646"
REPO="/home/tropico/backup"
DUMP_FILE="/home/tropico/backup/backup_fturi_$(date +%Y-%m-%d_%H-%M-%S).sql"

send_message() {
    local message="$1"
    curl -s -X POST "https://api.telegram.org/bot${TELEGRAM_TOKEN}/sendMessage" -d chat_id="${CHAT_ID}" -d text="${message}"
}
send_message "Dump de PostgreSQL"
