-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 09-Ago-2022 às 22:34
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
-- Banco de dados: `key_control`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `numero` varchar(50) NOT NULL COMMENT 'num do imóvel',
  `bairro` varchar(100) NOT NULL COMMENT 'bairro onde imóvel se localiza',
  `cidade` varchar(255) NOT NULL COMMENT 'cidade onde imóvel se localiza',
  `rua` varchar(1000) NOT NULL COMMENT 'rua onde imóvel se localiza',
  `complemento` varchar(1000) DEFAULT NULL COMMENT 'informação adicional sobre o endereço, como se é apartamento ou casa, bloco e num do apartamento, etc'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='guarda endereço do imóvel da chave';

-- --------------------------------------------------------

--
-- Estrutura da tabela `borrowing`
--

CREATE TABLE `borrowing` (
  `id` int(11) NOT NULL,
  `data_checkin` datetime NOT NULL COMMENT 'data de DEVOLUÇÃO PREVISTA da chave',
  `data_checkout` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'data de RETIRADA da chave',
  `requester_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='salva os empréstimos de chaves';

-- --------------------------------------------------------

--
-- Estrutura da tabela `hook`
--

CREATE TABLE `hook` (
  `id` int(11) NOT NULL,
  `codigo` varchar(5) NOT NULL,
  `tipo` enum('Aluguel','Venda') NOT NULL,
  `usado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='equivale aos ganchos físicos no painel';

-- --------------------------------------------------------

--
-- Estrutura da tabela `keys`
--

CREATE TABLE `keys` (
  `id` int(11) NOT NULL,
  `gancho` varchar(10) DEFAULT NULL COMMENT 'o gancho onde a chave se encontra fisicamente no painel da imobiliaria, segue padrão letra-numero',
  `sicadi` varchar(20) NOT NULL COMMENT 'código do imóvel no sistema sicadi ',
  `tipo` enum('Venda','Aluguel') NOT NULL COMMENT 'se a chave é de venda ou aluguel',
  `status` enum('Disponível','Emprestado','Atrasado','Perdido','Indisponível') NOT NULL COMMENT 'os diferentes status que a chave pode se encontrar',
  `data_in` date NOT NULL DEFAULT current_timestamp() COMMENT 'data de cadastro',
  `adicional` text DEFAULT NULL COMMENT 'informação adicional sobre a chave ou imóvel',
  `endereco_id` int(11) NOT NULL,
  `gancho_id` int(11) DEFAULT NULL,
  `gancho_manual` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'se o gancho eh automatico ou escolhido manual, 0 auto, 1 manual'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='armazena as chaves de imóveis disponíveis para locação/venda';

-- --------------------------------------------------------

--
-- Estrutura da tabela `keys_borrowing`
--

CREATE TABLE `keys_borrowing` (
  `id` int(11) NOT NULL,
  `keys_id` int(11) NOT NULL,
  `borrowing_id` int(11) NOT NULL,
  `is_ativo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'identifica se o empréstimo ainda está ativo e alertas não foram enviados',
  `is_reminder` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'identifica se essa instância já foi enviado email de lembrete 30 minutos'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='salva relação entre as chaves e emprestimos';

-- --------------------------------------------------------

--
-- Estrutura da tabela `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `keys_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `operation` enum('Criação','Alteração','Empréstimo','Devolução','Exclusão') NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='salva logs de alteração de status das chaves';

-- --------------------------------------------------------

--
-- Estrutura da tabela `requester`
--

CREATE TABLE `requester` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL COMMENT 'nome completo ',
  `telefone` varchar(20) DEFAULT NULL,
  `ddd` varchar(5) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `documento` varchar(100) DEFAULT NULL COMMENT 'ex RG, CPF, CNH',
  `tipo` enum('Cliente','Manutenção','Prestador de serviço','Marketing','Vistoria','Diretoria') NOT NULL COMMENT 'possibilidades de pessoas que pedem as chaves emprestadas'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='guarda informações de pessoas que pegam chaves emprestadas';

-- --------------------------------------------------------

--
-- Estrutura da tabela `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `data_in` date NOT NULL DEFAULT current_timestamp() COMMENT 'data de cadastro',
  `nome` varchar(255) NOT NULL COMMENT 'primeiro nome',
  `senha` varchar(255) NOT NULL COMMENT 'senha criptografada',
  `email` varchar(255) NOT NULL COMMENT 'email de acesso',
  `sobrenome` varchar(200) NOT NULL COMMENT 'sobrenome'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='armazena os usuários do sistema';

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `borrowing`
--
ALTER TABLE `borrowing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_requester` (`requester_id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Índices para tabela `hook`
--
ALTER TABLE `hook`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `keys`
--
ALTER TABLE `keys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_address` (`endereco_id`) USING BTREE,
  ADD KEY `fk_hook` (`gancho_id`);

--
-- Índices para tabela `keys_borrowing`
--
ALTER TABLE `keys_borrowing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_borrowing` (`borrowing_id`),
  ADD KEY `fk_keys` (`keys_id`) USING BTREE;

--
-- Índices para tabela `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`),
  ADD KEY `fk_keys` (`keys_id`);

--
-- Índices para tabela `requester`
--
ALTER TABLE `requester`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `borrowing`
--
ALTER TABLE `borrowing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `hook`
--
ALTER TABLE `hook`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `keys`
--
ALTER TABLE `keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `keys_borrowing`
--
ALTER TABLE `keys_borrowing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `requester`
--
ALTER TABLE `requester`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `borrowing`
--
ALTER TABLE `borrowing`
  ADD CONSTRAINT `fk_requester` FOREIGN KEY (`requester_id`) REFERENCES `requester` (`id`),
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Limitadores para a tabela `keys`
--
ALTER TABLE `keys`
  ADD CONSTRAINT `fk_address` FOREIGN KEY (`endereco_id`) REFERENCES `address` (`id`),
  ADD CONSTRAINT `fk_hook` FOREIGN KEY (`gancho_id`) REFERENCES `hook` (`id`);

--
-- Limitadores para a tabela `keys_borrowing`
--
ALTER TABLE `keys_borrowing`
  ADD CONSTRAINT `fk_borrowing` FOREIGN KEY (`borrowing_id`) REFERENCES `borrowing` (`id`),
  ADD CONSTRAINT `fk_keys` FOREIGN KEY (`keys_id`) REFERENCES `keys` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `log_ibfk_1` FOREIGN KEY (`keys_id`) REFERENCES `keys` (`id`),
  ADD CONSTRAINT `log_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
