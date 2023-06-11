DROP DATABASE petshop;
CREATE DATABASE petshop;
USE petshop;


-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 06-Jun-2023 às 17:29
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 8.1.6

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
-- Estrutura da tabela `agendamentos`
--

CREATE TABLE `agendamentos` (
  `pk_Agendamento` int(10) UNSIGNED NOT NULL,
  `fk_Funcionario` int(10) UNSIGNED NOT NULL,
  `fk_Animal` int(10) UNSIGNED DEFAULT NULL,
  `data_agendamento` date NOT NULL,
  `horario_agendamento` time NOT NULL,
  `status` enum('Disponivel','Marcado','Em_Andamento','Concluido','Cancelado') NOT NULL DEFAULT 'Disponivel',
  `descricao` text DEFAULT NULL,
  `tipo` enum('Banho','Tosa','Veterinário','Banho e Tosa') NOT NULL,
  `transporte` enum('buscar','trazer','buscar/trazer','nenhum') NOT NULL,
  `ativo` enum('ativo','inativo') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `agendamentos`
--

INSERT INTO `agendamentos` (`pk_Agendamento`, `fk_Funcionario`, `fk_Animal`, `data_agendamento`, `horario_agendamento`, `status`, `descricao`, `tipo`, `transporte`, `ativo`) VALUES
(1, 3, 1, '2023-06-07', '09:00:00', 'Marcado', NULL, 'Veterinário', 'buscar/trazer', 'ativo'),
(2, 3, NULL, '2023-06-07', '10:30:00', 'Disponivel', NULL, 'Veterinário', 'buscar', 'ativo'),
(3, 3, NULL, '2023-06-07', '15:00:00', 'Disponivel', NULL, 'Veterinário', 'buscar', 'ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `animais`
--

CREATE TABLE `animais` (
  `pk_Animal` int(10) UNSIGNED NOT NULL,
  `fk_Cliente` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `data_nascimento` date NOT NULL,
  `sexo` enum('F','M','I') DEFAULT NULL,
  `especie` varchar(45) NOT NULL,
  `raca` varchar(45) NOT NULL,
  `peso` float NOT NULL,
  `cor` varchar(45) NOT NULL,
  `data_cadastro` date NOT NULL,
  `ativo` enum('ativo','inativo') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `animais`
--

INSERT INTO `animais` (`pk_Animal`, `fk_Cliente`, `nome`, `data_nascimento`, `sexo`, `especie`, `raca`, `peso`, `cor`, `data_cadastro`, `ativo`) VALUES
(1, 1, 'Nika', '2023-01-08', 'M', 'Cachorro', 'Bull Terrier', 20, 'Branco', '2023-06-06', 'ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `clientes`
--

CREATE TABLE `clientes` (
  `pk_Cliente` int(10) UNSIGNED NOT NULL,
  `cpf` char(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `sobrenome` varchar(200) NOT NULL,
  `celular` char(11) NOT NULL,
  `cep` char(8) NOT NULL,
  `logradouro` varchar(100) NOT NULL,
  `numero` varchar(5) NOT NULL,
  `complemento` varchar(50) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `municipio` varchar(100) NOT NULL,
  `uf` char(2) NOT NULL,
  `email` varchar(200) NOT NULL,
  `senha` varchar(250) NOT NULL,
  `ativo` enum('ativo','inativo') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `clientes`
--

INSERT INTO `clientes` (`pk_Cliente`, `cpf`, `nome`, `sobrenome`, `celular`, `cep`, `logradouro`, `numero`, `complemento`, `bairro`, `municipio`, `uf`, `email`, `senha`, `ativo`) VALUES
(1, '07363631069', 'Nathan', 'Barros', '11967154425', '04235460', 'Rua da Mina Central', '88', '', 'Cidade Nova Heliópolis', 'São Paulo', 'SP', 'nathan@email.com', '3a20aafb67bb5440d154b49262a6412bb0ee4b0c1ba449e4456e1c789cb91b223726aa34e2b233b0f260e650bf45923946f06ad4ba791ad4299bb6db81150039', 'ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `comentarios`
--

CREATE TABLE `comentarios` (
  `pk_Comentario` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `telefone` char(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `mensagem` text DEFAULT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `funcionarios`
--

CREATE TABLE `funcionarios` (
  `pk_Funcionario` int(10) UNSIGNED NOT NULL,
  `nome` varchar(200) NOT NULL,
  `cpf` char(11) NOT NULL,
  `profissao` enum('Veterinario','Secretaria','Esteticista','Motorista','admin') NOT NULL,
  `senha` varchar(250) NOT NULL,
  `ativo` enum('ativo','demitido') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `funcionarios`
--

INSERT INTO `funcionarios` (`pk_Funcionario`, `nome`, `cpf`, `profissao`, `senha`, `ativo`) VALUES
(1, 'Michael', '03524746020', 'admin', 'b123e9e19d217169b981a61188920f9d28638709a5132201684d792b9264271b7f09157ed4321b1c097f7a4abecfc0977d40a7ee599c845883bd1074ca23c4af', 'ativo'),
(2, 'Dominic', '83358339076', 'Motorista', 'aa5036ce2027e5185844a2358cc2141946e93e86fb9c42f86cdefa23a0ddaf29b2164011249d09e5e300e507db0769244a2f1488a5f8fa6f845392d64ea21817', 'demitido'),
(3, 'Batatinha', '08517575024', 'Veterinario', '17457b77746e60834d352d1c8fa11d64c78dace240ac0714bb6be217c2752c1da28c812da85f5d350a85ccbc57597faa23823b5047c8bd129b0c920759691db5', 'ativo'),
(4, 'Berinjelinha', '34623951065', 'Secretaria', '3c3311b08bc3639a9cd1b0f2e32894142dcea5f556d427149fe8a4e70242493faa429e39d771f614b7fc58e5702a9ad4e93a5d23a17031cdca70f319ee45d616', 'ativo'),
(5, 'Tomatinho', '19993196053', 'Esteticista', '2aadcdc852583e06f01ad04ac55ab3c2da3659de18bcec64be6d4b9940e77e8682c752a9ed04bec2abf0109a11398f33a43c0867e19746528b4c55ed4ab9c060', 'ativo'),
(6, 'Abobrinha', '19611070071', 'Motorista', '9848f44ac6e0b2683b282ac04149da7ce9a9c25c910fb690d9438b5873708eb05e68d395433c7203971d5f79da0e6295c805594e368f81934e41946b2ab2b274', 'ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `veiculos`
--

CREATE TABLE `veiculos` (
  `pk_veiculo` int(11) NOT NULL,
  `placa` char(7) DEFAULT NULL,
  `modelo` varchar(20) DEFAULT NULL,
  `marca` varchar(20) DEFAULT NULL,
  `ativo` enum('ativo','inativo') DEFAULT 'ativo',
  `fk_motorista` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `veiculos`
--

INSERT INTO `veiculos` (`pk_veiculo`, `placa`, `modelo`, `marca`, `ativo`, `fk_motorista`) VALUES
(1, 'ABC1234', 'Camaro', 'Chevrolet', 'inativo', NULL),
(2, 'ABC1D23', 'seila', 'Ferrari', 'ativo', NULL),
(3, 'NIE4113', 'Celta', 'Chevrolet', 'ativo', NULL),
(4, 'ABC4444', 'wrewrwer', 'wrfwerrfwe', 'ativo', NULL),
(5, 'TRE1234', 'Camaro', 'Chevrolet', 'ativo', NULL),
(6, 'FFF1234', 'Camaro', 'Chevrolet', 'ativo', NULL),
(7, 'WWW5555', 'Hum', 'Chevrolet', 'ativo', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD PRIMARY KEY (`pk_Agendamento`),
  ADD KEY `fk_Agendamentos_Funcionarios` (`fk_Funcionario`),
  ADD KEY `fk_Agendamentos_Animais` (`fk_Animal`);

--
-- Índices para tabela `animais`
--
ALTER TABLE `animais`
  ADD PRIMARY KEY (`pk_Animal`),
  ADD KEY `fk_Animais_Clientes1` (`fk_Cliente`);

--
-- Índices para tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`pk_Cliente`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices para tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`pk_Comentario`);

--
-- Índices para tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`pk_Funcionario`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- Índices para tabela `veiculos`
--
ALTER TABLE `veiculos`
  ADD PRIMARY KEY (`pk_veiculo`),
  ADD UNIQUE KEY `placa` (`placa`),
  ADD KEY `fk_motorista` (`fk_motorista`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  MODIFY `pk_Agendamento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `animais`
--
ALTER TABLE `animais`
  MODIFY `pk_Animal` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `pk_Cliente` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `pk_Comentario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `pk_Funcionario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `veiculos`
--
ALTER TABLE `veiculos`
  MODIFY `pk_veiculo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD CONSTRAINT `fk_Agendamentos_Animais` FOREIGN KEY (`fk_Animal`) REFERENCES `animais` (`pk_Animal`),
  ADD CONSTRAINT `fk_Agendamentos_Funcionarios` FOREIGN KEY (`fk_Funcionario`) REFERENCES `funcionarios` (`pk_Funcionario`);

--
-- Limitadores para a tabela `animais`
--
ALTER TABLE `animais`
  ADD CONSTRAINT `fk_Animais_Clientes1` FOREIGN KEY (`fk_Cliente`) REFERENCES `clientes` (`pk_Cliente`);

--
-- Limitadores para a tabela `veiculos`
--
ALTER TABLE `veiculos`
  ADD CONSTRAINT `veiculos_ibfk_1` FOREIGN KEY (`fk_motorista`) REFERENCES `funcionarios` (`pk_Funcionario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
