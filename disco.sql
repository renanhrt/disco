-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 11/09/2024 às 03:19
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `disco`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `artists`
--

CREATE TABLE `artists` (
  `idArtist` int(11) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `artists`
--

INSERT INTO `artists` (`idArtist`, `name`) VALUES
(1, 'Xitãozinho & Xororó'),
(3, 'Fat Rat'),
(4, 'Kendrick Lambari'),
(6, 'Dire Straits'),
(8, 'Matuê'),
(9, 'Jonas'),
(10, 'Lucas Lucco'),
(12, 'The Beatles');

-- --------------------------------------------------------

--
-- Estrutura para tabela `loans`
--

CREATE TABLE `loans` (
  `idLoan` int(11) NOT NULL,
  `idRecord` int(11) NOT NULL,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `date` date NOT NULL,
  `status` text NOT NULL DEFAULT 'Open',
  `returnDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `loans`
--

INSERT INTO `loans` (`idLoan`, `idRecord`, `name`, `email`, `date`, `status`, `returnDate`) VALUES
(6, 12, 'Vini', 'vini@gmail.com', '2024-09-09', 'Returned', '2024-09-10'),
(7, 10, 'Tulio', 'tulio@gmail.com', '2024-09-10', 'Open', NULL),
(8, 14, 'Tupi', 'tupi@gmail.com', '2024-09-10', 'Open', NULL),
(9, 15, 'Paul', 'paul@gmail.com', '2024-09-10', 'Open', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `records`
--

CREATE TABLE `records` (
  `idRecord` int(11) NOT NULL,
  `title` text NOT NULL,
  `year` int(4) NOT NULL,
  `cover` text NOT NULL,
  `idArtist` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `records`
--

INSERT INTO `records` (`idRecord`, `title`, `year`, `cover`, `idArtist`) VALUES
(10, 'Lokkup', 2017, 'images/Captura de Tela (11).png', 1),
(11, 'Qweerty', 2003, 'images/Captura de tela 2023-08-28 210935.png', 6),
(12, 'Success', 1871, 'images/Captura de tela 2023-11-21 201029.png', 4),
(14, 'Loop', 2004, 'images/Captura de tela 2023-12-24 133740.png', 6),
(15, 'yeahg', 2001, 'images/Captura de tela 2023-09-05 103535.png', 8);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `artists`
--
ALTER TABLE `artists`
  ADD PRIMARY KEY (`idArtist`);

--
-- Índices de tabela `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`idLoan`),
  ADD KEY `idRecord` (`idRecord`);

--
-- Índices de tabela `records`
--
ALTER TABLE `records`
  ADD PRIMARY KEY (`idRecord`),
  ADD KEY `idArtist` (`idArtist`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `artists`
--
ALTER TABLE `artists`
  MODIFY `idArtist` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `loans`
--
ALTER TABLE `loans`
  MODIFY `idLoan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `records`
--
ALTER TABLE `records`
  MODIFY `idRecord` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `idRecord` FOREIGN KEY (`idRecord`) REFERENCES `records` (`idRecord`) ON DELETE CASCADE;

--
-- Restrições para tabelas `records`
--
ALTER TABLE `records`
  ADD CONSTRAINT `idArtist` FOREIGN KEY (`idArtist`) REFERENCES `artists` (`idArtist`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
