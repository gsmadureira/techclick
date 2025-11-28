-- ============================
-- Criação do Banco de Dados: TechClick
-- ============================
CREATE SCHEMA IF NOT EXISTS techclick 
    DEFAULT CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

USE techclick;

-- ============================
-- Tabela: categoria
-- ============================
CREATE TABLE IF NOT EXISTS categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nome_categoria VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir categorias padrão de INFORMÁTICA
INSERT INTO categoria (nome_categoria) VALUES
('Processadores'),
('Placas de Vídeo (GPU)'),
('Placas-Mãe'),
('Memória RAM'),
('Armazenamento (SSD/HD)'),
('Fontes de Alimentação'),
('Gabinetes e Coolers'),
('Periféricos (Teclado/Mouse)'),
('Monitores'),
('Redes e Cabos'),
('Softwares e Sistemas');

-- ============================
-- Tabela: produto
-- ============================
CREATE TABLE IF NOT EXISTS produto (
    id_produto INT AUTO_INCREMENT PRIMARY KEY,
    nome_produto VARCHAR(100) NOT NULL UNIQUE,
    preco_produto DECIMAL(10,2) NOT NULL CHECK (preco_produto > 0),
    unidade_produto VARCHAR(20) NOT NULL DEFAULT 'un', -- Ajustado para 'un' como padrão
    id_categoria INT NULL,
    estoque_minimo INT NOT NULL DEFAULT 5 CHECK (estoque_minimo >= 0),
    estoque_maximo INT NOT NULL DEFAULT 100,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX (id_categoria),
    FOREIGN KEY (id_categoria)
        REFERENCES categoria(id_categoria)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================
-- Tabela: estoque
-- ============================
CREATE TABLE IF NOT EXISTS estoque (
    id_produto INT PRIMARY KEY,
    -- INT UNSIGNED impede números negativos no nível do banco.
    -- Se o PHP tentar inserir negativo, o MySQL retornará erro "Out of range", o que é seguro.
    quantidade INT UNSIGNED NOT NULL DEFAULT 0, 

    FOREIGN KEY (id_produto)
        REFERENCES produto(id_produto)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================
-- Tabela: movimentacoes
-- ============================
CREATE TABLE IF NOT EXISTS movimentacoes (
    id_movimento INT AUTO_INCREMENT PRIMARY KEY,
    id_produto INT NOT NULL,
    tipo ENUM('ENTRADA', 'SAIDA') NOT NULL,
    quantidade INT NOT NULL CHECK (quantidade > 0), 
    data_movimento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    observacoes VARCHAR(255),

    INDEX (id_produto),
    FOREIGN KEY (id_produto)
        REFERENCES produto(id_produto)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;