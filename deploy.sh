#!/bin/bash
# Variables pour le nom de l'image et le tag
IMAGE_NAME="servex/portail"
IMAGE_TAG="20251216"
REGISTRY_URL="docker.io"  # Docker Hub

# Fonction pour afficher des messages en couleur
function echo_error() {
    echo -e "\033[31m[ERROR]\033[0m $1"
}

function echo_success() {
    echo -e "\033[32m[SUCCESS]\033[0m $1"
}

function echo_info() {
    echo -e "\033[34m[INFO]\033[0m $1"
}

# Étape 1 : Création de l'image Docker avec un tag spécifique
echo_info "Création de l'image Docker à l'aide de Dockerfile avec un tag $IMAGE_TAG..."
docker build -t $REGISTRY_URL/$IMAGE_NAME:$IMAGE_TAG -f Dockerfile .

if [ $? -ne 0 ]; then
    echo_error "Échec de la création de l'image Docker."
    exit 1
else
    echo_success "Image Docker créée avec succès."
fi

# Étape 2 : Pousser l'image sur le registre
echo_info "Envoi de l'image Docker vers le registre $REGISTRY_URL..."
docker push $REGISTRY_URL/$IMAGE_NAME:$IMAGE_TAG

if [ $? -ne 0 ]; then
    echo_error "Échec de l'envoi de l'image Docker."
    exit 1
else
    echo_success "Image Docker envoyée avec succès: $REGISTRY_URL/$IMAGE_NAME:$IMAGE_TAG"
fi

# Pause pour garder le terminal ouvert
read -p "Appuyez sur une touche pour continuer..."
