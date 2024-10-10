<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/svg+xml" href="./images/icon.png">
  <title>Suite Store | Home</title>
  <link rel="stylesheet" href="./css/home.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

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
      <select name="selectProduto" id="selectProduto" method="POST" action="index.php" class="selectProduto" required>
          
          <option value="" disabled selected>Produto</option>
          <?php
          require_once "conexao.php";
          $query = $myPDO->query("SELECT nomeprod FROM public.produtos ORDER BY nomeprod;");
          $registros = $query->fetchAll(PDO::FETCH_ASSOC);
          foreach ($registros as $option) {
              $string_home = implode(",", $option); ?>
            <option value="<?php echo $option["nomeprod"]; ?>">
              <?php echo $option["nomeprod"]; ?></option>
        <?php
          }
          ?>
        </select>
        <br />
        <input type="number" name="Quantidade" placeholder="Quantidade" id="inputQuantidade" class="qtde" required />

        <!-- Taxa -->
          <input type="number" name="Taxa" placeholder="Taxa" id="Taxa" readonly />

          <!-- Preco-->
          <input type="text" name="Precound" placeholder="Preco" id="Preco" readonly/>

        <br />
        <p class="errorMsg">Por favor insira uma Quantidade Valida</p>
        <button class="addProduto" id="addProduto" name="addProduto">Adcicionar produto</button>
        <?php

  ?>
        <p></p>
      </form>
    </div>


    <div class="divisao"></div>

    <div class="separar">
      <div class="carrt">
      <h1>Carrinho</h1>
      <span class="material-symbols-outlined">
        shopping_cart
        </span>
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

            $consulta = $myPDO->query("SELECT id, total, taxa, precound, quantidade, produto_nomeprod, amount
                                        FROM public.carrinho;;");
            while ($exibe = $consulta->fetch(PDO::FETCH_ASSOC)) {
                echo "
                  <tr>
                  <td>$exibe[produto_nomeprod]</td>
                  <td>$exibe[precound]</td>
                  <td>$exibe[quantidade]</td>
                  <td>R$$exibe[amount]</td>
                  
                  <td>
                  <a href='../processos/excluircarrinho.php?id=$exibe[id]' 
                  onclick=\"return confirm('Quer Realmente excluir etsa categoria?')\"
                  style='
                  color: black;
                  text-decoration: none;
                  background-color: #6a65fe;
                  border: none;
                  font-size: 15px;
                  border-radius: 5px;
                  width: 90%;
                        height: 25px;
                        margin-left: 5px;
                        display: inline;
                        font-weight: bold;
                        '                        
                        >Deletar</a>
                        </td>
                        </tr>
                        ";
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
  <hr />

  <?php
  require_once "conexao.php";


  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $erros = [];

  
    if (empty($_POST["Quantidade"]) || $_POST["Quantidade"] < 0  ) {
        $erros[] = "Quantidade invalida";
    }
  
    if (!empty($erros)) {
        
        foreach ($erros as $erro) {
            echo "<li>$erro</li>";
        }
    } else {




      if (!empty($_POST)) {
        $Quantidade = $_POST["Quantidade"];
        $nomeprodqtde = $_POST["selectProduto"];

        $qtdeProd = $myPDO->prepare("SELECT quantidade
                                      FROM produtos 
                                      WHERE nomeprod = :nomeprodqtde");
    $dataProd = [":nomeprodqtde" => $nomeprodqtde];

        $qtdeCarr = $myPDO->prepare("SELECT COALESCE(SUM(quantidade), 0) as caraio
                                      FROM carrinho 
                                      WHERE produto_nomeprod = :nomeprodqtdee");
    $dataCarr = [":nomeprodqtdee" => $nomeprodqtde];

    

    if ($qtdeProd->execute($dataProd)){
    foreach ($qtdeProd as $option) {
        $qtdeEstoque = $option["quantidade"]; // imprimiu a quantidade do Produto no estoque
        // echo "$qtdeEstoque<br>";
    }

    }
    if ($qtdeCarr->execute($dataCarr)){
      foreach ($qtdeCarr as $option){
       $qtdeCarrinho = $option["caraio"];
        // echo $qtdeCarrinho;
      }
      // echo "<br>$qtdeEstoque<br>";
      $soma = $Quantidade + $qtdeCarrinho;
      $selecao = $qtdeEstoque - $qtdeCarrinho;
      if($soma > $qtdeEstoque){
        echo "<script>alert('Voce pode selecionar no maximo $selecao deste produto')</script>";
      }else {



        if (!empty($_POST)) {
          $selectProduto = $_POST["selectProduto"];
          $Precound = $_POST["Precound"];
          $Quantidade = $_POST["Quantidade"];
          
    
          if (isset($Quantidade) && isset($Precound)) {
            
              $Precound = floatval($Precound);
              $Quantidade = intval($Quantidade);
  
              $total = $Precound * $Quantidade;
              $valorProduto = $total; //valor total do que foi adicionado
    
              //Calcular taxa
              $query = $myPDO->prepare("SELECT c.taxa 
                  FROM public.produtos p 
                  JOIN public.categorias c 
                  ON p.categoria_nomecat = c.nomecat 
                  WHERE nomeprod = :produto");
              $data = [":produto" => $selectProduto];
              $query->execute($data);
    
              foreach ($query as $option) {
                  $taxaselect = $option["taxa"];
              }
    
              $valorTaxa = $valorProduto * $taxaselect;
              $valorTaxaDivisao = $valorTaxa / 100;
              //echo $valorTaxaDivisao; // Imprime apenas o valor da taxa aplicada
    
              $calctotal = $valorProduto + $valorTaxaDivisao;
              $totalfinal = $calctotal;
              //echo $totalfinal;
    
              try {
                  $msql = "INSERT INTO public.carrinho(
              quantidade,     produto_nomeprod, precound,     amount,               taxa,                 total)
      VALUES ('$Quantidade', '$selectProduto', '$Precound', '$valorProduto', '$valorTaxaDivisao',     '$totalfinal')";
    
                  $stm = $myPDO->prepare($msql);
    
                  if ($stm->execute()) {
                      echo "<meta http-equiv='refresh' content='0'>";
                  }
              } catch (\Exception $e) {
                  echo "<br>";
                  echo "Falha ao adicionar: " . $e->getMessage();
              }
          } else {
              echo "Dados incompletos.";
          }
      }
        
      die();
      
      }
      die();
    }




        
      }
  }
  }
  
      


  // Taxa
  $querySumTaxa = $myPDO->query("SELECT SUM(taxa) AS total_taxa FROM carrinho")->fetchAll();
  foreach ($querySumTaxa as $rowTotalTaxa) {
  }
  $totaltaxa = $rowTotalTaxa["total_taxa"];

  ////////////
  // Total //
  ///////////
  $querySumTotal = $myPDO->query("SELECT SUM(total) AS total_total FROM carrinho")->fetchAll();
  foreach ($querySumTotal as $rowTotal) {
  }
  $totaltotal = $rowTotal["total_total"];
  ?>

  <script>

  //Total
  var totaltotal = <?echo $totaltotal;?>;
  window.addEventListener("load", () => {
  var inputTotal = document.querySelector('.inputValorTotal');
  inputTotal.value = `R$${totaltotal.toFixed(2)}` ;
  });

  //Taxa
  var totaltaxa = <?echo $totaltaxa;?>;
  window.addEventListener("load", () => {
  var inputTaxa = document.querySelector('.inputTaxaTotal');
  inputTaxa.value = `R$${totaltaxa.toFixed(2)}` ;
  });

  </script>


</body>

</html>