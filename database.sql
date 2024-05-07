CREATE DATABASE IF NOT EXISTS SchoolGameDB;

USE SchoolGameDB;

CREATE TABLE IF NOT EXISTS Students (
    id INT AUTO_INCREMENT NOT NULL UNIQUE,
    username VARCHAR(25) NOT NULL UNIQUE,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(64) NOT NULL,
    surname VARCHAR(25) NOT NULL,
    name VARCHAR(25) NOT NULL,
    class INT NOT NULL,

    PRIMARY KEY (id),
    FOREIGN KEY (class) REFERENCES (Class)
)

CREATE TABLE IF NOT EXISTS Classes (
    id INT AUTO_INCREMENT NOT NULL UNIQUE,
    tag VARCHAR(15) NOT NULL UNIQUE,

    PRIMARY KEY (id),
)

CREATE TABLE IF NOT EXISTS VirtualClasses (
    id INT AUTO_INCREMENT NOT NULL UNIQUE,
    joinLink VARCHAR(255) NOT NULL UNIQUE,
    tag VARCHAR(15) NOT NULL UNIQUE,
    subject VARCHAR(25) NOT NULL,

    PRIMARY KEY (id)
)

CREATE TABLE IF NOT EXISTS Links (
    id INT AUTO_INCREMENT NOT NULL UNIQUE,
    virtualClass INT NOT NULL,
    student INT NOT NULL,

    PRIMARY KEY (id),
    FOREIGN KEY (virtualClass, student) REFERENCES (VirtualClasses, Students)
)

CREATE TABLE IF NOT EXISTS Teachers (
    id INT AUTO_INCREMENT NOT NULL UNIQUE,
    name VARCHAR(30) NOT NULL,
    surname VARCHAR(30) NOT NULL,
    email VARCHAR(40) NOT NULL UNIQUE,
    username VARCHAR(16) NOT NULL UNIQUE,
    password VARCHAR(25) NOT NULL,

    PRIMARY KEY (id)
)

CREATE TABLE IF NOT EXISTS Arguments (
    id INT AUTO_INCREMENT NOT NULL UNIQUE,
    name VARCHAR(25) NOT NULL,

    PRIMARY KEY (id)
)

CREATE TABLE Games (
    id INT AUTO_INCREMENT NOT NULL UNIQUE,
    title VARCHAR(25) NOT NULL,
    virtualCoins INT NOT NULL,
    argument INT NOT NULL,

    PRIMARY KEY(id),
    FOREIGN KEY(argument) REFERENCES (Arguments)
)

CREATE TABLE References (
    id INT AUTO_INCREMENT NOT NULL UNIQUE,
    game INT NOT NULL,
    virtualcClass INT NOT NULL,

    PRIMARY KEY(id),
    FOREIGN KEY(virtualClass, game) REFERENCES (VirtualClasses, Games)
)