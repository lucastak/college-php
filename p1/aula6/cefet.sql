CREATE TABLE empresa (

)ENGINE=INNODB

CREATE TABLE servico_empresa (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    servico_id INT NOT NULL,
    empresa_id INT NOT NULL
    CONSTRAINT fk_servico_empresa__empresa_ID

)


Serviço            1----* ServiçoEmpresa *----1 Empresa
id                                              id
descricao                                       nome
valor                          