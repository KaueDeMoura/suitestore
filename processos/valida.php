<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Formulário de Cadastro</title>
</head>
<body>
 

 
<form action="valida.php" method="POST">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" pattern="[a-zA-ZÀ-ÿ\s]+"> <!-- <input type="text" id="nome" name="nome" pattern="[a-zA-ZÀ-ÿ\s]+" title="O nome deve conter apenas letras e espaços." required> -->
    <br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email">
    <br>
    <label for="senha">Senha:</label>
    <input type="password" id="senha" name="senha">
    <br>

    <br>
    <button type="submit" name="bt_enviar">Enviar</button>
</form>
 
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $erros = [];
 

    if (empty($_POST["nome"]) || (!preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $_POST["nome"])) ) {
        $erros[] = "Nome invalido ou não preenchido"; 
    }
 
    if (empty($_POST["email"]) || (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) ) {
        $erros[] = "Email invalido ou não preenchido";
    }
 
    if (empty($_POST["senha"])) {
        $erros[] = "Senha é obrigatória";
    }
 
    if (!empty($erros)) {
        
        foreach ($erros as $erro) {
            echo "<li>$erro</li>";
        }
    } else {
        echo "<p>Nome: " . htmlspecialchars($_POST["nome"]) . "</p>";
        echo "<p>Email: " . htmlspecialchars($_POST["email"]) . "</p>";
        echo "<p>Senha: " . htmlspecialchars($_POST["senha"]) . "</p>";
    }
}
?>
</body>
</html>