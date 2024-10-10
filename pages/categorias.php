<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/svg+xml" href="../images/categoria.png" />
  <title>Suite Store | Home</title>
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
        <input type="text" name="categoria" placeholder="Nome da Categoria (Apenas Letras)" id="inputCategoria" class="qtde" />
        <input type="text" name="taxa" placeholder="Taxa (Max 100%)" id="inputTaxa" class="taxa" />

        <p class="errorMsg error-hidden">Preencha corretamente Nome e Taxa</p>
        <br />
        <button class="inputBtn" type="submit" id="cadastrar" name="Adicionar" value="Adcicionar">
          Adicionar Categoria
        </button>
        <p></p>
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
            include '../conexao.php';

            $consulta = $myPDO->query('SELECT * FROM public.categorias ORDER BY id ASC');

            $query = "DELETE FROM public.categorias WHERE id =$";
            
              while($exibe = $consulta->fetch(PDO::FETCH_ASSOC)){
                echo "
                <tr>
                <td>$exibe[id]</td>
                <td>$exibe[nomecat]</td>
                <td>$exibe[taxa]</td>
                <td>
                  <a href='../processos/excluircat.php?id=$exibe[id]' 
                    onclick=\"return confirm('Quer Realmente excluir esta categoria?')\"
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
<img src="" >
            </tbody>
        </table>
      </div>
    </div>
  </div>
  <hr />

  <?php

require_once '../conexao.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $erros = [];


  if (empty($_POST["categoria"]) || (!preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $_POST["categoria"])) ) {
      $erros[] = "Nome de categoria invalida ou não preenchida"; 
  }

  if (empty($_POST["taxa"]) || (!preg_match("/^\d+(\.\d{1,2})?$/", $_POST["taxa"])) || $_POST["taxa"] > 100 || $_POST["taxa"] < 0  ) {
      $erros[] = "Taxa invalida ou não preenchida";
  }



  if (!empty($erros)) {
      
      foreach ($erros as $erro) {
          echo "<li>$erro</li>";
      }
  } else {

///////////
    ///////////
        ///////////
            ///////////
                ///////////
                    ///////////
                        ///////////
    if (!empty($_POST)){

      try{
  
          $sql = "INSERT INTO public.categorias(
     nomecat, taxa)
    VALUES (:categoria, :taxa)";
  
      $stmt = $myPDO->prepare($sql);
  
          $dados = array(
              ':categoria' => $_POST['categoria'],
              ':taxa' => $_POST['taxa']
          );
  
          if ($stmt->execute($dados)){
              echo "<meta http-equiv='refresh' content='0'>";
          }
      } catch (\Exception $e){
              echo '<br>';
          echo "Falha ao adicionar";
      }
  
  } else{
   echo 'Não foi possivel enviar os dados de Categoria';
  }
  die();
  }
}


?>

</body>

</html>