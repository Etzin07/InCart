USE incart;

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Não inserimos nenhum admin aqui de propósito: rode admin_criar.php uma
-- vez pelo navegador pra cadastrar o primeiro admin com senha já criptografada
-- (password_hash), em vez de guardar uma senha em texto puro dentro do .sql.