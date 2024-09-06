-- Drop existing database if needed
DROP DATABASE IF EXISTS Todozz;
CREATE DATABASE Todozz;
USE Todozz;

-- Existing users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    premium BOOLEAN DEFAULT FALSE
);

-- Existing projects table
CREATE TABLE Projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Existing tasks table
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT,
    description TEXT NOT NULL,
    completed BOOLEAN DEFAULT FALSE,
    name VARCHAR(255) NOT NULL,
    FOREIGN KEY (project_id) REFERENCES Projects(id) ON DELETE CASCADE
);

-- Existing user_projects table
CREATE TABLE user_projects (
    user_id INT,
    project_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES Projects(id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, project_id)
);

-- Archive tables for soft deletion
CREATE TABLE archived_projects (
    id INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    user_id INT,
    deleted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE archived_tasks (
    id INT PRIMARY KEY,
    project_id INT,
    description TEXT NOT NULL,
    completed BOOLEAN DEFAULT FALSE,
    name VARCHAR(255) NOT NULL,
    deleted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES Projects(id) ON DELETE CASCADE
);

CREATE TABLE transactionz (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    daytransacted DATE NOT NULL DEFAULT (CURRENT_DATE), -- Set to the current date at the time of insertion
    token VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);