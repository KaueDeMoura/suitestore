<?php
session_start();

// Isso que Gera o token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once '../conexao.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $erros = [];

    // Isso que verifica o token CSRF
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $erros[] = "Token CSRF inválido.";
    }

    
    $categoria = htmlspecialchars(filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_STRING), ENT_QUOTES, 'UTF-8');
    $taxa = htmlspecialchars(filter_input(INPUT_POST, 'taxa', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION), ENT_QUOTES, 'UTF-8');

    if (empty($categoria) || !preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $categoria)) {
        $erros[] = "Nome de categoria inválido ou não preenchido";
    }

    if (empty($taxa) || (!preg_match("/^\d+(\.\d{1,2})?$/", $taxa)) || $taxa > 100 || $taxa < 0) {
        $erros[] = "Taxa inválida ou não preenchida";
    }

    if (!empty($erros)) {
        foreach ($erros as $erro) {
            echo "<li>$erro</li>";
        }
    } else {
        try {
            $sql = "INSERT INTO public.categorias (nomecat, taxa) VALUES (:categoria, :taxa)";
            $stmt = $myPDO->prepare($sql);
            $dados = [
                ':categoria' => $categoria,
                ':taxa' => $taxa
            ];

            if ($stmt->execute($dados)) {
                echo "<meta http-equiv='refresh' content='0'>";
            }
        } catch (\Exception $e) {
            echo '<br>Falha ao adicionar: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/svg+xml" href="../images/categoria.png" />
  <title>Suite Store | Categorias</title>
  <link rel="stylesheet" href="../css/categorias.css" />
</head>
<body>
<header>
    <a href="../home.php" class="header">Suite Store</a>
    <a href="../home.php">Compras</a>
    <a href="./produtos.php">Produtos</a>
    <a class="cor" href="./categorias.php">Categorias</a>
    <a href="./historico.php">Historico</a>
</header>

<div class="pagina">
    <div class="separar">
        <form action="categorias.php" id="formCategoria" method="POST">
            <!--CSRF token -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <!--CSRF token -->
            <input type="text" name="categoria" placeholder="Nome da Categoria (Apenas Letras)" id="inputCategoria" class="qtde" />
            <input type="text" name="taxa" placeholder="Taxa (Max 100%)" id="inputTaxa" class="taxa" />
            <p class="errorMsg error-hidden">Preencha corretamente Nome e Taxa</p>
            <br />
            <button class="inputBtn" type="submit" id="cadastrar" name="Adicionar" value="Adicionar">Adicionar Categoria</button>
        </form>
    </div>

    <div class="divisao"></div>
    <div class="separar">
        <div class="tabela">
            <table id="tabelaCategorias">
                <tr>
                    <th>Código</th>
                    <th>Categoria</th>
                    <th>Taxa (%)</th>
                    <th>Ação</th>
                </tr>
                <tbody id="listaCategoriasBody">
                <?php
                $consulta = $myPDO->query('SELECT * FROM public.categorias ORDER BY id ASC');
                while ($exibe = $consulta->fetch(PDO::FETCH_ASSOC)) {
                    echo "
                    <tr>
                    <td>" . htmlspecialchars($exibe['id'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($exibe['nomecat'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($exibe['taxa'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>
                      <a href='../processos/excluircat.php?id=" . htmlspecialchars($exibe['id'], ENT_QUOTES, 'UTF-8') . "'
                      class='btncat' onclick=\"return confirm('Quer realmente excluir esta categoria?')\">Deletar</a>
                    </td>
                    </tr>
                    ";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
