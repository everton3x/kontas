-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 06-Jan-2022 às 14:57
-- Versão do servidor: 10.4.21-MariaDB
-- versão do PHP: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `kontasdb`
--
CREATE DATABASE IF NOT EXISTS `kontasdb` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `kontasdb`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `despesaalteracao`
--

CREATE TABLE IF NOT EXISTS `despesaalteracao` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `despesa` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `observacao` varchar(255) DEFAULT NULL,
  `registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `despesaalteracao`
--

INSERT INTO `despesaalteracao` (`cod`, `despesa`, `valor`, `observacao`, `registro`) VALUES
(4, 12, '100.00', 'Suplementação automática', '2022-01-06 13:47:13'),
(5, 12, '200.00', 'Suplementação automática', '2022-01-06 13:49:56');

-- --------------------------------------------------------

--
-- Estrutura da tabela `despesas`
--

CREATE TABLE IF NOT EXISTS `despesas` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `periodo` int(11) NOT NULL,
  `descricao` text NOT NULL,
  `valorInicial` decimal(10,2) NOT NULL,
  `agrupador` varchar(255) DEFAULT NULL,
  `parcela` int(11) DEFAULT NULL,
  `registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `despesas`
--

INSERT INTO `despesas` (`cod`, `periodo`, `descricao`, `valorInicial`, `agrupador`, `parcela`, `registro`) VALUES
(12, 202201, 'teste', '1000.00', '', 0, '2022-01-06 13:42:52');

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `despesasresumo`
-- (Veja abaixo para a view atual)
--
CREATE TABLE IF NOT EXISTS `despesasresumo` (
`cod` int(11)
,`periodo` int(11)
,`descricao` text
,`valorInicial` decimal(10,2)
,`agrupador` varchar(255)
,`parcela` int(11)
,`registro` timestamp
,`alteracao` decimal(32,2)
,`gasto` decimal(32,2)
,`pago` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Estrutura da tabela `gastos`
--

CREATE TABLE IF NOT EXISTS `gastos` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `despesa` int(11) NOT NULL,
  `data` date NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `mp` int(11) NOT NULL,
  `vencimento` date DEFAULT NULL,
  `observacao` varchar(255) DEFAULT NULL,
  `registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `gastos`
--

INSERT INTO `gastos` (`cod`, `despesa`, `data`, `valor`, `mp`, `vencimento`, `observacao`, `registro`) VALUES
(13, 12, '2022-01-06', '1100.00', 1, '0000-00-00', '', '2022-01-06 13:47:13'),
(14, 12, '2022-01-06', '200.00', 1, '0000-00-00', '', '2022-01-06 13:50:16');

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `listatags`
-- (Veja abaixo para a view atual)
--
CREATE TABLE IF NOT EXISTS `listatags` (
`tag` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura da tabela `mp`
--

CREATE TABLE IF NOT EXISTS `mp` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `mp` varchar(255) NOT NULL,
  `autopagar` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`cod`),
  UNIQUE KEY `mp` (`mp`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `mp`
--

INSERT INTO `mp` (`cod`, `mp`, `autopagar`, `status`, `registro`) VALUES
(1, 'Dinheiro', 1, 0, '2022-01-05 16:51:34'),
(2, 'Débito C/C', 1, 0, '2022-01-05 16:53:36'),
(3, 'Americanas/Crédito', 0, 0, '2022-01-05 16:54:00'),
(4, 'Banricompras', 0, 0, '2022-01-05 16:54:10'),
(5, 'Inter/Crédito', 0, 0, '2022-01-05 16:54:21'),
(6, 'Agendamento/Débito em C/C', 0, 0, '2022-01-05 16:54:40');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pagamentos`
--

CREATE TABLE IF NOT EXISTS `pagamentos` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `gasto` int(11) NOT NULL,
  `data` date NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `observacao` varchar(255) DEFAULT NULL,
  `registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `pagamentos`
--

INSERT INTO `pagamentos` (`cod`, `gasto`, `data`, `valor`, `observacao`, `registro`) VALUES
(3, 14, '2022-01-06', '200.00', 'Pagamento automático.', '2022-01-06 13:50:16');

-- --------------------------------------------------------

--
-- Estrutura da tabela `recebimentos`
--

CREATE TABLE IF NOT EXISTS `recebimentos` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `receita` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data` date NOT NULL,
  `observacao` varchar(255) DEFAULT NULL,
  `registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `recebimentos`
--

INSERT INTO `recebimentos` (`cod`, `receita`, `valor`, `data`, `observacao`, `registro`) VALUES
(1, 1, '4563464.00', '2021-12-30', 'Recebimento automático.', '2021-12-30 17:56:57');

-- --------------------------------------------------------

--
-- Estrutura da tabela `receitaalteracao`
--

CREATE TABLE IF NOT EXISTS `receitaalteracao` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `receita` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `observacao` varchar(255) DEFAULT NULL,
  `registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `receitas`
--

CREATE TABLE IF NOT EXISTS `receitas` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `periodo` int(11) NOT NULL,
  `descricao` text NOT NULL,
  `valorInicial` decimal(10,2) NOT NULL,
  `agrupador` varchar(255) DEFAULT NULL,
  `parcela` int(11) DEFAULT NULL,
  `registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `receitas`
--

INSERT INTO `receitas` (`cod`, `periodo`, `descricao`, `valorInicial`, `agrupador`, `parcela`, `registro`) VALUES
(1, 202112, 'FAFAFASFAS', '4563464.00', '', 0, '2021-12-30 17:56:57'),
(2, 202112, 'teste de tag', '1000.00', '', 0, '2021-12-30 18:03:32');

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `receitasresumo`
-- (Veja abaixo para a view atual)
--
CREATE TABLE IF NOT EXISTS `receitasresumo` (
`cod` int(11)
,`periodo` int(11)
,`descricao` text
,`valorInicial` decimal(10,2)
,`alteracao` decimal(32,2)
,`recebido` decimal(32,2)
,`agrupador` varchar(255)
,`parcela` int(11)
,`parcelas` bigint(21)
);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) NOT NULL,
  `receita` int(11) DEFAULT NULL,
  `despesa` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tags`
--

INSERT INTO `tags` (`cod`, `tag`, `receita`, `despesa`) VALUES
(1, 'Tag 1', 2, NULL),
(2, 'Tag 2', 2, NULL),
(3, 'Tag 1', NULL, '1'),
(4, 'Tag 3', NULL, '1');

-- --------------------------------------------------------

--
-- Estrutura para vista `despesasresumo`
--
DROP TABLE IF EXISTS `despesasresumo`;

CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `despesasresumo`  AS SELECT `despesas`.`cod` AS `cod`, `despesas`.`periodo` AS `periodo`, `despesas`.`descricao` AS `descricao`, `despesas`.`valorInicial` AS `valorInicial`, `despesas`.`agrupador` AS `agrupador`, `despesas`.`parcela` AS `parcela`, `despesas`.`registro` AS `registro`, (select coalesce(sum(`despesaalteracao`.`valor`),0) from `despesaalteracao` where `despesaalteracao`.`despesa` = `despesas`.`cod`) AS `alteracao`, (select coalesce(sum(`gastos`.`valor`),0) from `gastos` where `gastos`.`despesa` = `despesas`.`cod`) AS `gasto`, (select coalesce(sum(`pagamentos`.`valor`),0) from ((`pagamentos` join `gastos`) join `despesas`) where `pagamentos`.`gasto` = `gastos`.`cod` and `gastos`.`despesa` = `despesas`.`cod`) AS `pago` FROM `despesas` ;

-- --------------------------------------------------------

--
-- Estrutura para vista `listatags`
--
DROP TABLE IF EXISTS `listatags`;

CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `listatags`  AS SELECT DISTINCT `tags`.`tag` AS `tag` FROM `tags` ORDER BY `tags`.`tag` ASC ;

-- --------------------------------------------------------

--
-- Estrutura para vista `receitasresumo`
--
DROP TABLE IF EXISTS `receitasresumo`;

CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `receitasresumo`  AS SELECT `receitas`.`cod` AS `cod`, `receitas`.`periodo` AS `periodo`, `receitas`.`descricao` AS `descricao`, `receitas`.`valorInicial` AS `valorInicial`, (select coalesce(sum(`receitaalteracao`.`valor`),0) from `receitaalteracao` where `receitaalteracao`.`receita` = `receitas`.`cod`) AS `alteracao`, (select coalesce(sum(`recebimentos`.`valor`),0) from `recebimentos` where `recebimentos`.`receita` = `receitas`.`cod`) AS `recebido`, `receitas`.`agrupador` AS `agrupador`, `receitas`.`parcela` AS `parcela`, (select count(`receitas`.`agrupador`) from `receitas` where `receitas`.`agrupador` like `receitas`.`agrupador` and `receitas`.`agrupador`  not like '') AS `parcelas` FROM `receitas` ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
