<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Formulário de Cadastro</title>
</head>
<body>
 
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $erros = [];
 
    if (empty($_POST["email"])) {
        $erros[] = "Email é obrigatório";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Formato de email inválido";
    }
 
    if (empty($_POST["senha"])) {

 
    if (!empty($erros)) {
        echo "<ul>";
        foreach ($erros as $erro) {
            echo "<li>$erro</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Nome: " . htmlspecialchars($_POST["nome"]) . "</p>";
        echo "<p>Email: " . htmlspecialchars($_POST["email"]) . "</p>";
        echo "<p>Senha: " . htmlspecialchars($_POST["senha"]) . "</p>";
    }
}
?>
 
<form action="valida.php" method="POST">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" pattern="[a-zA-ZÀ-ÿ\s]+" title="O nome deve conter apenas letras e espaços." required>
    <br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <br>
    <label for="senha">Senha:</label>
    <input type="password" id="senha" name="senha" required>
    <br>
    <label for="idade">Idade:</label>
    <input type="number" id="idade" name="idade">
    <br>
    <label for="peso">Peso:</label>
    <input type="number" id="peso" name="peso">
    <br>
    <button type="submit" name="bt_enviar">Enviar</button>
</form>
 
</body>
</html>