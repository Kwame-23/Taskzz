DROP DATABASE IF EXISTS Todozz;
CREATE DATABASE Todozz;
USE Todozz;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    premium BOOLEAN DEFAULT FALSE
);

CREATE TABLE Projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT,
    description TEXT NOT NULL,
    completed BOOLEAN DEFAULT FALSE,
    name VARCHAR(255) NOT NULL,
    FOREIGN KEY (project_id) REFERENCES Projects(id) ON DELETE CASCADE
);

CREATE TABLE user_projects (
    user_id INT,
    project_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES Projects(id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, project_id)
);
