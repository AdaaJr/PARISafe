CREATE DATABASE IF NOT EXISTS PARISafe;
USE PARISafe;

-- Table Utilisateur
CREATE TABLE IF NOT EXISTS Utilisateur (
    userID INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    typeUSER INT NOT NULL,
    motDePasse VARCHAR(255) NOT NULL
);

-- Insertions groupées pour la table Utilisateur
INSERT INTO Utilisateur(nom, email, typeUSER, motDePasse) VALUES ('Visiteur', 'visiteur@email.com', '0', SHA2('password123', 256)), ('Diabi Wali', 'wali@email.com', '1', SHA2('password123', 256));

-- Table Lieu
CREATE TABLE IF NOT EXISTS Lieu (
    lieuID INT AUTO_INCREMENT PRIMARY KEY,
    googlePlaceID VARCHAR(255) UNIQUE,
    nom VARCHAR(255) NOT NULL,
    typeLieu VARCHAR(255) NOT NULL,
    latitude DECIMAL(10, 8) NOT NULL,
    longitude DECIMAL(11, 8) NOT NULL,
    descriptions TEXT
);

-- Table Signalement
CREATE TABLE IF NOT EXISTS Signalement (
    signalementID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT,
    lieuID INT,
    descriptions TEXT NOT NULL,
    dateHeure TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    typeSignalement VARCHAR(255) NOT NULL,
    FOREIGN KEY (userID) REFERENCES Utilisateur(userID),
    FOREIGN KEY (lieuID) REFERENCES Lieu(lieuID)
);

