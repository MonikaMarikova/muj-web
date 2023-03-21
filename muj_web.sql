-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Úte 21. bře 2023, 19:22
-- Verze serveru: 10.4.27-MariaDB
-- Verze PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `muj_web`
--
CREATE DATABASE IF NOT EXISTS `muj_web` DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci;
USE `muj_web`;

-- --------------------------------------------------------

--
-- Struktura tabulky `clanky`
--

CREATE TABLE IF NOT EXISTS `clanky` (
  `clanky_id` int(11) NOT NULL AUTO_INCREMENT,
  `titulek` varchar(255) DEFAULT NULL,
  `obsah` text DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `popisek` varchar(255) DEFAULT NULL,
  `klicova_slova` varchar(255) DEFAULT NULL,
  `datum_pridani` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`clanky_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `clanky`
--

INSERT INTO `clanky` (`clanky_id`, `titulek`, `obsah`, `url`, `popisek`, `klicova_slova`, `datum_pridani`) VALUES
(1, 'Vítejte na mém webu!', '<p>Jmenuji se Monika Mariková a je mi 29 let. Mám dvě dcerky ve věku 4 a 5 let.</p>\r\n<p>Během rodičovské dovolené jsem se začala zajímat o programování. Přihlásila jsem se na rekvalifikační kurz Programátor WWW aplikací v jazyce PHP, který jsem úspěšně absolvovala. Nyní hledám práci, kde bych svou novou zálibu mohla dále rozvíjet a hlavně využít v praxi.</p>\r\n<p>Kromě programování a času s dětmi ráda čtu, cestuji, vařím, tvořím a učím se nové věci.</p>\r\n', 'uvod', 'Úvodní článek webu', 'úvod, o mně, web', '2023-03-21'),
(5, 'Moje cesta k programování', '<p>Jak mě napadlo začít programovat? Když jsem končila základní školu, nevěděla jsem jakým směrem se vydat. Nakonec jsem se rozhodla pro Obchodní akademii a v roce 2013 jsem zdárně odmaturovala. Našla jsem si čtyřměsíční brigádu jako administrativní pracovnice. Po té bylo mé pracovní místo zrušeno - nahradil mě program:)</p>\r\n<p>V lednu 2014 jsem se vydala do Anglie, do městečka Grays nedaleko Londýna, kde jsem devět měsíců žila a pracovala jako aupair. Když jsem se vrátila domů, začala jsem vykonávat dělnickou profesi. V prosinci 2017 se narodila první dcera a 16 měsíců na to druhá. Během mateřské jsem začala uvažovat co budu dělat dál, jaká práce by se dala do budoucna skloubit s péčí o rodinu? Zároveň bych ráda dělala něco tvořivého, něco u čeho je třeba přemýšlet. A začala jsem uvažovat o rekvalifikačním kurzu na programátorku webových aplikací. Než jsem se do kurzu přihlásila, zkusila jsem si projít online kurz Základní konstrukce jazyka Java na ITnetwork.cz. Po té jsem se přihlásila na čtyřměsíční rekvalifikační kurz Programátor WWW aplikací v jazyce PHP, který jsem v prosinci 2022 úspěšně dokončila.</p>\r\n<p>Jsem teprve na začátku cesty k programování. Je toho tolik co se musím naučit, ale naučím se to ráda.</p>\r\n<p>V tuto chvíli hledám svou první práci v IT. Zatím mám za sebou pár odmítnutí, která mi však pomohla si ujasnit na co se zaměřit. Snažila jsem se toho totiž naučit moc naráz, ale došlo mi, že bude mnohem lepší se zdokonalovat v tom co již umím a hlavně programovat, programovat, programovat.</p>\r\n<p>Mám rozpracovanou aplikaci na evidenci knih ve své domácí knihovničce a zároveň takový můj čtenářský deník. Spojení mých zálib - čtení a programování. A to je to, co se mi na programování také líbí - že ho můžu spojit i s jinými zálibami.</p>', 'moje-cesta-k-programovani', 'Co mě láká na programování?', 'programovani', '2023-03-21');

-- --------------------------------------------------------

--
-- Struktura tabulky `uzivatele`
--

CREATE TABLE IF NOT EXISTS `uzivatele` (
  `uzivatele_id` int(11) NOT NULL AUTO_INCREMENT,
  `jmeno` varchar(255) DEFAULT NULL,
  `heslo` varchar(255) DEFAULT NULL,
  `admin` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`uzivatele_id`),
  UNIQUE KEY `jmeno` (`jmeno`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `uzivatele`
--

INSERT INTO `uzivatele` (`uzivatele_id`, `jmeno`, `heslo`, `admin`) VALUES
(1, 'admin', '$2y$10$wOntFMGrLrr/F6vJT5/EQeE8B.jotE8P.aVlcv5i6hhhzObWZjk4u', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
