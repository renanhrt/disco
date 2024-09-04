-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04/09/2024 às 02:38
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
(1, 'Xitãozinho'),
(3, 'Fat Rat'),
(4, 'Kendrick Lambari'),
(5, 'The Beatles'),
(6, 'Dire Straits');

-- --------------------------------------------------------

--
-- Estrutura para tabela `loans`
--

CREATE TABLE `loans` (
  `idLoan` int(11) NOT NULL,
  `idRecord` int(11) NOT NULL,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'Solo', 2003, 'images/RobloxScreenShot20240229_151401627.png', 6),
(4, 'Sultans Of Swing', 1998, 'images/Captura de Tela (2).png', 6),
(5, 'Forsure', 2001, 'images/Captura de Tela (19).png', 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `returns`
--

CREATE TABLE `returns` (
  `idReturn` int(11) NOT NULL,
  `idLoan` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Índices de tabela `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`idReturn`),
  ADD KEY `idLoan` (`idLoan`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `artists`
--
ALTER TABLE `artists`
  MODIFY `idArtist` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `loans`
--
ALTER TABLE `loans`
  MODIFY `idLoan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `records`
--
ALTER TABLE `records`
  MODIFY `idRecord` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `returns`
--
ALTER TABLE `returns`
  MODIFY `idReturn` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `idRecord` FOREIGN KEY (`idRecord`) REFERENCES `records` (`idRecord`);

--
-- Restrições para tabelas `records`
--
ALTER TABLE `records`
  ADD CONSTRAINT `idArtist` FOREIGN KEY (`idArtist`) REFERENCES `artists` (`idArtist`);

--
-- Restrições para tabelas `returns`
--
ALTER TABLE `returns`
  ADD CONSTRAINT `idLoan` FOREIGN KEY (`idLoan`) REFERENCES `loans` (`idLoan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
