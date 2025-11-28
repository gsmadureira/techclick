💻 TechClick - Sistema de Gestão de Hardware

⚠️ Nota: Este projeto foi desenvolvido exclusivamente para fins educacionais e de estudo. O sistema simula um ambiente real de gestão, mas não se trata de um produto comercial.

O TechClick é um sistema web fictício desenvolvido para praticar conceitos de desenvolvimento web, gerenciamento de inventário e banco de dados. O sistema simula o controle preciso sobre produtos, categorias e movimentações de estoque de uma loja de informática, com um dashboard intuitivo para tomada de decisões.

🚀 Tecnologias Utilizadas

Este projeto foi desenvolvido utilizando uma stack clássica e robusta para desenvolvimento web local:

Linguagem de Backend: PHP (Nativo)

Banco de Dados: MySQL

Frontend: HTML5, CSS3, Bootstrap 5

Ambiente de Desenvolvimento: XAMPP (Apache + MySQL)

Gerenciamento de Banco: phpMyAdmin

📋 Funcionalidades

Dashboard Interativo: Indicadores financeiros, alertas de estoque baixo e resumo de operações diárias.

Gestão de Produtos: Cadastro completo com preços, unidades, estoque mínimo e máximo.

Controle de Estoque: Registro de entradas (compras) e saídas (vendas) com cálculo automático de saldo.

Histórico de Transações: Log detalhado de todas as movimentações com identificação visual (Entrada/Saída).

Categorização: Organização de hardware por categorias (Processadores, GPUs, RAM, etc.).

⚙️ Pré-requisitos e Instalação

Para rodar este projeto, utilizamos o pacote XAMPP, que já fornece o servidor Apache e o banco de dados MySQL.

Passo 1: Configuração do Ambiente

Baixe e instale o XAMPP.

Abra o XAMPP Control Panel.

Inicie os serviços Apache e MySQL clicando nos botões "Start".

Passo 2: Instalação dos Arquivos

Acesse a pasta de arquivos do servidor local (geralmente em C:\xampp\htdocs).

Crie uma nova pasta chamada techclick.

Coloque todos os arquivos do projeto (index.php, config.php, pastas css, js, etc.) dentro desta pasta.

Passo 3: Configuração do Banco de Dados (phpMyAdmin)

No seu navegador, acesse: http://localhost/phpmyadmin.

Clique na aba SQL no menu superior.

Copie e cole o código SQL abaixo para criar o banco e as tabelas automaticamente:

CREATE DATABASE IF NOT EXISTS techclick DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE techclick;

CREATE TABLE IF NOT EXISTS categoria (
id_categoria INT AUTO_INCREMENT PRIMARY KEY,
nome_categoria VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS produto (
id_produto INT AUTO_INCREMENT PRIMARY KEY,
nome_produto VARCHAR(100) NOT NULL UNIQUE,
preco_produto DECIMAL(10,2) NOT NULL,
unidade_produto VARCHAR(20) NOT NULL DEFAULT 'un',
id_categoria INT NULL,
estoque_minimo INT NOT NULL DEFAULT 5,
estoque_maximo INT NOT NULL DEFAULT 100,
data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS estoque (
id_produto INT PRIMARY KEY,
quantidade INT UNSIGNED NOT NULL DEFAULT 0,
FOREIGN KEY (id_produto) REFERENCES produto(id_produto) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS movimentacoes (
id_movimento INT AUTO_INCREMENT PRIMARY KEY,
id_produto INT NOT NULL,
tipo ENUM('ENTRADA', 'SAIDA') NOT NULL,
quantidade INT NOT NULL,
data_movimento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
observacoes VARCHAR(255),
FOREIGN KEY (id_produto) REFERENCES produto(id_produto) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Dados Iniciais (Opcional)
INSERT INTO categoria (nome_categoria) VALUES ('Processadores'), ('Placas de Vídeo'), ('Memória RAM'), ('Armazenamento'), ('Periféricos');

Clique em Executar (ou Go).

Passo 4: Configuração da Conexão

Certifique-se de que o arquivo config.php na pasta do projeto está configurado corretamente:

<?php
define('HOST', 'localhost');
define('USER', 'root');
define('PASS', ''); // Senha padrão do XAMPP é vazia
define('BASE', 'techclick');

$conn = new mysqli(HOST, USER, PASS, BASE);
?>

🚀 Como Utilizar

Abra seu navegador preferido.

Acesse o endereço: http://localhost/techclick

O sistema abrirá diretamente no Dashboard.

Utilize o menu superior ou os atalhos rápidos para cadastrar seus primeiros produtos e movimentações.

🛡️ Segurança e Aprendizado

O sistema implementa práticas básicas de segurança ideais para estudo, como:

Uso de Prepared Statements / Real Escape String para evitar SQL Injection.

Transações SQL (Commit/Rollback) para garantir a integridade do estoque financeiro.

Validações de estoque negativo no banco de dados.

Projeto Acadêmico / Estudo - TechClick
