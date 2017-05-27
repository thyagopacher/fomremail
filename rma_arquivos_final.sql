-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 27-Maio-2017 às 20:53
-- Versão do servidor: 10.1.10-MariaDB
-- PHP Version: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `formemail`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `rma_arquivos_final`
--

CREATE TABLE `rma_arquivos_final` (
  `id` int(11) NOT NULL,
  `id_rma` int(11) NOT NULL,
  `arquivo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `rma_arquivos_final`
--

INSERT INTO `rma_arquivos_final` (`id`, `id_rma`, `arquivo`) VALUES
(1, 16, 'http://formemail.dev/arquivos/garantia_idrma16-0-data20170425144826.jpg'),
(2, 16, 'http://formemail.dev/arquivos/garantia_idrma16-1-data20170425144826.jpg'),
(3, 16, 'http://formemail.dev/arquivos/garantia_idrma16-2-data20170425144827.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rma_arquivos_final`
--
ALTER TABLE `rma_arquivos_final`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rma_arquivos_final`
--
ALTER TABLE `rma_arquivos_final`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
