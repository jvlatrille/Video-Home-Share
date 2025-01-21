#!/bin/bash

# Chemin vers le fichier .env contenant les variables d'environnement pour les connexions
ENV_FILE="../config/.env"

# Charger les variables depuis le fichier .env 
if [ -f $ENV_FILE ]; then
    export $(grep -v '^#' $ENV_FILE | xargs)
else
    echo "Erreur : Pas de fichier .env chargé."
    exit 1
fi

# Nom du fichier de sauvegarde avec date et heure
DATE=$(date +"%Y-%m-%d_%H-%M-%S")
BACKUP_FILE="backup_${DB_NAME}_${DATE}.sql"
LOCAL_BACKUP_PATH="${LOCAL_BACKUP_DIR}/${BACKUP_FILE}"

# 1. Sauvegarder la base de données en local
echo ">>> Sauvegarde de la base de données..."
mysqldump -u $DB_USER -p$DB_PASSWORD $DB_NAME > $LOCAL_BACKUP_PATH
if [ $? -ne 0 ]; then
    echo "Erreur : Échec de la sauvegarde de la base de données."
    exit 1
fi
echo "Sauvegarde réussie : $LOCAL_BACKUP_PATH"

# 2. Envoyer la sauvegarde sur le Raspberry Pi
echo ">>> Envoi de la sauvegarde vers le Raspberry Pi..."
scp -P $REMOTE_PORT $LOCAL_BACKUP_PATH $REMOTE_USER@$REMOTE_HOST:$REMOTE_DIR
if [ $? -ne 0 ]; then
    echo "Erreur : Échec de l'envoi du fichier au Raspberry Pi."
    exit 1
fi
echo "Fichier envoyé avec succès sur $REMOTE_HOST:$REMOTE_DIR"

echo ">>> Script terminé avec succès."
