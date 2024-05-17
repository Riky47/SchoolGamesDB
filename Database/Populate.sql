-- Active: 1712133423498@@127.0.0.1@3306@schoolgamesdb
INSERT INTO Classes(tag) VALUES
("5AI"), ("1C");

INSERT INTO Arguments(tag) VALUES
("Leopardi"), ("Ungaretti");

INSERT INTO Games(title, description, argument, coins) VALUES
("Vita", "Vita di leopardi", 1, 10), ("Poetica", "Poetica di ungaretti", 2, 10),
("Poetica", "Poetica di leopardi", 1, 15), ("Vita", "Vita di ungaretti", 2, 15);

INSERT INTO VirtualClasses(tag, subject, teacher) VALUES
("Class 1", "Italiano", 1);

INSERT INTO LinksGames(virtualClass, game) VALUES
(1, 1), (1, 3);