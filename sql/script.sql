-- Création de la base de données
CREATE DATABASE IF NOT EXISTS gestion_journal;
USE gestion_journal;

-- Table des utilisateurs et administrateurs
CREATE TABLE journal_user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    mdp VARCHAR(255) NOT NULL,
    mail VARCHAR(100) NOT NULL UNIQUE,
    is_admin BOOLEAN NOT NULL DEFAULT FALSE
);
