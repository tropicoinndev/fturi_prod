#!/bin/bash

TELEGRAM_TOKEN="6360192292:AAHhJb6zZQoQx7hYV6Ywwz6B0lbXIRAd2sA"
CHAT_ID="-4120357646"
REPO="/home/tropico/backup"
DUMP_FILE="/home/tropico/backup/backup_fturi_$(date +%Y-%m-%d_%H-%M-%S).sql"
export BORG_PASSPHRASE="fturi"

backup_message="Backup automatico FTuri (192.168.3.212):
"

send_message() {
    local message="$1"
    curl -s -X POST "https://api.telegram.org/bot${TELEGRAM_TOKEN}/sendMessage" -d chat_id="${CHAT_ID}" -d text="${message}"
}


create_bk_psql() {
    local db_name="fturi_poduccion"
    PGPASSWORD="Tropico_Dev_PG" pg_dump -U tropico_dev "$db_name" > "$DUMP_FILE"

    if [ $? -eq 0 ]; then
        backup_message+="• Backup de PostgreSQL creado exitosamente: $DUMP_FILE
        "
    else
        backup_message+="• ERROR al crear el backup de PostgreSQL ($DUMP_FILE)
        "
        exit 1
    fi
}


create_bk_project() {


    local backup_name="$(date +%Y-%m-%d_%H-%M-%S)"

    borg create --stats --progress "${REPO}::${backup_name}" "$DUMP_FILE" /var/www/html

    if [ $? -eq 0 ]; then
        backup_message+="• Backup del proyecto creado exitosamente: ${backup_name}
        "
    else
        backup_message+="• ERROR al crear el backup del proyecto
        "
        exit 1
    fi

    rm -f "$DUMP_FILE"
    backup_message+="• Dump de PostgreSQL borrado: $DUMP_FILE backup DB en borg
    "
}

create_bk_psql
create_bk_project
unset BORG_PASSPHRASE
backup_message+="• Script Backup ejecutado (borrado de cache con exito)
"
send_message "$backup_message"
