#!/usr/bin/env bash
# =============================================================================
# Script : generate-rabbitmq-certs.sh
# But    : Génère les 3 certificats SSL auto-signés nécessaires à RabbitMQ
#          (ca_certificate.pem, server_certificate.pem, server_key.pem)
#          pour activer MQTT/SSL (8883) et STOMP/SSL (41416)
# =============================================================================

set -euo pipefail

# Dossier où seront stockés les certificats
#CERTS_DIR="./docker/rabbitmq/certs"
CERTS_DIR="/etc/rabbitmq/certs"

# Si les 3 fichiers existent déjà → on ne fait rien (très rapide)
if [ -f "$CERTS_DIR/server_key.pem" ] && \
   [ -f "$CERTS_DIR/server_certificate.pem" ] && \
   [ -f "$CERTS_DIR/ca_certificate.pem" ]; then
  echo "Certificats SSL déjà présents → on passe directement au démarrage de RabbitMQ"
  exit 0
fi

echo "Création du dossier des certificats..."
mkdir -p "$CERTS_DIR"

echo "Génération du certificat auto-signé (valable 10 ans)..."

# Méthode qui contourne le bug Git Bash Windows
# On force le subject avec MSYS_NO_PATHCONV=1 ou via variable propre
MSYS_NO_PATHCONV=1 openssl req \
  -new \
  -newkey rsa:2048 \
  -days 3650 \
  -nodes \
  -x509 \
  -subj "/CN=rabbitmq-local" \
  -keyout "$CERTS_DIR/server_key.pem" \
  -out "$CERTS_DIR/server_certificate.pem"

# Copie pour le CA (même certificat en dev)
cp "$CERTS_DIR/server_certificate.pem" "$CERTS_DIR/ca_certificate.pem"

# Permissions RabbitMQ-friendly
chmod 644 "$CERTS_DIR/"*.pem
chmod 600 "$CERTS_DIR/server_key.pem"

echo ""
echo "Certificats générés avec succès :"
ls -l "$CERTS_DIR/"
echo ""
echo "Relancez votre stack :"
echo "   docker compose down && docker compose up -d"
echo ""
echo "Ports 8883 et 41416 seront bien actifs"
