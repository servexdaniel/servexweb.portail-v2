-- Ce script est exécuté à chaque démarrage du conteneur
-- grâce au répertoire /docker-entrypoint-initdb.d/
-- Il crée/recrée l'utilisateur de l'application et lui donne les droits nécessaires

-- Remplacement des variables d'environnement par MariaDB
SET @db_user     = IFNULL(NULLIF('{{DB_USERNAME}}',''), 'servex');
SET @db_password = IFNULL(NULLIF('{{DB_PASSWORD}}',''), '');
SET @db_name     = IFNULL(NULLIF('{{DB_DATABASE}}',''), 'portail_db');

-- Création ou mise à jour de l'utilisateur
CREATE USER IF NOT EXISTS @db_user@'%' IDENTIFIED BY @db_password;

-- Si le mot de passe est vide dans le .env (mauvaise pratique mais parfois utilisé en dev), on le change quand même
ALTER USER @db_user@'%' IDENTIFIED BY @db_password;

-- Création de la base si elle n'existe pas encore
CREATE DATABASE IF NOT EXISTS `@db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Attribution des privilèges complets sur la base
GRANT ALL PRIVILEGES ON *.* TO @db_user@'%';

-- Pour être sûr que les changements sont bien pris en compte
FLUSH PRIVILEGES;
