CREATE DATABASE petshop;
USE petshop;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22/05/2023 às 21:58
-- Versão do servidor: 8.0.32
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `petshop`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `agendamentos`
--

CREATE TABLE `agendamentos` (
  `pk_Agendamento` int UNSIGNED NOT NULL,
  `fk_Funcionario` int UNSIGNED NOT NULL,
  `fk_Animal` int UNSIGNED DEFAULT NULL,
  `data_agendamento` date NOT NULL,
  `horario_agendamento` time NOT NULL,
  `status` enum('Disponivel','Marcado','Em_Andamento','Concluido','Cancelado') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Disponivel',
  `descricao` text COLLATE utf8mb4_general_ci,
  `tipo` enum('Banho','Tosa','Veterinário','Banho e Tosa') COLLATE utf8mb4_general_ci NOT NULL,
  `transporte` enum('buscar','trazer','buscar/trazer','nenhum') COLLATE utf8mb4_general_ci NOT NULL,
  `ativo` enum('ativo','inativo') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `animais`
--

CREATE TABLE `animais` (
  `pk_Animal` int UNSIGNED NOT NULL,
  `fk_Cliente` int UNSIGNED NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `data_nascimento` date NOT NULL,
  `sexo` enum('F','M','I') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `especie` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `raca` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `peso` float NOT NULL,
  `cor` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `data_cadastro` date NOT NULL,
  `ativo` enum('ativo','inativo') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `pk_Cliente` int UNSIGNED NOT NULL,
  `cpf` char(11) COLLATE utf8mb4_general_ci NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `sobrenome` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `celular` char(11) COLLATE utf8mb4_general_ci NOT NULL,
  `cep` char(8) COLLATE utf8mb4_general_ci NOT NULL,
  `logradouro` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `numero` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `complemento` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `bairro` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `municipio` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `uf` char(2) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `senha` varchar(250) COLLATE utf8mb4_general_ci NOT NULL,
  `ativo` enum('ativo','inativo') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `comentarios`
--

CREATE TABLE `comentarios` (
  `pk_Comentario` int UNSIGNED NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `telefone` char(11) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `mensagem` text COLLATE utf8mb4_general_ci,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionarios`
--

CREATE TABLE `funcionarios` (
  `pk_Funcionario` int UNSIGNED NOT NULL,
  `nome` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `cpf` char(11) COLLATE utf8mb4_general_ci NOT NULL,
  `profissao` enum('Veterinario','Secretaria','Esteticista','Motorista','admin') COLLATE utf8mb4_general_ci NOT NULL,
  `senha` varchar(250) COLLATE utf8mb4_general_ci NOT NULL,
  `ativo` enum('ativo','demitido') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `funcionarios`
--

INSERT INTO `funcionarios` (`pk_Funcionario`, `nome`, `cpf`, `profissao`, `senha`, `ativo`) VALUES
(1, 'Michael', '03524746020', 'admin', 'b123e9e19d217169b981a61188920f9d28638709a5132201684d792b9264271b7f09157ed4321b1c097f7a4abecfc0977d40a7ee599c845883bd1074ca23c4af', 'ativo'),
(2, 'Dominic', '83358339076', 'Motorista', 'aa5036ce2027e5185844a2358cc2141946e93e86fb9c42f86cdefa23a0ddaf29b2164011249d09e5e300e507db0769244a2f1488a5f8fa6f845392d64ea21817', 'demitido');

-- --------------------------------------------------------

--
-- Estrutura para tabela `veiculos`
--

CREATE TABLE `veiculos` (
  `pk_veiculo` int NOT NULL,
  `placa` char(7) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fk_motorista` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD PRIMARY KEY (`pk_Agendamento`),
  ADD KEY `fk_Agendamentos_Funcionarios` (`fk_Funcionario`),
  ADD KEY `fk_Agendamentos_Animais` (`fk_Animal`);

--
-- Índices de tabela `animais`
--
ALTER TABLE `animais`
  ADD PRIMARY KEY (`pk_Animal`),
  ADD KEY `fk_Animais_Clientes1` (`fk_Cliente`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`pk_Cliente`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`pk_Comentario`);

--
-- Índices de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`pk_Funcionario`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- Índices de tabela `veiculos`
--
ALTER TABLE `veiculos`
  ADD PRIMARY KEY (`pk_veiculo`),
  ADD UNIQUE KEY `placa` (`placa`),
  ADD KEY `fk_motorista` (`fk_motorista`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  MODIFY `pk_Agendamento` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `animais`
--
ALTER TABLE `animais`
  MODIFY `pk_Animal` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `pk_Cliente` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `pk_Comentario` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `pk_Funcionario` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `veiculos`
--
ALTER TABLE `veiculos`
  MODIFY `pk_veiculo` int NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD CONSTRAINT `fk_Agendamentos_Animais` FOREIGN KEY (`fk_Animal`) REFERENCES `animais` (`pk_Animal`),
  ADD CONSTRAINT `fk_Agendamentos_Funcionarios` FOREIGN KEY (`fk_Funcionario`) REFERENCES `funcionarios` (`pk_Funcionario`);

--
-- Restrições para tabelas `animais`
--
ALTER TABLE `animais`
  ADD CONSTRAINT `fk_Animais_Clientes1` FOREIGN KEY (`fk_Cliente`) REFERENCES `clientes` (`pk_Cliente`);

--
-- Restrições para tabelas `veiculos`
--
ALTER TABLE `veiculos`
  ADD CONSTRAINT `veiculos_ibfk_1` FOREIGN KEY (`fk_motorista`) REFERENCES `funcionarios` (`pk_Funcionario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
