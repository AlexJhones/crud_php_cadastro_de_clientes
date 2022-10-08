<?php
/* Remover caracteres não numéricos (texto) de uma string.
Garantindo que o conteúdo digitado em um campo seja composto apenas por números */
function limpar_texto($str){
    return preg_replace("/[^0-9]/", "", $str);
}

if(count($_POST)) {
    include('conexao.php');

    $error = false;
    $nome_cliente =$_POST['nome_cliente'];
    $email =$_POST['email'];
    $telefone =$_POST['telefone'];
    $nascimento =$_POST['nascimento'];

    if(empty($nome_cliente)){
        $error = "Preencha o nome";
    }

    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = "Preencha o e-mail";
    }

    if(!empty($telefone)) {
        $telefone = limpar_texto($telefone);
        if(strlen($telefone) != 11){
            $error = "O telefone deve ser preenchido no padrão: (82) 98888-8888";
        }
    }

    if($error){
        echo "<p>ERROR: $error</p>";
    }else{
        $sql_code = "INSERT INTO clientes (nome_cliente, email, telefone, nascimento, data)
        VALUE ('$nome_cliente', '$email', '$telefone', '$nascimento', NOW())";
        $enviado = $mysqli->query($sql_code) or die($mysqli->error);
        if($enviado){
            echo "Cliente cadastrado com sucesso.";
            unset($_POST);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Clientes</title>
</head>
<body>
    <h1>Cadastro de Clientes</h1>
    <a href="clientes.php">Voltar para a Lista</a>
    <form method="POST" action="">
        <p>
            <label>Nome:</label>
            <input value="<?php if(isset($_POST['nome_cliente'])) echo $_POST['nome_cliente']?>" name="nome_cliente" type="text">
        </p>

        <p>
            <label>E-mail:</label>
            <input value="<?php if(isset($_POST['email'])) echo $_POST['email']?>" name="email" type="text">
        </p>

        <p>
            <label>Telefone:</label>
            <input value="<?php if(isset($_POST['telefone'])) echo $_POST['telefone']?>" placeholder="(82) 98888-8888" name="telefone" type="text">
        </p>

        <p>
            <label>Data de Nascimento:</label>
            <input value="<?php if(isset($_POST['nascimento'])) echo $_POST['nascimento']?>" name="nascimento" type="date">
        </p>

        <p>
            <button type="submit">Salvar Cliente</button>
        </p>
    </form>
</body>
</html>