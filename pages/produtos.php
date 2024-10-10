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
        <input type="text" name="produto" placeholder="Nome do Produto" id="inputProduto" name="preco-unitario" class="inputcs"
          required />
        <input type="number" name="quantidade" placeholder="Quantidade" id="inputQuantidade" required />
        <input type="text" name="preco" placeholder="Preço da unidade" id="inputPreco" class="taxacs" required />
        <br />
        <select name="selectCategoria" id="Categoria" method="POST" action="produtos.php" required>
          
          <option value="" disabled selected>Categoria</option>
          <?php
        include '../conexao.php';
        $query = $myPDO->query("SELECT nomecat FROM public.categorias ORDER BY nomecat;");
        $registros = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach($registros as $option){
          ?>
            <option value="<?php echo $option['nomecat']?>">
              <?php echo $option['nomecat']?></option>
        <?php
      }
          
          ?>
        </select>
        <p class="errorMsg">Por favor, insira informações válidas!</p>
        <button type="submit">Adicionar produto</button>
        <p></p>
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

            
              while($exibe = $consulta->fetch(PDO::FETCH_ASSOC)){
                echo "
                <tr>
                <td>$exibe[id]</td>
                <td>$exibe[nomeprod]</td>
                <td>$exibe[quantidade]</td>
                <td>R$$exibe[preco]</td>
                <td>$exibe[categoria_nomecat]</td>
                <td>
                    <a href='../processos/excluirprod.php?id=$exibe[id]' 
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
      </div>
    </div>
  </div>


  <?php

require_once '../conexao.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $erros = [];


  if (empty($_POST["produto"]) || (!preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $_POST["produto"])) ) {
      $erros[] = "Nome de produto invalido ou não preenchido"; 
  }

  if (empty($_POST["preco"]) || $_POST["preco"] < 0  ) {
      $erros[] = "Taxa invalida ou não preenchida";
  }

  if (!empty($erros)) {
      
      foreach ($erros as $erro) {
          echo "<li>$erro</li>";
      }
  } else {

    if (!empty($_POST)){

      try{
  
          $msql = "INSERT INTO public.produtos(
     nomeprod, preco, quantidade, categoria_nomecat)
    VALUES (:produto, :preco, :quantidade, :selectCategoria )";
  
      $stm = $myPDO->prepare($msql);
  
          $dado = array(
              ':produto' =>        $_POST['produto'],
              ':quantidade' =>     $_POST['quantidade'],
              ':preco' =>          $_POST['preco'],
            ':selectCategoria' =>  $_POST['selectCategoria'],
  
          );
  
          if ($stm->execute($dado)){
              echo "<meta http-equiv='refresh' content='0'>";
          }
      } catch (\Exception $e){
              echo '<br>';
          echo "Falha ao adicionar";
      }
  
  } else{
   echo "Não foi possivel enviar os dados de produtos";
  }
  die();
  
  }
  die();
}


?>


</body>

</html>