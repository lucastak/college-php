DROP DATABASE IF EXISTS cefet;

CREATE DATABASE cefet DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE cefet;


CREATE TABLE IF NOT EXISTS servico (
    id         INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	descricao  VARCHAR(50) NOT NULL,
	valor      DECIMAL(10,2) DEFAULT 0,
	CONSTRAINT unq_servico__descricao UNIQUE( descricao )
) ENGINE=INNODB;


INSERT INTO servico ( descricao, valor ) VALUES
( 'Pintura',    200.00 ),
( 'Jardinagem', 800.00 ),
( 'Manutenção de Computadores', 500.00 ),
( 'Contabilidade', 400.00 );


CREATE TABLE IF NOT EXISTS empresa (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	nome VARCHAR(50) NOT NULL UNIQUE
) ENGINE=INNODB;


CREATE TABLE IF NOT EXISTS servico_empresa (
	id			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	empresa_id  INT NOT NULL,
	servico_id	INT NOT NULL,

	CONSTRAINT unq_servico_empresa
		UNIQUE( empresa_id, servico_id ),

	CONSTRAINT fk_servico_empresa__empresa_id
		FOREIGN KEY ( empresa_id ) REFERENCES empresa(id)
		ON UPDATE CASCADE ON DELETE CASCADE,

	CONSTRAINT fk_servico_empresa__servico_id
		FOREIGN KEY ( servico_id ) REFERENCES servico(id)
		ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=INNODB;


INSERT INTO empresa ( nome ) VALUES
( 'Acme S/A' ),
( 'Xpto Contabilidade' ),
( 'ABCD Manutenção' );


INSERT INTO servico_empresa ( empresa_id, servico_id ) VALUES
( 1, 1 ),
( 2, 4 ),
( 3, 3 ),
( 3, 2 );