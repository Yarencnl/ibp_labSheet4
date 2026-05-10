-- ============================================
-- Lab Sheet 4 - Authentication System
-- Veritabanı ve Tablo Oluşturma
-- ============================================

CREATE DATABASE IF NOT EXISTS MyFirstDatabase
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE MyFirstDatabase;

CREATE TABLE IF NOT EXISTS tbl_user (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(50)  NOT NULL UNIQUE,
    email       VARCHAR(100) NOT NULL UNIQUE,
    password    VARCHAR(64)  NOT NULL COMMENT 'SHA-256 hash',
    role        ENUM('admin','user') DEFAULT 'user',
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS fraud (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    username     VARCHAR(50)  NOT NULL,
    ip_address   VARCHAR(45),
    attempt_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    reason       VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS visitor (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    username     VARCHAR(50),
    ip_address   VARCHAR(45),
    visit_time   DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Test verisi: şifre "password123" için SHA-256
-- SHA256('password123') = ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f

INSERT INTO tbl_user (username, email, password, role) VALUES
('admin', 'admin@example.com', SHA2('password123', 256), 'admin'),
('ahmet', 'ahmet@example.com', SHA2('ahmet123',    256), 'user');
