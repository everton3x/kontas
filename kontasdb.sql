-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 27-Dez-2021 às 13:41
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
-- Estrutura da tabela `lancamentos`
--
-- Criação: 15-Dez-2021 às 19:38
--

DROP TABLE IF EXISTS `lancamentos`;
CREATE TABLE IF NOT EXISTS `lancamentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transacao` varchar(255) NOT NULL,
  `contaContabil` text NOT NULL,
  `movimento` text NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `registro` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- RELACIONAMENTOS PARA TABELAS `lancamentos`:
--

--
-- Extraindo dados da tabela `lancamentos`
--

INSERT INTO `lancamentos` (`id`, `transacao`, `contaContabil`, `movimento`, `valor`, `registro`) VALUES
(1, 'f7df4eadac1633da2230cc43f1b956c231da4bb1', '111020201', 'credito', '1000.00', '2021-12-15 17:10:53'),
(2, 'f7df4eadac1633da2230cc43f1b956c231da4bb1', '111010100', 'debito', '1000.00', '2021-12-15 17:10:53'),
(3, '9df9289bb9d05dd6eb91f8a3783bfe4e2af099b9', '111040300', 'credito', '500.99', '2021-12-15 17:25:07'),
(4, '9df9289bb9d05dd6eb91f8a3783bfe4e2af099b9', '111040300', 'debito', '500.99', '2021-12-15 17:25:07');

-- --------------------------------------------------------

--
-- Estrutura da tabela `planodecontas`
--
-- Criação: 15-Dez-2021 às 13:53
--

DROP TABLE IF EXISTS `planodecontas`;
CREATE TABLE IF NOT EXISTS `planodecontas` (
  `codigo` varchar(12) NOT NULL,
  `tipoNivel` varchar(1) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `debitaQuando` text DEFAULT NULL,
  `creditaQuando` text DEFAULT NULL,
  `naturezaSaldo` varchar(2) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `registro` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELACIONAMENTOS PARA TABELAS `planodecontas`:
--

--
-- Extraindo dados da tabela `planodecontas`
--

INSERT INTO `planodecontas` (`codigo`, `tipoNivel`, `nome`, `descricao`, `debitaQuando`, `creditaQuando`, `naturezaSaldo`, `status`, `registro`) VALUES
('100000000', 'S', 'Ativo', '', '', '', '', 0, '0000-00-00 00:00:00'),
('110000000', 'S', 'Ativo Circulante', '', '', '', '', 0, '0000-00-00 00:00:00'),
('111000000', 'S', 'Disponibilidades', '', '', '', '', 0, '0000-00-00 00:00:00'),
('111010000', 'S', 'Disponibilidades em Espécie', '', '', '', '', 0, '0000-00-00 00:00:00'),
('111010100', 'A', 'Caixa', '', '', '', '', 0, '0000-00-00 00:00:00'),
('111020000', 'S', 'Depósitos Bancários', '', '', '', '', 0, '0000-00-00 00:00:00'),
('111020100', 'S', 'CEF', '', '', '', 'DC', 0, '0000-00-00 00:00:00'),
('111020101', 'A', 'Ag. 0521 Op. 001 C/C 20.280-1', '', '', '', '', 0, '0000-00-00 00:00:00'),
('111020200', 'S', 'Banrisul', '', '', '', '', 0, '0000-00-00 00:00:00'),
('111020201', 'A', 'Ag. 000 C/C 00000000', '', '', '', '', 0, '0000-00-00 00:00:00'),
('111030000', 'S', 'Depósitos em Poupança', '', '', '', '', 0, '0000-00-00 00:00:00'),
('111030100', 'S', 'CEF', '', '', '', 'D', 0, '0000-00-00 00:00:00'),
('111030101', 'S', 'Ag. 0521 Op. 000 C/C 00000', '', '', '', '', 0, '0000-00-00 00:00:00'),
('111040000', 'S', 'Vales, cupons, cashback e assemelhados', '', '', '', '', 0, '0000-00-00 00:00:00'),
('111040100', 'A', 'Mais sorrisos Americanas', '', '', '', '', 0, '0000-00-00 00:00:00'),
('111040200', 'A', 'Cashback Ame', '', '', '', '', 0, '0000-00-00 00:00:00'),
('111040300', 'A', 'Cashback Santa Teresinha', '', '', '', '', 0, '0000-00-00 00:00:00'),
('111990000', 'S', 'Outras Disponibilidades', '', '', '', '', 0, '0000-00-00 00:00:00'),
('111990100', 'A', 'Vales-compras', '', '', '', '', 0, '0000-00-00 00:00:00'),
('112000000', 'S', 'Créditos a Receber', '', '', '', '', 0, '0000-00-00 00:00:00'),
('112010000', 'S', 'Remuneração a Receber', '', '', '', '', 0, '0000-00-00 00:00:00'),
('112010100', 'A', 'Salários a Receber', '', '', '', '', 0, '0000-00-00 00:00:00'),
('112010200', 'A', '13º Salário a Receber', '', '', '', '', 0, '0000-00-00 00:00:00'),
('112010300', 'A', '1/3 de Férias a Receber', '', '', '', '', 0, '0000-00-00 00:00:00'),
('112020000', 'S', 'Indenizações e Restituições a Receber', '', '', '', '', 0, '0000-00-00 00:00:00'),
('112990000', 'S', 'Outros Créditos a Receber', '', '', '', '', 0, '0000-00-00 00:00:00'),
('120000000', 'S', 'Ativo Não Circulante', '', '', '', '', 0, '0000-00-00 00:00:00'),
('121000000', 'S', 'Investimentos a Longo Prazo', '', '', '', '', 0, '0000-00-00 00:00:00'),
('122000000', 'S', 'Participação em Negócios', '', '', '', '', 0, '0000-00-00 00:00:00'),
('123000000', 'S', 'Imobilizado', '', '', '', '', 0, '0000-00-00 00:00:00'),
('123010000', 'S', 'Bens Móveis', '', '', '', '', 0, '0000-00-00 00:00:00'),
('123010100', 'A', 'Móveis, Eletrodomésticos e Eletrônicos', '', '', '', '', 0, '0000-00-00 00:00:00'),
('123010200', 'A', 'Ferramentas e Utensílios', '', '', '', '', 0, '0000-00-00 00:00:00'),
('123010300', 'S', 'Veículos', '', '', '', '', 0, '0000-00-00 00:00:00'),
('123010301', 'A', 'HB20S 2014 MLT7A35', '', '', '', '', 0, '0000-00-00 00:00:00'),
('123010302', 'A', 'Yamaha Factor 125 K1 2014 IVD8I61', '', '', '', '', 0, '0000-00-00 00:00:00'),
('123019900', 'A', 'Outros bens móveis', '', '', '', '', 0, '0000-00-00 00:00:00'),
('123020000', 'S', 'Bens imóveis', '', '', '', '', 0, '0000-00-00 00:00:00'),
('123020100', 'S', 'Terrenos', '', '', '', '', 0, '0000-00-00 00:00:00'),
('123020101', 'A', 'Rua Ello Redel nº 55, Três de Maio', '', '', '', '', 0, '0000-00-00 00:00:00'),
('123020200', 'S', 'Edifícios', '', '', '', '', 0, '0000-00-00 00:00:00'),
('123020201', 'A', 'Casa Rua Ello Redel nº 55, Três de Maio', '', '', '', '', 0, '0000-00-00 00:00:00'),
('123020300', 'S', 'Glebas rurais', '', '', '', '', 0, '0000-00-00 00:00:00'),
('123029900', 'S', 'Outros bens imóveis', '', '', '', '', 0, '0000-00-00 00:00:00'),
('200000000', 'S', 'Passivo e Patrimônio Líquido', '', '', '', '', 0, '0000-00-00 00:00:00'),
('210000000', 'S', 'Passivo Circulante', '', '', '', '', 0, '0000-00-00 00:00:00'),
('211000000', 'S', 'Pessoal e Encargos a Pagar', '', '', '', '', 0, '0000-00-00 00:00:00'),
('211010000', 'S', 'Pessoal a Pagar', '', '', '', '', 0, '0000-00-00 00:00:00'),
('211020000', 'S', 'Encargos Sociais a Pagar', '', '', '', '', 0, '0000-00-00 00:00:00'),
('211020100', 'A', 'Contribuição ao RGPS a Pagar', '', '', '', '', 0, '0000-00-00 00:00:00'),
('212000000', 'S', 'Contas a Pagar', '', '', '', '', 0, '0000-00-00 00:00:00'),
('212010000', 'A', 'Fornecedores a Pagar', '', '', '', '', 0, '0000-00-00 00:00:00'),
('212020000', 'S', 'Faturas a Pagar', '', '', '', '', 0, '0000-00-00 00:00:00'),
('212020100', 'A', 'Cartão Americanas a Pagar', '', '', '', '', 0, '0000-00-00 00:00:00'),
('212020200', 'A', 'Cartão Inter a Pagar', '', '', '', '', 0, '0000-00-00 00:00:00'),
('212020300', 'A', 'Banricompras a Pagar', '', '', '', '', 0, '0000-00-00 00:00:00'),
('212990000', 'S', 'Outros Valores a Pagar', '', '', '', '', 0, '0000-00-00 00:00:00'),
('220000000', 'S', 'Passivo Não Circulante', '', '', '', '', 0, '0000-00-00 00:00:00'),
('221000000', 'S', 'Empréstimos e Financiamentos de Longo Prazo', '', '', '', '', 0, '0000-00-00 00:00:00'),
('221010000', 'S', 'Financiamento Habitacional', '', '', '', '', 0, '0000-00-00 00:00:00'),
('221010100', 'A', 'CEF contrato nº Rua Ello Redel, 55, Três de Maio', '', '', '', '', 0, '0000-00-00 00:00:00'),
('230000000', 'S', 'Patrimônio Líquido', '', '', '', '', 0, '0000-00-00 00:00:00'),
('231000000', 'S', 'Ajustes', '', '', '', '', 0, '0000-00-00 00:00:00'),
('231010000', 'S', 'Ajustes por conciliação', '', '', '', '', 0, '0000-00-00 00:00:00'),
('232000000', 'S', 'Resultados', '', '', '', '', 0, '0000-00-00 00:00:00'),
('232010000', 'A', 'Resultados Acumulados', '', '', '', '', 0, '0000-00-00 00:00:00'),
('232020000', 'A', 'Resultados do Período', '', '', '', '', 0, '0000-00-00 00:00:00'),
('300000000', 'S', 'Variação Patrimonial Diminutiva', '', '', '', '', 0, '0000-00-00 00:00:00'),
('310000000', 'S', 'Pessoal e Encargos', '', '', '', '', 0, '0000-00-00 00:00:00'),
('311000000', 'S', 'Remuneração de Pessoal', '', '', '', '', 0, '0000-00-00 00:00:00'),
('311010000', 'A', 'Salários', '', '', '', '', 0, '0000-00-00 00:00:00'),
('311990000', 'S', 'Outras Remunerações', '', '', '', '', 0, '0000-00-00 00:00:00'),
('312000000', 'S', 'Encargos Sociais', '', '', '', '', 0, '0000-00-00 00:00:00'),
('312010000', 'A', 'Contribuição ao RGPS', '', '', '', '', 0, '0000-00-00 00:00:00'),
('312990000', 'S', 'Outros Encargos Previdenciários', '', '', '', '', 0, '0000-00-00 00:00:00'),
('330000000', 'S', 'Consumo de Materiais e Serviços', '', '', '', '', 0, '0000-00-00 00:00:00'),
('331000000', 'S', 'Consumo de Materiais', '', '', '', '', 0, '0000-00-00 00:00:00'),
('331010000', 'A', 'Combustíveis', '', '', '', '', 0, '0000-00-00 00:00:00'),
('331020000', 'A', 'Gás GLP', '', '', '', '', 0, '0000-00-00 00:00:00'),
('331030000', 'A', 'Produtos de Mercado, Mercearia e Semelhantes', '', '', '', '', 0, '0000-00-00 00:00:00'),
('331040000', 'A', 'Produtos Farmacêuticos e Hospitalares', '', '', '', '', 0, '0000-00-00 00:00:00'),
('331050000', 'A', 'Material Educativo', '', '', '', '', 0, '0000-00-00 00:00:00'),
('331060000', 'A', 'Material Esportivo', '', '', '', '', 0, '0000-00-00 00:00:00'),
('331070000', 'A', 'Material para Festividades e Presentes', '', '', '', '', 0, '0000-00-00 00:00:00'),
('331080000', 'A', 'Vestuário', '', '', '', '', 0, '0000-00-00 00:00:00'),
('331090000', 'S', 'Material de Manutenção e Conservação', '', '', '', '', 0, '0000-00-00 00:00:00'),
('331090100', 'A', 'Material de Manutenção de Veículos', '', '', '', '', 0, '0000-00-00 00:00:00'),
('331090200', 'A', 'Material de Manutenção de Móveis', '', '', '', '', 0, '0000-00-00 00:00:00'),
('331110000', 'A', 'Material de Manutenção de Imóveis', '', '', '', '', 0, '0000-00-00 00:00:00'),
('331120000', 'A', 'Ferramentas e Utensílios', '', '', '', '', 0, '0000-00-00 00:00:00'),
('331130000', 'A', 'Bens Móveis Não Ativáveis', '', '', '', '', 0, '0000-00-00 00:00:00'),
('331990000', 'S', 'Outros Materiais', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332000000', 'S', 'Serviços', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332010000', 'S', 'Comunicação', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332010100', 'A', 'Telefonia', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332010200', 'A', 'Internet', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332010300', 'A', 'Postal', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332019900', 'S', 'Outros serviços de Comunicação', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332020000', 'S', 'Manutenção e Conservação', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332020100', 'A', 'Manutenção de Veículos', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332020200', 'A', 'Manutenção de Bens Móveis', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332020300', 'A', 'Manutenção de Imóveis', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332029900', 'S', 'Outros Serviços de Manutenção', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332030000', 'A', 'Água e Esgoto', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332040000', 'A', 'Energia Elétrica', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332050000', 'S', 'Locação', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332060000', 'A', 'Fretes e Encomendas', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332070000', 'A', 'Assinaturas e Anuidades', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332080000', 'A', 'Hospedagens', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332090000', 'A', 'Comissões e Corretagens', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332100000', 'A', 'Festividades e Homenagens', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332110000', 'S', 'Seguros', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332110100', 'A', 'Seguros Veiculares', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332110200', 'A', 'Seguros Residenciais', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332120000', 'A', 'Serviços de Educação e Treinamento', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332130000', 'S', 'Serviços de Saúde', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332130100', 'A', 'Consultas Médicas', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332130200', 'A', 'Consultas Odontológicas', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332130300', 'A', 'Consultas Profissionais (exceto médicos e dentistas)', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332130400', 'A', 'Exames Laboratoriais, de Imagens e assemelhados', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332130500', 'A', 'Mensalidades de Planos de Saúde', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332130600', 'A', 'Despesas Hospitalares', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332139900', 'S', 'Outros Serviços Médicos', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332140000', 'A', 'Serviços e Tarifas Bancárias', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332150000', 'A', 'Serviços de Cópias e Reproduções de Documentos', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332160000', 'A', 'Serviços Técnico-Profissionais', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332170000', 'A', 'Passagens e Despesas com Locomoção', '', '', '', '', 0, '0000-00-00 00:00:00'),
('332990000', 'S', 'Outros Serviços', '', '', '', '', 0, '0000-00-00 00:00:00'),
('340000000', 'S', 'VPD Financeiras', '', '', '', '', 0, '0000-00-00 00:00:00'),
('341000000', 'A', 'Juros e Encargos', '', '', '', '', 0, '0000-00-00 00:00:00'),
('341010000', 'A', 'Juros e Encargos sobre Empréstimos e Financiamentos', '', '', '', '', 0, '0000-00-00 00:00:00'),
('342000000', 'S', 'Encargos de Mora', '', '', '', '', 0, '0000-00-00 00:00:00'),
('342010000', 'A', 'Juros de Mora', '', '', '', '', 0, '0000-00-00 00:00:00'),
('342020000', 'A', 'Multa de Mora', '', '', '', '', 0, '0000-00-00 00:00:00'),
('350000000', 'S', 'Desvalorização e Desimcorporação de Ativos e Incorporação de Passivos', '', '', '', '', 0, '0000-00-00 00:00:00'),
('351000000', 'S', 'Desvalorização de Ativos', '', '', '', '', 0, '0000-00-00 00:00:00'),
('351010000', 'S', 'Redução a Valor Recuperável', '', '', '', '', 0, '0000-00-00 00:00:00'),
('351010100', 'A', 'Redução a Valor Recuperável de Investimentos', '', '', '', '', 0, '0000-00-00 00:00:00'),
('351010200', 'A', 'Redução a Valor Recuperável de Veículos', '', '', '', '', 0, '0000-00-00 00:00:00'),
('351010300', 'A', 'Redução a Valor Recuperável de Bens Móveis', '', '', '', '', 0, '0000-00-00 00:00:00'),
('351040400', 'A', 'Redução a Valor Recuperável de Bens Imóveis', '', '', '', '', 0, '0000-00-00 00:00:00'),
('352000000', 'S', 'Desincorporação de Ativos', '', '', '', '', 0, '0000-00-00 00:00:00'),
('352010000', 'A', 'Desincorporação de Créditos a Receber', '', '', '', '', 0, '0000-00-00 00:00:00'),
('353000000', 'S', 'Incorporação de Passivos', '', '', '', '', 0, '0000-00-00 00:00:00'),
('353010000', 'A', 'Incorporação de Dívidas', '', '', '', '', 0, '0000-00-00 00:00:00'),
('360000000', 'S', 'Perdas e Sinistros', '', '', '', '', 0, '0000-00-00 00:00:00'),
('361000000', 'S', 'Perdas de Bens', '', '', '', '', 0, '0000-00-00 00:00:00'),
('361010000', 'A', 'Perdas de Veículos', '', '', '', '', 0, '0000-00-00 00:00:00'),
('361020000', 'A', 'Perdas de Bens Móveis', '', '', '', '', 0, '0000-00-00 00:00:00'),
('361030000', 'A', 'Perdas de Bens Imóveis', '', '', '', '', 0, '0000-00-00 00:00:00'),
('362000000', 'S', 'Sinistros', '', '', '', '', 0, '0000-00-00 00:00:00'),
('362010000', 'A', 'Sinistros de Veículos', '', '', '', '', 0, '0000-00-00 00:00:00'),
('362020000', 'A', 'Sinistros de Bens Móveis', '', '', '', '', 0, '0000-00-00 00:00:00'),
('362030000', 'A', 'Sinistros de Bens Imóveis', '', '', '', '', 0, '0000-00-00 00:00:00'),
('370000000', 'S', 'Tributos e Assemelhados', '', '', '', '', 0, '0000-00-00 00:00:00'),
('371000000', 'S', 'Impostos', '', '', '', '', 0, '0000-00-00 00:00:00'),
('371010000', 'A', 'IPTU', '', '', '', '', 0, '0000-00-00 00:00:00'),
('371020000', 'A', 'IPVA', '', '', '', '', 0, '0000-00-00 00:00:00'),
('372000000', 'S', 'Taxas', '', '', '', '', 0, '0000-00-00 00:00:00'),
('373000000', 'S', 'Contribuições', '', '', '', '', 0, '0000-00-00 00:00:00'),
('374000000', 'S', 'Anuidades Profissionais', '', '', '', '', 0, '0000-00-00 00:00:00'),
('374010000', 'A', 'CRC', '', '', '', '', 0, '0000-00-00 00:00:00'),
('380000000', 'S', 'Auxílios e Ajudas', '', '', '', '', 0, '0000-00-00 00:00:00'),
('381000000', 'S', 'Auxílios e Ajudas a Parentes', '', '', '', '', 0, '0000-00-00 00:00:00'),
('381010000', 'A', 'Auxílio e Ajudas em Dinheiro', '', '', '', '', 0, '0000-00-00 00:00:00'),
('381020000', 'A', 'Auxílios e Ajudas em Bens e Serviços', '', '', '', '', 0, '0000-00-00 00:00:00'),
('381030000', 'A', 'Pagamento de Contas para Parentes', '', '', '', '', 0, '0000-00-00 00:00:00'),
('382000000', 'S', 'Auxílios e Ajudas a Necessitados', '', '', '', '', 0, '0000-00-00 00:00:00'),
('382010000', 'A', 'Doações em Dinheiro', '', '', '', '', 0, '0000-00-00 00:00:00'),
('382020000', 'A', 'Doações de Bens e Serviços', '', '', '', '', 0, '0000-00-00 00:00:00'),
('382990000', 'A', 'Outros Auxílios e Ajudas a Necessitados', '', '', '', 'D', 0, '2021-12-17 15:53:01'),
('390000000', 'S', 'VPD com Fatos Geradores Diversos', '', '', '', '', 0, '0000-00-00 00:00:00'),
('391000000', 'S', 'Indenizações e Restituições', '', '', '', '', 0, '0000-00-00 00:00:00'),
('392000000', 'S', 'Loterias e Jogos de Azar', '', '', '', '', 0, '0000-00-00 00:00:00'),
('392010000', 'A', 'Loterias e Jogos de Azar', '', '', '', '', 0, '0000-00-00 00:00:00'),
('400000000', 'S', 'Variação Patrimonial Aumentativa', NULL, NULL, NULL, 'C', 0, '2021-12-20 07:42:59'),
('410000000', 'S', 'Resultado do Trabalho', '', '', '', 'C', 0, '2021-12-20 08:01:04'),
('411000000', 'S', 'Salários e Remunerações', '', '', '', 'C', 0, '2021-12-20 08:04:15'),
('411010000', 'A', 'Salário Mensal', '', '', '', 'C', 0, '2021-12-20 08:04:35'),
('411020000', 'A', 'Adicional de Férias (1/3  de férias)', '', '', '', 'C', 0, '2021-12-20 08:05:05'),
('411030000', 'A', 'Adicional Natalino (13º salário)', '', '', '', 'C', 0, '2021-12-20 08:05:32'),
('412000000', 'S', 'Verbas Indenizatórias', '', '', '', 'C', 0, '2021-12-20 08:13:01'),
('412010000', 'A', 'Diárias Brutas', '', '', '', 'C', 0, '2021-12-20 08:13:37'),
('412020000', 'A', 'Diárias Líquidas', '', '', '', 'C', 0, '2021-12-20 08:18:48'),
('412030000', 'A', 'Vale-Alimentação', '', '', '', 'C', 0, '2021-12-20 08:19:14'),
('420000000', 'S', 'Exploração e Venda de Bens, Serviços e Assemelhados', '', '', '', 'C', 0, '2021-12-20 08:52:28'),
('421000000', 'S', 'Venda de Bens', '', '', '', 'C', 0, '2021-12-20 08:54:03'),
('422000000', 'S', 'Prestação de Serviços', '', '', '', 'C', 0, '2021-12-20 08:54:17'),
('430000000', 'S', 'Variações Aumentativas Financeiras', '', '', '', 'C', 0, '2021-12-20 08:55:54'),
('431000000', 'S', 'Juros e Correção Monetária sobre Empréstimos Concedidos', '', '', '', 'C', 0, '2021-12-20 08:56:41'),
('431010000', 'A', 'Juros sobre Empréstimos Concedidos', '', '', '', 'C', 0, '2021-12-20 08:57:18'),
('431020000', 'A', 'Correção Monetária sobre Empréstimos Concedidos', '', '', '', 'C', 0, '2021-12-20 08:57:33'),
('432000000', 'S', 'Remuneração de Depósitos Bancários e Investimentos', '', '', '', 'C', 0, '2021-12-20 09:03:41'),
('432010000', 'S', 'Remuneração de Investimentos', '', '', '', 'C', 0, '2021-12-20 09:03:59'),
('432020000', 'S', 'Remuneração de Depósitos Bancários', '', '', '', 'C', 0, '2021-12-20 09:04:23'),
('432020100', 'A', 'Remuneração da Poupança', '', '', '', 'C', 0, '2021-12-20 09:05:06'),
('433000000', 'S', 'Encargos de Mora de Empréstimos Concedidos', '', '', '', 'C', 0, '2021-12-20 09:05:47'),
('433010000', 'A', 'Juros de Mora de Empréstimos Concedidos', '', '', '', 'C', 0, '2021-12-20 09:07:01'),
('433020000', 'A', 'Multa de Mora de Empréstimos Concedidos', '', '', '', 'C', 0, '2021-12-20 09:07:18'),
('433990000', 'A', 'Outros Encargos de Mora de Empréstimos Concedidos', '', '', '', 'C', 0, '2021-12-20 09:07:32'),
('440000000', 'S', 'Variação do Valor de Elementos do Ativo e Passivo', '', '', '', 'C', 0, '2021-12-20 09:08:39'),
('441000000', 'S', 'Valorização, Incorporação e Ganhos com Ativos', '', '', '', 'C', 0, '2021-12-20 09:10:07'),
('441010000', 'S', 'Incorporação de Ativos', '', '', '', 'C', 0, '2021-12-20 09:11:45'),
('441020000', 'S', 'Ganhos com Ativos', '', '', '', 'C', 0, '2021-12-20 09:12:11'),
('441030000', 'S', 'Valorização de Ativos', '', '', '', 'C', 0, '2021-12-20 09:12:25'),
('442000000', 'S', 'Desvalorização e Desincorporação de Passivos', '', '', '', 'C', 0, '2021-12-20 09:10:33'),
('442010000', 'S', 'Redução do Valor de Passivos', '', '', '', 'C', 0, '2021-12-20 09:12:47'),
('442020000', 'S', 'Desincorporação de Passivos', '', '', '', 'C', 0, '2021-12-20 09:13:09'),
('490000000', 'S', 'Outras Variações Patrimoniais Aumentativas', '', '', '', 'C', 0, '2021-12-20 09:14:28'),
('700000000', 'S', 'Contrapartida dos Controles', NULL, NULL, NULL, 'D', 0, '2021-12-20 13:50:17'),
('710000000', 'S', 'Centros de Receita e Despesa', '', '', '', 'D', 0, '2021-12-22 15:54:37'),
('711000000', 'S', 'Centros de Receita e Despesa', '', '', '', 'D', 0, '2021-12-22 15:55:47'),
('711010000', 'A', 'Contrapartida dos Centros de Receita e Despesa', '', '', '', 'DC', 0, '2021-12-22 15:56:53'),
('800000000', 'S', 'Controles Implantados', NULL, NULL, NULL, 'C', 0, '2021-12-20 13:50:38'),
('810000000', 'S', 'Centros de Receita e Despesa', '', '', '', 'C', 0, '2021-12-22 15:55:12'),
('811000000', 'S', 'Execução por Centros de Receita e Despesa', '', '', '', 'DC', 0, '2021-12-22 15:56:20'),
('811010000', 'S', 'Família', '', '', '', 'DC', 0, '2021-12-22 15:57:47'),
('811010100', 'A', 'Everton da Rosa', '', '', '', 'DC', 0, '2021-12-22 15:58:01'),
('811010200', 'A', 'Marlise da Rosa', '', '', '', 'DC', 0, '2021-12-22 15:58:15'),
('811010300', 'S', 'Filhos', '', '', '', 'DC', 0, '2021-12-22 15:58:28'),
('811010301', 'A', 'Arthur da Rosa', '', '', '', 'DC', 0, '2021-12-22 15:58:58'),
('811010302', 'A', 'Pedro Henrique da Rosa', '', '', '', 'DC', 0, '2021-12-22 15:59:10'),
('811010399', 'A', 'Filhos', '', '', '', 'DC', 0, '2021-12-22 15:59:23'),
('811019900', 'A', 'Família', '', '', '', 'DC', 0, '2021-12-22 15:58:44'),
('811020000', 'S', 'Bens Móveis e Imóveis', '', '', '', 'DC', 0, '2021-12-22 15:59:54'),
('811020100', 'S', 'Imóveis', '', '', '', 'DC', 0, '2021-12-22 16:00:09'),
('811020101', 'A', 'Rua Ello Redel, 55, Bairro Planalto, Três de Maio, RS', '', '', '', 'DC', 0, '2021-12-22 16:00:26'),
('811020200', 'S', 'Veículos', '', '', '', 'DC', 0, '2021-12-22 16:00:41'),
('811020201', 'A', 'HB20S 2014 MLT7A35', '', '', '', 'DC', 0, '2021-12-22 16:00:54'),
('811020202', 'A', 'Yamaha Factor 2014 XXX9X99', '', '', '', 'DC', 0, '2021-12-22 16:01:05'),
('811030000', 'S', 'Parentes e Familiares', '', '', '', 'DC', 0, '2021-12-22 16:01:30'),
('811030100', 'A', 'Os Da Rosa', '', '', '', 'DC', 0, '2021-12-22 16:01:44'),
('811030200', 'A', 'Os Lara Lemes', '', '', '', 'DC', 0, '2021-12-22 16:01:54'),
('811040000', 'S', 'Projetos', '', '', '', 'DC', 0, '2021-12-22 16:08:26'),
('811040100', 'A', 'Projeto XXXXXX', '', '', '', 'DC', 0, '2021-12-22 16:08:51'),
('811990000', 'S', 'Outros Centros de Receita e Despesa', '', '', '', 'DC', 0, '2021-12-22 16:02:37'),
('811990100', 'A', 'Poupança', '', '', '', 'DC', 0, '2021-12-22 16:02:50'),
('811990200', 'A', 'Ajustes e Correções', '', '', '', 'DC', 0, '2021-12-22 16:03:05');

-- --------------------------------------------------------

--
-- Estrutura da tabela `receitas`
--
-- Criação: 22-Dez-2021 às 19:05
--

DROP TABLE IF EXISTS `receitas`;
CREATE TABLE IF NOT EXISTS `receitas` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `periodo` varchar(7) NOT NULL,
  `descricao` text NOT NULL,
  `valorInicial` decimal(10,2) NOT NULL,
  `devedor` varchar(255) NOT NULL,
  `vencimento` date DEFAULT NULL,
  `agrupador` varchar(255) DEFAULT NULL,
  `parcela` int(11) DEFAULT NULL,
  `ccResultado` varchar(9) DEFAULT NULL,
  `ccAtivo` varchar(9) DEFAULT NULL,
  `ccCentroReceitaDespesa` varchar(9) DEFAULT NULL,
  `transacao` varchar(255) DEFAULT NULL,
  `registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELACIONAMENTOS PARA TABELAS `receitas`:
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `transacoes`
--
-- Criação: 15-Dez-2021 às 19:36
--

DROP TABLE IF EXISTS `transacoes`;
CREATE TABLE IF NOT EXISTS `transacoes` (
  `id` varchar(255) NOT NULL,
  `data` date NOT NULL,
  `historico` text NOT NULL,
  `registro` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELACIONAMENTOS PARA TABELAS `transacoes`:
--

--
-- Extraindo dados da tabela `transacoes`
--

INSERT INTO `transacoes` (`id`, `data`, `historico`, `registro`) VALUES
('9df9289bb9d05dd6eb91f8a3783bfe4e2af099b9', '2021-12-15', 'outro teste', '2021-12-15 17:25:07'),
('f7df4eadac1633da2230cc43f1b956c231da4bb1', '2021-12-15', 'testese', '2021-12-15 17:10:53');


--
-- Metadata
--
USE `phpmyadmin`;

--
-- Metadata para tabela lancamentos
--

--
-- Metadata para tabela planodecontas
--

--
-- Metadata para tabela receitas
--

--
-- Metadata para tabela transacoes
--

--
-- Metadata para o banco de dados kontasdb
--
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
