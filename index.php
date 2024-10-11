<?php

session_start();
// Isso que Gera o token CSRF
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

// Verificar se a requisição é POST e o token CSRF é válido
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Isso que verifica o token CSRF
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF token inválido.");
      }
    
      $Quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT);
      $selectProduto =($_POST["selectProduto"]);
      $Precound =($_POST["Precound"]);
      
      $erros = [];
      
    if (empty($Quantidade) || $Quantidade < 0) {
        $erros[] = "Quantidade inválida.";
    }

    if (!empty($erros)) {
        foreach ($erros as $erro) {
            echo "<li>$erro</li>";
        }
    } else {
        require_once "conexao.php";

        $qtdeProd = $myPDO->prepare("SELECT quantidade FROM produtos WHERE nomeprod = :nomeprodqtde");
        $qtdeProd->execute([':nomeprodqtde' => $selectProduto]);

        $qtdeCarr = $myPDO->prepare("SELECT COALESCE(SUM(quantidade), 0) as carrinho_qtd FROM carrinho WHERE produto_nomeprod = :nomeprodqtdee");
        $qtdeCarr->execute([':nomeprodqtdee' => $selectProduto]);

        $qtdeEstoque = $qtdeProd->fetchColumn();
        $qtdeCarrinho = $qtdeCarr->fetchColumn();

        $soma = $Quantidade + $qtdeCarrinho;
        $selecao = $qtdeEstoque - $qtdeCarrinho;

        if ($soma > $qtdeEstoque) {
            echo "<script>alert('Você pode selecionar no máximo $selecao deste produto')</script>";
        } else {
            $valorProduto = floatval($Precound) * intval($Quantidade);

            $queryTaxa = $myPDO->prepare("SELECT c.taxa FROM public.produtos p JOIN public.categorias c ON p.categoria_nomecat = c.nomecat WHERE nomeprod = :produto");
            $queryTaxa->execute([':produto' => $selectProduto]);
            $taxaselect = $queryTaxa->fetchColumn();

            $valorTaxa = ($valorProduto * $taxaselect) / 100;
            $totalfinal = $valorProduto + $valorTaxa;

            try {
                $msql = "INSERT INTO public.carrinho (quantidade, produto_nomeprod, precound, amount, taxa, total) VALUES ('$Quantidade', '$selectProduto', '$Precound', '$valorProduto', '$valorTaxa', '$totalfinal')";
                $stm = $myPDO->prepare($msql);

                if ($stm->execute()) {
                    echo "<meta http-equiv='refresh' content='0'>";
                }
            } catch (\Exception $e) {
                echo "<br>";
                echo "Falha ao adicionar: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/svg+xml" href="./images/icon.png">
    <title>Suite Store | Home</title>
    <link rel="stylesheet" href="./css/home.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
</head>
<body>
<header>
    <a href="./home.php" class="header">Suite Store</a>
    <a class="cor" href="./home.php">Compras</a>
    <a href="./pages/produtos.php">Produtos</a>
    <a href="./pages/categorias.php">Categorias</a>
    <a href="./pages/historico.php">Historico</a>
</header>

<div class="pagina">
    <div class="separar">
        <div class="carrt">
            <h1>Selecionar Produtos</h1>
            <span class="material-symbols-outlined">inventory_2</span>
        </div>
        <form id="formHome" action="index.php" method="POST" class="input">
            <!-- Token CSRF -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['token']; ?>">
            <!-- Token CSRF -->
            <select name="selectProduto" id="selectProduto" class="selectProduto" required>
                <option value="" disabled selected>Produto</option>
                <?php
                require_once "conexao.php";
                $query = $myPDO->query("SELECT nomeprod FROM public.produtos ORDER BY nomeprod;");
                $registros = $query->fetchAll(PDO::FETCH_ASSOC);
                foreach ($registros as $option) { ?>
                    <option value="<?php echo sanitizeInput($option["nomeprod"]); ?>">
                        <?php echo sanitizeInput($option["nomeprod"]); ?></option>
                <?php } ?>
            </select>
            <br />
            <input type="number" name="Quantidade" placeholder="Quantidade" id="inputQuantidade" class="qtde" required />
            <input type="number" name="Taxa" placeholder="Taxa" id="Taxa" readonly />
            <input type="text" name="Precound" placeholder="Preco" id="Preco" readonly />
            <br />
            <p class="errorMsg">Por favor insira uma Quantidade Valida</p>
            <button class="addProduto" id="addProduto" name="addProduto">Adicionar produto</button>
        </form>
    </div>

    <div class="divisao"></div>

    <div class="separar">
        <div class="carrt">
            <h1>Carrinho</h1>
            <span class="material-symbols-outlined">shopping_cart</span>
        </div>
        <div class="tabela">
            <table>
                <tr>
                    <th>Produto</th>
                    <th>Preço unidade</th>
                    <th>Quantidade</th>
                    <th>Total</th>
                    <th>Ação</th>
                </tr>
                <tbody id="listaHomeBody">
                <?php
                include "./conexao.php";
                $consulta = $myPDO->query("SELECT id, total, taxa, precound, quantidade, produto_nomeprod, amount FROM public.carrinho;");
                while ($exibe = $consulta->fetch(PDO::FETCH_ASSOC)) {
                    echo "
                    <tr>
                        <td>" . sanitizeInput($exibe['produto_nomeprod']) . "</td>
                        <td>" . sanitizeInput($exibe['precound']) . "</td>
                        <td>" . sanitizeInput($exibe['quantidade']) . "</td>
                        <td>R$" . sanitizeInput($exibe['amount']) . "</td>
                        <td>
                            <a href='../processos/excluircarrinho.php?id=" . sanitizeInput($exibe['id']) . "' 
                                onclick=\"return confirm('Quer realmente excluir esta categoria?')\"
                                class='deletar'>
                                Deletar
                            </a>
                        </td>
                    </tr>";
                }
                ?>
                </tbody>
            </table>

            <div class="infos">
                <label>Taxa:</label>
                <input type="text" id="inputTaxaTotal" name="taxa" value="R$00.00" class="inputTaxaTotal" readonly />
                <br />
                <label>Total:</label>
                <input type="text" id="inputValorTotal" name="total" value="R$00,00" class="inputValorTotal" readonly />
                <br />
                <a href='./processos/inserthistorico.php?cancelar=cancelar' class="cancelar">Cancelar</a>
                <a href='./processos/inserthistorico.php?concluir=concluir' class="concluir">Concluir</a>
            </div>
        </div>
    </div>
</div>

<script src="./js/taxa.js"></script>
<script src="./js/preco.js"></script>

<script>
    //Total
    var totaltotal = <?php echo json_encode($totaltotal); ?>;
    window.addEventListener("load", () => {
        var inputTotal = document.querySelector('.inputValorTotal');
        inputTotal.value = `R$${totaltotal.toFixed(2)}`;
    });

    //Taxa
    var totaltaxa = <?php echo json_encode($totaltaxa); ?>;
    window.addEventListener("load", () => {
        var inputTaxa = document.querySelector('.inputTaxaTotal');
        inputTaxa.value = `R$${totaltaxa.toFixed(2)}`;
    });
</script>

</body>
</html>
