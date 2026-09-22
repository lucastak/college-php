DROP DATABASE IF EXISTS locadora;
CREATE DATABASE locadora DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE locadora;

CREATE TABLE categoria (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL UNIQUE
) ENGINE=INNODB;

CREATE TABLE filme (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    preco_diaria DECIMAL(10,2) NOT NULL,
    categoria_id INT NOT NULL,
    CONSTRAINT fk_filme_categoria FOREIGN KEY (categoria_id) 
        REFERENCES categoria(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=INNODB;

CREATE TABLE ator (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(80) NOT NULL
) ENGINE=INNODB;

CREATE TABLE filme_ator (
    filme_id INT NOT NULL,
    ator_id INT NOT NULL,
    PRIMARY KEY (filme_id, ator_id),
    CONSTRAINT fk_fa_filme FOREIGN KEY (filme_id) REFERENCES filme(id),
    CONSTRAINT fk_fa_ator FOREIGN KEY (ator_id) REFERENCES ator(id)
) ENGINE=INNODB;

-- Dados para teste
INSERT INTO categoria (nome) VALUES ('Ação'), ('Comédia'), ('Drama'), ('Ficção Científica');
INSERT INTO ator (nome) VALUES ('Keanu Reeves'), ('Laurence Fishburne'), ('Leonardo DiCaprio'), ('Brad Pitt');

INSERT INTO filme (titulo, preco_diaria, categoria_id) VALUES ('Matrix', 12.50, 4);
INSERT INTO filme_ator (filme_id, ator_id) VALUES (1, 1), (1, 2);

INSERT INTO filme (titulo, preco_diaria, categoria_id) VALUES ('John Wick', 15.00, 1);
INSERT INTO filme_ator (filme_id, ator_id) VALUES (2, 1);
