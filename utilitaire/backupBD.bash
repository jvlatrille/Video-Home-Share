#!/bin/bash

# Chemin vers le fichier .env
ENV_FILE="../config/.env"

# Charger les variables depuis le fichier .env
echo ">>> Chargement des variables d'environnement..."
if [ -f "$ENV_FILE" ]; then
    export $(grep -v '^#' "$ENV_FILE" | xargs)
    echo "Variables chargées avec succès."
else
    echo "Erreur : Pas de fichier .env chargé."
    exit 1
fi

# Nom du fichier de sauvegarde avec date et heure
echo ">>> Création du nom du fichier de sauvegarde..."
DATE=$(date +"%Y-%m-%d_%Hh%Mmin-%Ssec")
BACKUP_FILE="backup_${DB_NAME}_${DATE}.sql"
LOCAL_BACKUP_PATH="${LOCAL_BACKUP_DIR}/${BACKUP_FILE}"
echo "Nom du fichier de sauvegarde : $BACKUP_FILE"

# 1. Sauvegarder la base de données en local
echo ">>> Sauvegarde de la base de données..."
echo ""
echo "ENTRER LE MOT DE PASSE BD"
"$MYSQLDUMP_PATH" -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" > "$LOCAL_BACKUP_PATH"
if [ $? -ne 0 ]; then
    echo "Erreur : Échec de la sauvegarde de la base de données."
    exit 1
fi
echo "Sauvegarde locale réussie : $LOCAL_BACKUP_PATH"

# 2. Envoyer la sauvegarde sur le Raspberry Pi
echo ">>> Envoi de la sauvegarde vers le Raspberry Pi..."
echo ""
echo "ENTRER LE MOT DE PASSE VERS LE SERVEUR"
scp -P $REMOTE_PORT "$LOCAL_BACKUP_PATH" $REMOTE_USER@$REMOTE_HOST:$REMOTE_DIR
if [ $? -ne 0 ]; then
    echo "Erreur : Échec de l'envoi du fichier au Raspberry Pi."
    exit 1
fi
echo "Fichier envoyé avec succès sur $REMOTE_HOST:$REMOTE_DIR"

echo ">>> Script terminé avec succès !"
