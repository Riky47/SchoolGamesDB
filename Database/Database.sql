-- Active: 1715887640611@@127.0.0.1@3306
CREATE DATABASE IF NOT EXISTS SchoolGamesDB;

USE SchoolGamesDB;

CREATE TABLE IF NOT EXISTS Classes (
    id INT AUTO_INCREMENT NOT NULL UNIQUE,
    tag VARCHAR(15) NOT NULL UNIQUE,
    --
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS Students (
    id INT AUTO_INCREMENT NOT NULL UNIQUE,
    username VARCHAR(25) NOT NULL UNIQUE,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    surname VARCHAR(25) NOT NULL,
    name VARCHAR(25) NOT NULL,
    class INT NOT NULL,
    --
    PRIMARY KEY (id),
    FOREIGN KEY (class) REFERENCES Classes(id)
);

CREATE TABLE IF NOT EXISTS Teachers (
    id INT AUTO_INCREMENT NOT NULL UNIQUE,
    username VARCHAR(16) NOT NULL UNIQUE,
    email VARCHAR(40) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    surname VARCHAR(30) NOT NULL,
    name VARCHAR(30) NOT NULL,
    --
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS VirtualClasses (
    id INT AUTO_INCREMENT NOT NULL UNIQUE,
    tag VARCHAR(15) NOT NULL UNIQUE,
    subject VARCHAR(25) NOT NULL,
    teacher INT NOT NULL,
    --
    PRIMARY KEY (id),
    FOREIGN KEY (teacher) REFERENCES Teachers(id)
);

CREATE TABLE IF NOT EXISTS Arguments (
    id INT AUTO_INCREMENT NOT NULL UNIQUE,
    tag VARCHAR(25) NOT NULL,
    --
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS Games (
    id INT AUTO_INCREMENT NOT NULL UNIQUE,
    coins INT NOT NULL CHECK (coins >= 0),
    description VARCHAR(160) NOT NULL,
    title VARCHAR(25) NOT NULL,
    argument INT NOT NULL,
    --
    PRIMARY KEY(id),
    FOREIGN KEY(argument) REFERENCES Arguments(id)
);

CREATE TABLE IF NOT EXISTS LinksUsers (
    id INT AUTO_INCREMENT NOT NULL UNIQUE,
    virtualClass INT NOT NULL,
    student INT NOT NULL,
    --
    PRIMARY KEY (id),
    FOREIGN KEY (student) REFERENCES Students(id),
    FOREIGN KEY (virtualClass) REFERENCES VirtualClasses(id)
);

CREATE TABLE IF NOT EXISTS LinksGames (
    id INT AUTO_INCREMENT NOT NULL UNIQUE,
    virtualClass INT NOT NULL,
    game INT NOT NULL,
    --
    PRIMARY KEY (id),
    FOREIGN KEY (game) REFERENCES Games(id),
    FOREIGN KEY (virtualClass) REFERENCES VirtualClasses(id)
);

CREATE TABLE IF NOT EXISTS Rewards (
    id INT AUTO_INCREMENT NOT NULL UNIQUE,
    coins INT NOT NULL CHECK (coins >= 0),
    student INT NOT NULL,
    game INT NOT NULL,
    --
    PRIMARY KEY (id),
    FOREIGN KEY (game) REFERENCES Games(id),
    FOREIGN KEY (student) REFERENCES Students(id)
);
