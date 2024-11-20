-- Créer la base de données
CREATE DATABASE IF NOT EXISTS databases;
USE databases;

-- Table principale : Utilisateurs
CREATE TABLE IF NOT EXISTS UserAccounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Ajouter des données de test
INSERT INTO UserAccounts (fullname, email, username, password) VALUES
('Jean Dupont', 'jean.dupont@example.com', 'jeandupont', '$2y$10$hashedpasswordexample1'),
('Alice Martin', 'alice.martin@example.com', 'alicemartin', '$2y$10$hashedpasswordexample2');
