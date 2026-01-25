<?php
// config/db.php

$host = 'localhost';
$db = 'pet_adoption_db';
$user = 'root'; 
$pass = 'RootPassword123!';     
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    // First connect without database to create it if needed
    $pdoTemp = new PDO("mysql:host=$host;charset=$charset", $user, $pass, $options);
    $pdoTemp->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET $charset COLLATE utf8mb4_unicode_ci");
    $pdoTemp = null;

    // Now connect to the specific database
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Create tables if they don't exist
    // Users Table (Adopters and Shelter Users)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('adopter', 'shelter') NOT NULL,
            full_name VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ");

    // Extra Shelter Profile Data
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS shelters (
            user_id INT PRIMARY KEY,
            shelter_name VARCHAR(255) NOT NULL,
            city VARCHAR(100) NOT NULL,
            state VARCHAR(100) NOT NULL,
            description TEXT,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        );
    ");

    // Pets Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS pets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            shelter_id INT NOT NULL,
            name VARCHAR(100) NOT NULL,
            species VARCHAR(50) NOT NULL,
            breed VARCHAR(100),
            age_range ENUM('baby', 'young', 'adult', 'senior') NOT NULL,
            gender ENUM('male', 'female') NOT NULL,
            size ENUM('small', 'medium', 'large', 'extra_large') NOT NULL,
            location_city VARCHAR(100) NOT NULL,
            location_state VARCHAR(100) NOT NULL,
            adoption_fee DECIMAL(10, 2) DEFAULT 0.00,
            description TEXT,
            main_image LONGBLOB,
            status ENUM('available', 'pending', 'adopted') DEFAULT 'available',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (shelter_id) REFERENCES users(id) ON DELETE CASCADE
        );
    ");

    // Extra Pet Photos
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS pet_images (
            id INT AUTO_INCREMENT PRIMARY KEY,
            pet_id INT NOT NULL,
            image_path VARCHAR(255) NOT NULL,
            FOREIGN KEY (pet_id) REFERENCES pets(id) ON DELETE CASCADE
        );
    ");

    // Adoption Applications
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS adoption_applications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            pet_id INT NOT NULL,
            adopter_id INT NOT NULL,
            message TEXT,
            applicant_name VARCHAR(255),
            applicant_email VARCHAR(255),
            applicant_phone VARCHAR(50),
            status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (pet_id) REFERENCES pets(id) ON DELETE CASCADE,
            FOREIGN KEY (adopter_id) REFERENCES users(id) ON DELETE CASCADE
        );
    ");

} catch (\PDOException $e) {
    // In production, you'd log this and show a generic message
    die("Database connection failed: " . $e->getMessage());
}
?>
