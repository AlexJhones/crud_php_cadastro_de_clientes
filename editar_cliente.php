<?php
include('conexao.php');
$id = intval($_GET['id']);

/* Remover caracteres não numéricos (texto) de uma string.
Garantindo que o conteúdo digitado em um campo seja composto apenas por números */
function limpar_texto($str){
    return preg_replace("/[^0-9]/", "", $str);
}

if(count($_POST)) {
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
        $sql_code= "UPDATE clientes
        SET nome_cliente = '$nome_cliente',
        email = '$email',
        telefone = '$telefone',
        nascimento = '$nascimento'
        WHERE id = '$id'";

        $enviado = $mysqli->query($sql_code) or die($mysqli->error);
        if($enviado){
            echo "Cliente atualizado com sucesso.";
            unset($_POST);
        }
    }
}

$sql_cliente = "SELECT * FROM clientes WHERE id = '$id'";
$query_cliente = $mysqli->query($sql_cliente) or die($mysqli->error);
$cliente = $query_cliente->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar de Clientes</title>
</head>
<body>
    <h1>Editar de Clientes</h1>
    <a href="clientes.php">Voltar para a Lista</a>
    <form method="POST" action="">
        <p>
            <label>Nome:</label>
            <input value="<?php echo $cliente['nome_cliente']?>" name="nome_cliente" type="text">
        </p>

        <p>
            <label>E-mail:</label>
            <input value="<?php echo $cliente['email']?>" name="email" type="text">
        </p>

        <p>
            <label>Telefone:</label>
            <input value="<?php if(!empty($cliente['telefone'])) echo formatar_telefone($cliente['telefone'])?>" placeholder="(82) 98888-8888" name="telefone" type="text">
        </p>

        <p>
            <label>Data de Nascimento:</label>
            <input value="<?php echo $cliente['nascimento']?>" name="nascimento" type="date">
        </p>

        <p>
            <button type="submit">Salvar Cliente</button>
        </p>
    </form>
</body>
</html>