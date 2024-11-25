CREATE DATABASE IF NOT EXISTS my_databases;
USE my_databases;

-- Table principale : Utilisateurs
CREATE TABLE IF NOT EXISTS UserAccounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL ,
    username VARCHAR(50) NOT NULL ,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Ajouter des données de test
INSERT INTO UserAccounts (fullname, email, username, password) VALUES
('Jean Dupont', 'jean.dupont@example.com', 'jeandupont', '$2y$10$hashedpasswordexample1'),
('Alice Martin', 'alice.martin@example.com', 'alicemartin', '$2y$10$hashedpasswordexample2');
