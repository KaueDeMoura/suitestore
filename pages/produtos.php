<?php
  session_start();

  // Isso que Gera o token CSRF
  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }

  require_once '../conexao.php';

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Isso que verifica o token CSRF
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
      die("CSRF token inválido.");
    }

    $produto = filter_input(INPUT_POST, 'produto', FILTER_SANITIZE_STRING);
    $quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT);
    $preco = filter_input(INPUT_POST, 'preco', FILTER_VALIDATE_FLOAT);
    $selectCategoria = htmlspecialchars($_POST['selectCategoria'], ENT_QUOTES, 'UTF-8');

    $erros = [];

    if (!$produto || !preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $produto)) {
      $erros[] = "Nome de produto inválido ou não preenchido";
    }

    if ($preco === false || $preco < 0) {
      $erros[] = "Preço inválido ou não preenchido";
    }

    if ($quantidade === false || $quantidade < 0) {
      $erros[] = "Quantidade inválida";
    }

    if (!empty($erros)) {
      foreach ($erros as $erro) {
        echo "<li>$erro</li>";
      }
    } else {
      try {
        $msql = "INSERT INTO public.produtos(nomeprod, preco, quantidade, categoria_nomecat) 
                 VALUES (:produto, :preco, :quantidade, :selectCategoria)";
        $stm = $myPDO->prepare($msql);
        $dado = [
          ':produto' => $produto,
          ':quantidade' => $quantidade,
          ':preco' => $preco,
          ':selectCategoria' => $selectCategoria,
        ];
        if ($stm->execute($dado)) {
          echo "<meta http-equiv='refresh' content='0'>";
        }
      } catch (Exception $e) {
        echo "Falha ao adicionar o produto";
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/svg+xml" href="../images/produtoi.png" />
  <title>Suite Store | Home</title>
  <link rel="stylesheet" href="../css/produtos.css" />
</head>

<body>
  <header>
    <a href="../home.php" class="header">Suite Store</a>
    <a href="../home.php">Compras</a>
    <a class="cor" href="./produtos.php">Produtos</a>
    <a href="./categorias.php">Categorias</a>
    <a href="./historico.php">Historico</a>
  </header>

  <div class="pagina">
    <div class="separar">
      <form action="produtos.php" method="POST" id="formProduto">
        <br />
        <input type="text" name="produto" placeholder="Nome do Produto" id="inputProduto" class="inputcs" required />
        <input type="number" name="quantidade" placeholder="Quantidade" id="inputQuantidade" required />
        <input type="text" name="preco" placeholder="Preço da unidade" id="inputPreco" class="taxacs" required />
        <br />
        <select name="selectCategoria" id="Categoria" required>
          <option value="" disabled selected>Categoria</option>
          <?php
            include '../conexao.php';
            $query = $myPDO->query("SELECT nomecat FROM public.categorias ORDER BY nomecat;");
            $registros = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($registros as $option) {
              echo "<option value='".htmlspecialchars($option['nomecat'], ENT_QUOTES, 'UTF-8')."'>".htmlspecialchars($option['nomecat'], ENT_QUOTES, 'UTF-8')."</option>";
            }
          ?>
        </select>
        <p class="errorMsg">Por favor, insira informações válidas!</p>
        <!--CSRF token -->
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
        <!--CSRF token -->
        <button type="submit">Adicionar produto</button>
      </form>
    </div>

    <div class="divisao"></div>
    <div class="separar">
      <div class="tabela">
        <table>
          <tr>
            <th>Código</th>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Preço unidade</th>
            <th>Categoria</th>
            <th>Ação</th>
          </tr>
          <tbody id="listaProdutosBody">
            <?php
              include '../conexao.php';
              $consulta = $myPDO->query("SELECT id, nomeprod, preco, quantidade, categoria_nomecat FROM public.produtos;");
              while ($exibe = $consulta->fetch(PDO::FETCH_ASSOC)) {
                echo "
                <tr>
                <td>".htmlspecialchars($exibe['id'], ENT_QUOTES, 'UTF-8')."</td>
                <td>".htmlspecialchars($exibe['nomeprod'], ENT_QUOTES, 'UTF-8')."</td>
                <td>".htmlspecialchars($exibe['quantidade'], ENT_QUOTES, 'UTF-8')."</td>
                <td>R$".htmlspecialchars($exibe['preco'], ENT_QUOTES, 'UTF-8')."</td>
                <td>".htmlspecialchars($exibe['categoria_nomecat'], ENT_QUOTES, 'UTF-8')."</td>
                <td>
                  <a href='../processos/excluirprod.php?id=".htmlspecialchars($exibe['id'], ENT_QUOTES, 'UTF-8')."' 
                  onclick=\"return confirm('Quer realmente excluir este produto?')\"
                  style='
                  background-color: #6a65fe; 
                  border: none; font-size: 15px; 
                  border-radius: 5px; width: 80%;
                  height: 25px; 
                  margin-left: 5px; 
                  display: flex; 
                  font-weight: bold; 
                  text-align: center; 
                  justify-content: center; 
                  align-items: center; 
                  cursor: pointer; 
                  text-decoration: none; 
                  color: black;'
                  >Deletar</a>
                </td>
                </tr>";
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
