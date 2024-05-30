-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 30, 2024 alle 09:27
-- Versione del server: 10.4.28-MariaDB
-- Versione PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `schoolgamesdb`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `arguments`
--

CREATE TABLE `arguments` (
  `id` int(11) NOT NULL,
  `tag` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `arguments`
--

INSERT INTO `arguments` (`id`, `tag`) VALUES
(1, 'Integrali'),
(2, 'Trigonometria Analitica'),
(3, 'Leopardi');

-- --------------------------------------------------------

--
-- Struttura della tabella `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `tag` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `classes`
--

INSERT INTO `classes` (`id`, `tag`) VALUES
(5, '1AI'),
(4, '2AI'),
(3, '3AI'),
(2, '4AI'),
(1, '5AI');

-- --------------------------------------------------------

--
-- Struttura della tabella `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `coins` int(11) NOT NULL CHECK (`coins` >= 0),
  `description` varchar(160) NOT NULL,
  `title` varchar(25) NOT NULL,
  `argument` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `games`
--

INSERT INTO `games` (`id`, `coins`, `description`, `title`, `argument`) VALUES
(1, 10, 'Primo esercizio sugli integrali', 'Esercizio 1', 1),
(2, 15, 'Poetica di leopardi', 'Poetica', 3),
(3, 20, 'I triangoli se fossero dei grafici', 'I triangoli', 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `linksgames`
--

CREATE TABLE `linksgames` (
  `id` int(11) NOT NULL,
  `virtualClass` int(11) NOT NULL,
  `game` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `linksgames`
--

INSERT INTO `linksgames` (`id`, `virtualClass`, `game`) VALUES
(2, 2, 1),
(3, 1, 2),
(4, 1, 3),
(5, 2, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `linksusers`
--

CREATE TABLE `linksusers` (
  `id` int(11) NOT NULL,
  `virtualClass` int(11) NOT NULL,
  `student` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `linksusers`
--

INSERT INTO `linksusers` (`id`, `virtualClass`, `student`) VALUES
(1, 2, 2),
(2, 2, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `rewards`
--

CREATE TABLE `rewards` (
  `id` int(11) NOT NULL,
  `coins` int(11) NOT NULL CHECK (`coins` >= 0),
  `student` int(11) NOT NULL,
  `game` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `rewards`
--

INSERT INTO `rewards` (`id`, `coins`, `student`, `game`) VALUES
(1, 17, 2, 3),
(2, 4, 2, 1),
(3, 10, 3, 1),
(4, 13, 3, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `surname` varchar(25) NOT NULL,
  `name` varchar(25) NOT NULL,
  `class` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `students`
--

INSERT INTO `students` (`id`, `username`, `email`, `password`, `surname`, `name`, `class`) VALUES
(2, 'Frank', 'francisco.franco@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$eXg3MXA5T2NaMUJpcy9UMA$7yNb8U45+UR67lxmV1cgB36axPrqRQdQLJYJp9FtRmM', 'Franco', 'Francisco', 1),
(3, 'Camomil', 'camillo.benso@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$YklCaUVzNzFVRWR3eWU1bQ$7lktvCTA++SpQ4DBnisLfXRzNUzaknMM6y8WT6HGXGw', 'Benso', 'Camillo', 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `username` varchar(16) NOT NULL,
  `email` varchar(40) NOT NULL,
  `password` varchar(255) NOT NULL,
  `surname` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `teachers`
--

INSERT INTO `teachers` (`id`, `username`, `email`, `password`, `surname`, `name`) VALUES
(1, 'Alcapone', 'al.capone@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$OWdYOFBCRi5YY2E4Mm50OA$Q0vmtOc5T0xPxTTQz835SSGvr4vfc165hL4WUU4PwUg', 'Al Capone', 'Capoccione');

-- --------------------------------------------------------

--
-- Struttura della tabella `virtualclasses`
--

CREATE TABLE `virtualclasses` (
  `id` int(11) NOT NULL,
  `tag` varchar(15) NOT NULL,
  `subject` varchar(25) NOT NULL,
  `teacher` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `virtualclasses`
--

INSERT INTO `virtualclasses` (`id`, `tag`, `subject`, `teacher`) VALUES
(1, '1VC', 'Matematica', 1),
(2, '2VC', 'Matematica', 1);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `arguments`
--
ALTER TABLE `arguments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indici per le tabelle `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `tag` (`tag`);

--
-- Indici per le tabelle `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `argument` (`argument`);

--
-- Indici per le tabelle `linksgames`
--
ALTER TABLE `linksgames`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `game` (`game`,`virtualClass`),
  ADD KEY `virtualClass` (`virtualClass`);

--
-- Indici per le tabelle `linksusers`
--
ALTER TABLE `linksusers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `student` (`student`,`virtualClass`),
  ADD KEY `virtualClass` (`virtualClass`);

--
-- Indici per le tabelle `rewards`
--
ALTER TABLE `rewards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `game` (`game`,`student`),
  ADD KEY `student` (`student`);

--
-- Indici per le tabelle `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `class` (`class`);

--
-- Indici per le tabelle `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indici per le tabelle `virtualclasses`
--
ALTER TABLE `virtualclasses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `tag` (`tag`),
  ADD KEY `teacher` (`teacher`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `arguments`
--
ALTER TABLE `arguments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `linksgames`
--
ALTER TABLE `linksgames`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `linksusers`
--
ALTER TABLE `linksusers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `rewards`
--
ALTER TABLE `rewards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `virtualclasses`
--
ALTER TABLE `virtualclasses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `games_ibfk_1` FOREIGN KEY (`argument`) REFERENCES `arguments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `linksgames`
--
ALTER TABLE `linksgames`
  ADD CONSTRAINT `linksgames_ibfk_1` FOREIGN KEY (`game`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `linksgames_ibfk_2` FOREIGN KEY (`virtualClass`) REFERENCES `virtualclasses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `linksusers`
--
ALTER TABLE `linksusers`
  ADD CONSTRAINT `linksusers_ibfk_1` FOREIGN KEY (`student`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `linksusers_ibfk_2` FOREIGN KEY (`virtualClass`) REFERENCES `virtualclasses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `rewards`
--
ALTER TABLE `rewards`
  ADD CONSTRAINT `rewards_ibfk_1` FOREIGN KEY (`game`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rewards_ibfk_2` FOREIGN KEY (`student`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`class`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `virtualclasses`
--
ALTER TABLE `virtualclasses`
  ADD CONSTRAINT `virtualclasses_ibfk_1` FOREIGN KEY (`teacher`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
