-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2023 at 08:25 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `proiect_pagina_web`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `User` varchar(50) NOT NULL,
  `Parola` varchar(255) NOT NULL,
  `ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`User`, `Parola`, `ID`) VALUES
('test2', '$2y$10$ITBB.DPhUhv768t6ydN0gOGrCpFRRHzDq6TSEF/r1GGpIRxvGTZJS', 9),
('Vlad', '$2y$10$4DtLTrA95DQ5jun9CMA8VuGOtNCHuKXuCBNR7SW0JNplluWtxz02.', 10);

-- --------------------------------------------------------

--
-- Table structure for table `evenimente`
--

CREATE TABLE `evenimente` (
  `ID` int(10) NOT NULL,
  `Titlu` varchar(150) NOT NULL,
  `Descriere` text NOT NULL,
  `Data` date NOT NULL,
  `Locatia` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `eveniment_parteneri`
--

CREATE TABLE `eveniment_parteneri` (
  `ID` int(10) NOT NULL,
  `Eveniment_ID` int(11) NOT NULL,
  `Parteneri_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `eveniment_speakeri`
--

CREATE TABLE `eveniment_speakeri` (
  `ID` int(10) NOT NULL,
  `Eveniment_ID` int(11) NOT NULL,
  `Speakeri_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `eveniment_sponsori`
--

CREATE TABLE `eveniment_sponsori` (
  `ID` int(11) NOT NULL,
  `Eveniment_ID` int(10) NOT NULL,
  `Sponsori_ID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inregistrare`
--

CREATE TABLE `inregistrare` (
  `ID` int(11) NOT NULL,
  `Nume` varchar(100) NOT NULL,
  `Prenume` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Telefon` varchar(15) NOT NULL,
  `ID_Eveniment` int(11) NOT NULL,
  `Detalii` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parteneri`
--

CREATE TABLE `parteneri` (
  `ID` int(11) NOT NULL,
  `Nume` varchar(150) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Adresa` text NOT NULL,
  `Telefon` varchar(15) NOT NULL,
  `Domeniu` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `speakeri`
--

CREATE TABLE `speakeri` (
  `ID` int(10) NOT NULL,
  `Nume` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Telefon` varchar(15) NOT NULL,
  `Adresa` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sponsori`
--

CREATE TABLE `sponsori` (
  `ID` int(10) NOT NULL,
  `Nume` varchar(100) NOT NULL,
  `Adresa` text NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Telefon` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `User` (`User`);

--
-- Indexes for table `evenimente`
--
ALTER TABLE `evenimente`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `eveniment_parteneri`
--
ALTER TABLE `eveniment_parteneri`
  ADD UNIQUE KEY `Eveniment_ID_Parteneri` (`Eveniment_ID`),
  ADD UNIQUE KEY `Eveniment_ID_Parteneri2` (`Parteneri_ID`);

--
-- Indexes for table `eveniment_speakeri`
--
ALTER TABLE `eveniment_speakeri`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Eveniment_ID_Speakeri` (`Eveniment_ID`),
  ADD UNIQUE KEY `Eveniment_ID_Speakeri2` (`Speakeri_ID`);

--
-- Indexes for table `eveniment_sponsori`
--
ALTER TABLE `eveniment_sponsori`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Eveniment_ID_Sponsori` (`Eveniment_ID`),
  ADD UNIQUE KEY `Eveniment_ID_Sponsori2` (`Sponsori_ID`);

--
-- Indexes for table `inregistrare`
--
ALTER TABLE `inregistrare`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `ID_Eveniment` (`ID_Eveniment`);

--
-- Indexes for table `parteneri`
--
ALTER TABLE `parteneri`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `speakeri`
--
ALTER TABLE `speakeri`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `sponsori`
--
ALTER TABLE `sponsori`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `evenimente`
--
ALTER TABLE `evenimente`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `eveniment_sponsori`
--
ALTER TABLE `eveniment_sponsori`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inregistrare`
--
ALTER TABLE `inregistrare`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parteneri`
--
ALTER TABLE `parteneri`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `speakeri`
--
ALTER TABLE `speakeri`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sponsori`
--
ALTER TABLE `sponsori`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `eveniment_parteneri`
--
ALTER TABLE `eveniment_parteneri`
  ADD CONSTRAINT `eveniment_parteneri_ibfk_1` FOREIGN KEY (`Parteneri_ID`) REFERENCES `parteneri` (`ID`),
  ADD CONSTRAINT `eveniment_parteneri_ibfk_2` FOREIGN KEY (`Eveniment_ID`) REFERENCES `evenimente` (`ID`);

--
-- Constraints for table `eveniment_speakeri`
--
ALTER TABLE `eveniment_speakeri`
  ADD CONSTRAINT `eveniment_speakeri_ibfk_1` FOREIGN KEY (`Speakeri_ID`) REFERENCES `speakeri` (`ID`),
  ADD CONSTRAINT `eveniment_speakeri_ibfk_2` FOREIGN KEY (`Eveniment_ID`) REFERENCES `evenimente` (`ID`);

--
-- Constraints for table `eveniment_sponsori`
--
ALTER TABLE `eveniment_sponsori`
  ADD CONSTRAINT `eveniment_sponsori_ibfk_1` FOREIGN KEY (`Eveniment_ID`) REFERENCES `evenimente` (`ID`),
  ADD CONSTRAINT `eveniment_sponsori_ibfk_2` FOREIGN KEY (`Sponsori_ID`) REFERENCES `sponsori` (`ID`);

--
-- Constraints for table `inregistrare`
--
ALTER TABLE `inregistrare`
  ADD CONSTRAINT `inregistrare_ibfk_1` FOREIGN KEY (`ID_Eveniment`) REFERENCES `evenimente` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
