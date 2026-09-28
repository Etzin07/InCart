CREATE DATABASE incart;
USE incart;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    cpf VARCHAR(20) NOT NULL UNIQUE,
    endereco VARCHAR(200) NOT NULL,
    bairro VARCHAR(100) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    estado VARCHAR(50) NOT NULL,
    cep VARCHAR(20) NOT NULL
);

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    preco DECIMAL(10,2) NOT NULL
);

CREATE TABLE logins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpf VARCHAR(20) NOT NULL,
    login VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,

    FOREIGN KEY (cpf) REFERENCES usuarios(cpf)
);

CREATE TABLE carrinho (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpf VARCHAR(20) NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL,

    UNIQUE (cpf, produto_id),

    FOREIGN KEY (cpf) REFERENCES usuarios(cpf),
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

CREATE TABLE favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpf VARCHAR(20) NOT NULL,
    produto_id INT NOT NULL,

    UNIQUE (cpf, produto_id),

    FOREIGN KEY (cpf) REFERENCES usuarios(cpf),
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpf VARCHAR(20) NOT NULL,
    data_pedido DATETIME NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    pagamento VARCHAR(50) NOT NULL,
    entrega VARCHAR(20) NOT NULL,

    FOREIGN KEY (cpf) REFERENCES usuarios(cpf)
);

CREATE TABLE itens_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL,
    preco DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);
