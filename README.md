# 💻 TechClick - Sistema de Gestão de Hardware

⚠️ **Nota:** Este projeto foi desenvolvido exclusivamente para fins
educacionais e de estudo. O sistema simula um ambiente real de gestão,
mas não se trata de um produto comercial.

O **TechClick** é um sistema web fictício desenvolvido para praticar
conceitos de desenvolvimento web, gerenciamento de inventário e banco de
dados. O sistema simula o controle preciso sobre produtos, categorias e
movimentações de estoque de uma loja de informática, com um dashboard
intuitivo para tomada de decisões.

------------------------------------------------------------------------

## 🚀 Tecnologias Utilizadas

-   **Backend:** PHP (Nativo)
-   **Banco de Dados:** MySQL
-   **Frontend:** HTML5, CSS3, Bootstrap 5
-   **Ambiente:** XAMPP (Apache + MySQL)
-   **Gerenciamento:** phpMyAdmin

------------------------------------------------------------------------

## 📋 Funcionalidades

-   Dashboard Interativo
-   Gestão de Produtos
-   Controle de Estoque
-   Histórico de Movimentações
-   Categorização de Hardware

------------------------------------------------------------------------

## ⚙️ Pré-requisitos e Instalação

### Passo 1 --- Configuração do Ambiente

1.  Instale o XAMPP\
2.  Inicie Apache e MySQL

### Passo 2 --- Instalação dos Arquivos

1.  Vá para `C:\xampp\htdocs`
2.  Crie a pasta `techclick`
3.  Coloque os arquivos do projeto dentro dela

### Passo 3 --- Banco de Dados

Acesse `http://localhost/phpmyadmin` e execute:

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

    INSERT INTO categoria (nome_categoria) VALUES 
    ('Processadores'), ('Placas de Vídeo'), ('Memória RAM'), ('Armazenamento'), ('Periféricos');

### Passo 4 --- Configuração da Conexão

    <?php
    define('HOST', 'localhost');
    define('USER', 'root');
    define('PASS', '');
    define('BASE', 'techclick');

    $conn = new mysqli(HOST, USER, PASS, BASE);
    ?>

------------------------------------------------------------------------

## 🚀 Como Utilizar

1.  Abra o navegador\
2.  Acesse **http://localhost/techclick**

------------------------------------------------------------------------

## 🛡️ Segurança

-   Prepared Statements\
-   Transações (Commit/Rollback)\
-   Validação de estoque negativo

------------------------------------------------------------------------

## 📚 Projeto Acadêmico --- TechClick
