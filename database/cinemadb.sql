-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Feb 09, 2022 alle 11:42
-- Versione del server: 10.4.18-MariaDB
-- Versione PHP: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cinemadb`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `Afferisce`
--

CREATE TABLE `Afferisce` (
  `IDFilm` int(11) NOT NULL,
  `IDCast` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `Afferisce`
--

INSERT INTO `Afferisce` (`IDFilm`, `IDCast`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(2, 7),
(2, 8),
(2, 9),
(2, 10),
(2, 11),
(2, 12),
(2, 13),
(2, 14),
(2, 15),
(3, 16),
(3, 17),
(3, 18),
(3, 19),
(3, 20),
(3, 21),
(4, 6),
(4, 22),
(4, 23),
(4, 24),
(4, 25);

-- --------------------------------------------------------

--
-- Struttura della tabella `Amministratori`
--

CREATE TABLE `Amministratori` (
  `Username` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `Amministratori`
--

INSERT INTO `Amministratori` (`Username`) VALUES
('admin');

-- --------------------------------------------------------

--
-- Struttura della tabella `CastFilm`
--

CREATE TABLE `CastFilm` (
  `Nome` varchar(50) NOT NULL,
  `Cognome` varchar(50) NOT NULL,
  `ID` int(11) NOT NULL,
  `Lingua` char(2) DEFAULT NULL,
  `Ruolo` char(1) NOT NULL
) ;

--
-- Dump dei dati per la tabella `CastFilm`
--

INSERT INTO `CastFilm` (`Nome`, `Cognome`, `ID`, `Lingua`, `Ruolo`) VALUES
('Ramona', 'Jones', 1, NULL, 'R'),
('Allen', 'Hart', 2, NULL, 'A'),
('Terri', 'Turner', 3, NULL, 'A'),
('Kevin', 'Gutierrez', 4, NULL, 'R'),
('Jose', 'Roy', 5, NULL, 'A'),
('Oliver', 'Carroll', 6, NULL, 'A'),
('Kelley', 'Hunter', 7, NULL, 'A'),
('Charles', 'Campbell', 8, NULL, 'A'),
('Erma', 'Rowe', 9, NULL, 'A'),
('Sherry', 'Malone', 10, NULL, 'A'),
('Greg', 'Brady', 11, NULL, 'R'),
('Constance', 'Fuller', 12, NULL, 'A'),
('Lila', 'Lawson', 13, NULL, 'A'),
('Orville', 'Ballard', 14, NULL, 'A'),
('Carolyn', 'Wallace', 15, NULL, 'A'),
('Sammy', 'Patrick', 16, NULL, 'A'),
('Stephanie', 'Hanson', 17, NULL, 'R'),
('Lyle', 'Bates', 18, NULL, 'A'),
('Rosalie', 'Benson', 19, NULL, 'A'),
('Cody', 'Bowers', 20, NULL, 'A'),
('Josefina', 'Fitzgerald', 21, NULL, 'A'),
('Bessie', 'Adams', 22, NULL, 'A'),
('Camille', 'Jackson', 23, NULL, 'A'),
('Myron', 'Santos', 24, 'en', 'A'),
('Amanda', 'Ingram', 25, 'en', 'R');

-- --------------------------------------------------------

--
-- Struttura della tabella `Film`
--

CREATE TABLE `Film` (
  `ID` int(11) NOT NULL,
  `Titolo` varchar(255) NOT NULL,
  `Genere` varchar(20) NOT NULL,
  `DataUscita` date DEFAULT NULL,
  `Descrizione` varchar(3000) NOT NULL,
  `SrcImg` varchar(100) NOT NULL,
  `CarouselImg` varchar(100) NOT NULL,
  `Durata` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `Film`
--

INSERT INTO `Film` (`ID`, `Titolo`, `Genere`, `DataUscita`, `Descrizione`, `SrcImg`, `CarouselImg`, `Durata`) VALUES
(1, 'Sonic 2', 'Azione', '2022-04-08', 'Dopo essersi stabilito a Green Hills, Sonic è pronto per una maggiore libertà, e Tom e Maddie accettano di lasciarlo a casa mentre vanno in vacanza. Ma, non appena se ne sono andati, il Dr. Robotnik ritorna, questa volta con un nuovo partner, Knuckles, alla ricerca di uno smeraldo che ha il potere di costruire e distruggere le civiltà. Sonic fa squadra con la sua spalla, Tails, e insieme intraprendono un viaggio per trovare lo smeraldo prima che cada nelle mani sbagliate. Diretto da Jeff Fowler e interpretato da Jim Carrey, James Marsden e Ben Schwartz.', 'sonic2-vertical.jpg', 'sonic2.jpg', 100),
(2, 'Spider-Man: No Way Home', 'Fantastico', '2021-12-15', 'Spider-Man No Way Home, il film diretto da Jon Watts, vede, per la prima volta nella storia cinematografica di Spider-Man, la vera identità del nostro amichevole supereroe di quartiere rivelata al mondo intero.\r\nLa vita del 17enne Peter Parker (Tom Holland), così come quella delle persone a cui tiene, finisce al centro dell\'attenzione dell\'opinione pubblica. I pericoli e il caos che circondano Spider-Man rischiano di costare a Peter, a MJ (Zendaya) e Ned (Jacob Batalon) - che i media considerano suoi complici a tutti gli effetti - l\'ammissione al college.\r\nIncapace di restare a guardare passivamente infrangersi i sogni dei suoi amici, Peter decide di rivolgersi al Dottor Strange (Benedict Cumberbatch), chiedendogli di ripristinare il suo segreto, facendo dimenticare a tutti che Peter Parker è Spider-Man. Strange non può rimanere indifferente alla supplica di Peter e decide di aiutarlo.\r\nPurtroppo, l\'incantesimo dell\'Oblio eseguito da Strange apre uno squarcio nel loro mondo, liberando i più potenti nemici mai affrontati da uno Spider-Man in qualsiasi universo. Ora Peter dovrà superare la sua più grande sfida, che non solo cambierà per sempre il suo futuro, ma anche quello del Multiverso.', 'nowayhome-vertical.jpg', 'nowayhome.jpg', 148),
(3, 'Uncharted', 'Azione', '2022-02-17', 'Basato su una delle serie di videogiochi più vendute e acclamate dalla critica, Uncharted presenta al pubblico il giovane e furbo Nathan Drake (Tom Holland) nella sua prima avventura alla ricerca del tesoro con l’arguto partner Victor “Sully” Sullivan (Mark Wahlberg). In un’epica avventura piena di azione che attraversa il mondo intero, i due protagonisti partono alla pericolosa ricerca del “più grande tesoro mai trovato”, inseguendo indizi che potrebbero condurli al fratello di Nathan, scomparso da tempo.', 'uncharted-vertical.jpg', 'uncharted.jpg', 125),
(4, 'Sing 2', 'Animazione', '2021-12-23', 'Sing 2, diretto da Garth Jennings, è il sequel dell\'omonimo film di successo che vedeva un gruppo di animali, capitanati dal koala Buster Moon, che per salvare il Moon Theatre dalla chiusura decide di indire una gara canora così da riportare il teatro al suo vecchio splendore. Questa volta i protagonisti dovranno abbandonare il Moon Theatre per debuttare su un palco ancora più prestigioso. Buster sogna infatti di esibirsi al Crystal Tower Theater nell\'incantevole Redshore City, ma senza nessuna conoscenza non sarà facile.', 'sing2-vertical.jpg', 'sing2.jpg', 105);

-- --------------------------------------------------------

--
-- Struttura della tabella `Orario`
--

CREATE TABLE `Orario` (
  `Ora` time NOT NULL,
  `IDProiezione` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `Orario`
--

INSERT INTO `Orario` (`Ora`, `IDProiezione`) VALUES
('15:00:00', 4),
('15:00:00', 6),
('15:00:00', 7),
('15:00:00', 12),
('15:00:00', 13),
('16:00:00', 1),
('16:00:00', 4),
('16:00:00', 6),
('16:00:00', 7),
('16:00:00', 12),
('16:00:00', 13),
('16:00:00', 15),
('17:30:00', 1),
('17:30:00', 4),
('17:30:00', 6),
('17:30:00', 7),
('17:30:00', 9),
('17:30:00', 11),
('17:30:00', 12),
('17:30:00', 13),
('17:30:00', 15),
('21:30:00', 1),
('21:30:00', 2),
('21:30:00', 3),
('21:30:00', 4),
('21:30:00', 5),
('21:30:00', 6),
('21:30:00', 7),
('21:30:00', 8),
('21:30:00', 9),
('21:30:00', 10),
('21:30:00', 11),
('21:30:00', 12),
('21:30:00', 13),
('21:30:00', 14),
('21:30:00', 15);

-- --------------------------------------------------------

--
-- Struttura della tabella `Partecipa`
--

CREATE TABLE `Partecipa` (
  `NumeroPosto` smallint(6) NOT NULL,
  `FilaPosto` char(1) NOT NULL,
  `NumeroSala` smallint(6) NOT NULL,
  `IDPrenotazione` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `Partecipa`
--

INSERT INTO `Partecipa` (`NumeroPosto`, `FilaPosto`, `NumeroSala`, `IDPrenotazione`) VALUES
(1, 'E', 1, 4),
(1, 'F', 1, 4),
(1, 'G', 1, 4),
(2, 'A', 1, 4),
(5, 'D', 1, 3),
(5, 'E', 1, 3),
(5, 'F', 1, 3),
(6, 'C', 2, 5),
(6, 'C', 2, 6),
(6, 'D', 1, 7),
(6, 'D', 1, 8),
(7, 'C', 1, 8),
(7, 'D', 1, 7),
(8, 'D', 1, 7),
(8, 'D', 1, 8),
(8, 'D', 2, 5),
(8, 'D', 2, 6),
(9, 'D', 1, 7),
(9, 'D', 1, 8),
(11, 'G', 1, 2),
(12, 'A', 1, 2),
(13, 'B', 2, 5),
(13, 'E', 2, 6),
(14, 'G', 3, 1),
(15, 'A', 3, 1),
(15, 'B', 3, 1),
(15, 'C', 3, 1),
(15, 'G', 2, 5),
(15, 'G', 2, 6);

-- --------------------------------------------------------

--
-- Struttura della tabella `Posto`
--

CREATE TABLE `Posto` (
  `Fila` char(1) NOT NULL,
  `Numero` smallint(6) NOT NULL,
  `NumeroSala` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `Posto`
--

INSERT INTO `Posto` (`Fila`, `Numero`, `NumeroSala`) VALUES
('A', 1, 1),
('A', 1, 2),
('A', 1, 3),
('B', 1, 1),
('B', 1, 2),
('B', 1, 3),
('C', 1, 1),
('C', 1, 2),
('C', 1, 3),
('D', 1, 1),
('D', 1, 2),
('D', 1, 3),
('E', 1, 1),
('E', 1, 2),
('E', 1, 3),
('F', 1, 1),
('F', 1, 2),
('F', 1, 3),
('G', 1, 1),
('G', 1, 2),
('G', 1, 3),
('A', 2, 1),
('A', 2, 2),
('A', 2, 3),
('B', 2, 1),
('B', 2, 2),
('B', 2, 3),
('C', 2, 1),
('C', 2, 2),
('C', 2, 3),
('D', 2, 1),
('D', 2, 2),
('D', 2, 3),
('E', 2, 1),
('E', 2, 2),
('E', 2, 3),
('F', 2, 1),
('F', 2, 2),
('F', 2, 3),
('G', 2, 1),
('G', 2, 2),
('G', 2, 3),
('A', 3, 1),
('A', 3, 2),
('A', 3, 3),
('B', 3, 1),
('B', 3, 2),
('B', 3, 3),
('C', 3, 1),
('C', 3, 2),
('C', 3, 3),
('D', 3, 1),
('D', 3, 2),
('D', 3, 3),
('E', 3, 1),
('E', 3, 2),
('E', 3, 3),
('F', 3, 1),
('F', 3, 2),
('F', 3, 3),
('G', 3, 1),
('G', 3, 2),
('G', 3, 3),
('A', 4, 1),
('A', 4, 2),
('A', 4, 3),
('B', 4, 1),
('B', 4, 2),
('B', 4, 3),
('C', 4, 1),
('C', 4, 2),
('C', 4, 3),
('D', 4, 1),
('D', 4, 2),
('D', 4, 3),
('E', 4, 1),
('E', 4, 2),
('E', 4, 3),
('F', 4, 1),
('F', 4, 2),
('F', 4, 3),
('G', 4, 1),
('G', 4, 2),
('G', 4, 3),
('A', 5, 1),
('A', 5, 2),
('A', 5, 3),
('B', 5, 1),
('B', 5, 2),
('B', 5, 3),
('C', 5, 1),
('C', 5, 2),
('C', 5, 3),
('D', 5, 1),
('D', 5, 2),
('D', 5, 3),
('E', 5, 1),
('E', 5, 2),
('E', 5, 3),
('F', 5, 1),
('F', 5, 2),
('F', 5, 3),
('G', 5, 1),
('G', 5, 2),
('G', 5, 3),
('A', 6, 1),
('A', 6, 2),
('A', 6, 3),
('B', 6, 1),
('B', 6, 2),
('B', 6, 3),
('C', 6, 1),
('C', 6, 2),
('C', 6, 3),
('D', 6, 1),
('D', 6, 2),
('D', 6, 3),
('E', 6, 1),
('E', 6, 2),
('E', 6, 3),
('F', 6, 1),
('F', 6, 2),
('F', 6, 3),
('G', 6, 1),
('G', 6, 2),
('G', 6, 3),
('A', 7, 1),
('A', 7, 2),
('A', 7, 3),
('B', 7, 1),
('B', 7, 2),
('B', 7, 3),
('C', 7, 1),
('C', 7, 2),
('C', 7, 3),
('D', 7, 1),
('D', 7, 2),
('D', 7, 3),
('E', 7, 1),
('E', 7, 2),
('E', 7, 3),
('F', 7, 1),
('F', 7, 2),
('F', 7, 3),
('G', 7, 1),
('G', 7, 2),
('G', 7, 3),
('A', 8, 1),
('A', 8, 2),
('A', 8, 3),
('B', 8, 1),
('B', 8, 2),
('B', 8, 3),
('C', 8, 1),
('C', 8, 2),
('C', 8, 3),
('D', 8, 1),
('D', 8, 2),
('D', 8, 3),
('E', 8, 1),
('E', 8, 2),
('E', 8, 3),
('F', 8, 1),
('F', 8, 2),
('F', 8, 3),
('G', 8, 1),
('G', 8, 2),
('G', 8, 3),
('A', 9, 1),
('A', 9, 2),
('A', 9, 3),
('B', 9, 1),
('B', 9, 2),
('B', 9, 3),
('C', 9, 1),
('C', 9, 2),
('C', 9, 3),
('D', 9, 1),
('D', 9, 2),
('D', 9, 3),
('E', 9, 1),
('E', 9, 2),
('E', 9, 3),
('F', 9, 1),
('F', 9, 2),
('F', 9, 3),
('G', 9, 1),
('G', 9, 2),
('G', 9, 3),
('A', 10, 1),
('A', 10, 2),
('A', 10, 3),
('B', 10, 1),
('B', 10, 2),
('B', 10, 3),
('C', 10, 1),
('C', 10, 2),
('C', 10, 3),
('D', 10, 1),
('D', 10, 2),
('D', 10, 3),
('E', 10, 1),
('E', 10, 2),
('E', 10, 3),
('F', 10, 1),
('F', 10, 2),
('F', 10, 3),
('G', 10, 1),
('G', 10, 2),
('G', 10, 3),
('A', 11, 1),
('A', 11, 2),
('A', 11, 3),
('B', 11, 1),
('B', 11, 2),
('B', 11, 3),
('C', 11, 1),
('C', 11, 2),
('C', 11, 3),
('D', 11, 1),
('D', 11, 2),
('D', 11, 3),
('E', 11, 1),
('E', 11, 2),
('E', 11, 3),
('F', 11, 1),
('F', 11, 2),
('F', 11, 3),
('G', 11, 1),
('G', 11, 2),
('G', 11, 3),
('A', 12, 1),
('A', 12, 2),
('A', 12, 3),
('B', 12, 1),
('B', 12, 2),
('B', 12, 3),
('C', 12, 1),
('C', 12, 2),
('C', 12, 3),
('D', 12, 1),
('D', 12, 2),
('D', 12, 3),
('E', 12, 1),
('E', 12, 2),
('E', 12, 3),
('F', 12, 1),
('F', 12, 2),
('F', 12, 3),
('G', 12, 1),
('G', 12, 2),
('G', 12, 3),
('A', 13, 1),
('A', 13, 2),
('A', 13, 3),
('B', 13, 1),
('B', 13, 2),
('B', 13, 3),
('C', 13, 1),
('C', 13, 2),
('C', 13, 3),
('D', 13, 1),
('D', 13, 2),
('D', 13, 3),
('E', 13, 1),
('E', 13, 2),
('E', 13, 3),
('F', 13, 1),
('F', 13, 2),
('F', 13, 3),
('G', 13, 1),
('G', 13, 2),
('G', 13, 3),
('A', 14, 1),
('A', 14, 2),
('A', 14, 3),
('B', 14, 1),
('B', 14, 2),
('B', 14, 3),
('C', 14, 1),
('C', 14, 2),
('C', 14, 3),
('D', 14, 1),
('D', 14, 2),
('D', 14, 3),
('E', 14, 1),
('E', 14, 2),
('E', 14, 3),
('F', 14, 1),
('F', 14, 2),
('F', 14, 3),
('G', 14, 1),
('G', 14, 2),
('G', 14, 3),
('A', 15, 1),
('A', 15, 2),
('A', 15, 3),
('B', 15, 1),
('B', 15, 2),
('B', 15, 3),
('C', 15, 1),
('C', 15, 2),
('C', 15, 3),
('D', 15, 1),
('D', 15, 2),
('D', 15, 3),
('E', 15, 1),
('E', 15, 2),
('E', 15, 3),
('F', 15, 1),
('F', 15, 2),
('F', 15, 3),
('G', 15, 1),
('G', 15, 2),
('G', 15, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `Prenotazione`
--

CREATE TABLE `Prenotazione` (
  `ID` int(11) NOT NULL,
  `NumeroPersone` smallint(6) NOT NULL,
  `UsernameUtente` varchar(50) DEFAULT NULL,
  `IDProiezione` int(11) NOT NULL,
  `OraProiezione` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `Prenotazione`
--

INSERT INTO `Prenotazione` (`ID`, `NumeroPersone`, `UsernameUtente`, `IDProiezione`, `OraProiezione`) VALUES
(1, 4, 'user', 2, '21:30:00'),
(2, 2, 'user', 1, '17:30:00'),
(3, 3, 'user', 3, '21:30:00'),
(4, 4, 'user', 3, '21:30:00'),
(5, 4, NULL, 10, '21:30:00'),
(6, 4, NULL, 10, '21:30:00'),
(7, 4, 'admin', 4, '16:00:00'),
(8, 4, 'admin', 4, '16:00:00');

-- --------------------------------------------------------

--
-- Struttura della tabella `Prezzi`
--

CREATE TABLE `Prezzi` (
  `Giorno` varchar(10) NOT NULL,
  `PrezzoIntero` decimal(4,2) NOT NULL,
  `PrezzoRidotto` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `Prezzi`
--

INSERT INTO `Prezzi` (`Giorno`, `PrezzoIntero`, `PrezzoRidotto`) VALUES
('Domenica', '10.00', '8.50'),
('Giovedì', '7.00', '5.50'),
('Lunedì', '7.00', '5.50'),
('Martedì', '7.00', '5.50'),
('Mercoledì', '5.00', '5.00'),
('Sabato', '10.00', '8.50'),
('Venerdì', '10.00', '8.50');

-- --------------------------------------------------------

--
-- Struttura della tabella `Proiezione`
--

CREATE TABLE `Proiezione` (
  `ID` int(11) NOT NULL,
  `Data` date NOT NULL,
  `IDFilm` int(11) NOT NULL,
  `NumeroSala` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `Proiezione`
--

INSERT INTO `Proiezione` (`ID`, `Data`, `IDFilm`, `NumeroSala`) VALUES
(8, '2022-01-04', 3, 2),
(3, '2022-01-28', 1, 1),
(15, '2022-02-01', 4, 1),
(1, '2022-02-15', 1, 1),
(14, '2022-03-15', 4, 2),
(12, '2022-03-28', 3, 3),
(4, '2022-05-04', 2, 1),
(6, '2022-06-10', 2, 2),
(13, '2022-06-13', 4, 2),
(10, '2022-07-14', 3, 2),
(7, '2022-08-19', 2, 1),
(11, '2022-10-11', 3, 2),
(5, '2022-10-28', 2, 3),
(2, '2022-11-11', 1, 3),
(9, '2022-11-20', 3, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `Sala`
--

CREATE TABLE `Sala` (
  `Numero` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `Sala`
--

INSERT INTO `Sala` (`Numero`) VALUES
(1),
(2),
(3);

-- --------------------------------------------------------

--
-- Struttura della tabella `Utente`
--

CREATE TABLE `Utente` (
  `Username` varchar(50) NOT NULL,
  `Nome` varchar(50) NOT NULL,
  `Cognome` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `Utente`
--

INSERT INTO `Utente` (`Username`, `Nome`, `Cognome`, `Password`, `Email`) VALUES
('admin', 'admin', 'admin', '$2y$10$oboMc3j0qLGtk7zLTIdMSuJi.ab4Hf2U7SZlEWdZ2gr3B/46fCH/u', 'admin@admin.it'),
('user', 'user', 'user', '$2y$10$cx/Uk.IywbxIZyJDzwGWFeBoCteUMqwL4kDVgnjz3gVvZhl/Hi29O', 'user@user.it');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `Afferisce`
--
ALTER TABLE `Afferisce`
  ADD PRIMARY KEY (`IDFilm`,`IDCast`),
  ADD KEY `IDCast` (`IDCast`);

--
-- Indici per le tabelle `Amministratori`
--
ALTER TABLE `Amministratori`
  ADD PRIMARY KEY (`Username`);

--
-- Indici per le tabelle `CastFilm`
--
ALTER TABLE `CastFilm`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Nome` (`Nome`,`Cognome`,`Ruolo`);

--
-- Indici per le tabelle `Film`
--
ALTER TABLE `Film`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `Orario`
--
ALTER TABLE `Orario`
  ADD PRIMARY KEY (`Ora`,`IDProiezione`),
  ADD KEY `IDProiezione` (`IDProiezione`);

--
-- Indici per le tabelle `Partecipa`
--
ALTER TABLE `Partecipa`
  ADD PRIMARY KEY (`NumeroPosto`,`FilaPosto`,`NumeroSala`,`IDPrenotazione`),
  ADD KEY `IDPrenotazione` (`IDPrenotazione`);

--
-- Indici per le tabelle `Posto`
--
ALTER TABLE `Posto`
  ADD PRIMARY KEY (`Numero`,`Fila`,`NumeroSala`),
  ADD KEY `NumeroSala` (`NumeroSala`);

--
-- Indici per le tabelle `Prenotazione`
--
ALTER TABLE `Prenotazione`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `UsernameUtente` (`UsernameUtente`),
  ADD KEY `IDProiezione` (`IDProiezione`,`OraProiezione`);

--
-- Indici per le tabelle `Prezzi`
--
ALTER TABLE `Prezzi`
  ADD PRIMARY KEY (`Giorno`);

--
-- Indici per le tabelle `Proiezione`
--
ALTER TABLE `Proiezione`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Data` (`Data`,`IDFilm`,`NumeroSala`),
  ADD KEY `IDFilm` (`IDFilm`),
  ADD KEY `NumeroSala` (`NumeroSala`);

--
-- Indici per le tabelle `Sala`
--
ALTER TABLE `Sala`
  ADD PRIMARY KEY (`Numero`);

--
-- Indici per le tabelle `Utente`
--
ALTER TABLE `Utente`
  ADD PRIMARY KEY (`Username`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `CastFilm`
--
ALTER TABLE `CastFilm`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Film`
--
ALTER TABLE `Film`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `Prenotazione`
--
ALTER TABLE `Prenotazione`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `Proiezione`
--
ALTER TABLE `Proiezione`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `Afferisce`
--
ALTER TABLE `Afferisce`
  ADD CONSTRAINT `Afferisce_ibfk_1` FOREIGN KEY (`IDFilm`) REFERENCES `Film` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `Afferisce_ibfk_2` FOREIGN KEY (`IDCast`) REFERENCES `CastFilm` (`ID`) ON DELETE CASCADE;

--
-- Limiti per la tabella `Orario`
--
ALTER TABLE `Orario`
  ADD CONSTRAINT `Orario_ibfk_1` FOREIGN KEY (`IDProiezione`) REFERENCES `Proiezione` (`ID`) ON DELETE CASCADE;

--
-- Limiti per la tabella `Partecipa`
--
ALTER TABLE `Partecipa`
  ADD CONSTRAINT `Partecipa_ibfk_1` FOREIGN KEY (`NumeroPosto`,`FilaPosto`,`NumeroSala`) REFERENCES `Posto` (`Numero`, `Fila`, `NumeroSala`) ON DELETE CASCADE,
  ADD CONSTRAINT `Partecipa_ibfk_2` FOREIGN KEY (`IDPrenotazione`) REFERENCES `Prenotazione` (`ID`) ON DELETE CASCADE;

--
-- Limiti per la tabella `Posto`
--
ALTER TABLE `Posto`
  ADD CONSTRAINT `Posto_ibfk_1` FOREIGN KEY (`NumeroSala`) REFERENCES `Sala` (`Numero`) ON DELETE CASCADE;

--
-- Limiti per la tabella `Prenotazione`
--
ALTER TABLE `Prenotazione`
  ADD CONSTRAINT `Prenotazione_ibfk_1` FOREIGN KEY (`UsernameUtente`) REFERENCES `Utente` (`Username`) ON DELETE CASCADE,
  ADD CONSTRAINT `Prenotazione_ibfk_2` FOREIGN KEY (`IDProiezione`,`OraProiezione`) REFERENCES `Orario` (`IDProiezione`, `Ora`) ON DELETE CASCADE;

--
-- Limiti per la tabella `Proiezione`
--
ALTER TABLE `Proiezione`
  ADD CONSTRAINT `Proiezione_ibfk_1` FOREIGN KEY (`IDFilm`) REFERENCES `Film` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `Proiezione_ibfk_2` FOREIGN KEY (`NumeroSala`) REFERENCES `Sala` (`Numero`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;