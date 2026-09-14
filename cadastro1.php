<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro - In Cart</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="cadastro-container">

        <form action="salvar_usuario.php" 
              method="POST" 
              class="cadastro-box">

            <h1>
                🛒 In Cart
            </h1>

            <h2>
                Cadastro de Usuário
            </h2>

            <input type="text"
                   name="nome"
                   placeholder="Nome completo"
                   required>

            <input type="text"
                   name="cpf"
                   placeholder="CPF"
                   required>

            <input type="text"
                   name="endereco"
                   placeholder="Endereço"
                   required>

            <input type="text"
                   name="bairro"
                   placeholder="Bairro"
                   required>

            <input type="text"
                   name="cidade"
                   placeholder="Cidade"
                   required>

            <input type="text"
                   name="estado"
                   placeholder="Estado"
                   required>

            <input type="text"
                   name="cep"
                   placeholder="CEP"
                   required>

            <button type="submit">

                Continuar

            </button>

        </form>

    </div>

</body>

</html>