
<!DOCTYPE html>
<html lang="pt-br">

<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <link rel="icon" type="image/svg+xml" href="../images/historico.png" />
   <title>Suite Store | Home</title>
   <link rel="stylesheet" href="../css/historico.css" />
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

</head>

<body>
<header>
    <a href="../home.php" class="header">Suite Store</a>
    <a href="../home.php">Compras</a>
    <a href="./produtos.php">Produtos</a>
    <a href="./categorias.php">Categorias</a>
    <a class="cor" href="./historico.php">Historico</a>
  </header>

   <div class="pagina">
      <div class="separar1">
         <div class="carrt">
         <h1>Historico</h1>
         <span class="material-symbols-outlined">
            history
            </span>
         </div>
         <div class="tabela">
            <table>
               <thead>
                  <tr>
                     <th class="thid">ID</th>
                     <th class="thdata">Data</th>
                     <th class="thtotal">Total</th>
                     <th class="thacao">Ação</th>
                  </tr>
               </thead>
               <tbody id="listaHistoricoBody">
               <?php
            include "../conexao.php";

            $consulta = $myPDO->query("SELECT id_compra, date, sum(total) as teste
                                       FROM historico
                                       GROUP BY id_compra, date
									   order by id_compra asc");
            while ($exibe = $consulta->fetch(PDO::FETCH_ASSOC)) {
              echo "
                  <tr>
                  <td>$exibe[id_compra]</td>
                  <td>$exibe[date]</td>
                  <td>R$$exibe[teste]</td>

                  <td>
                  <a href='historico.php?id_compra=$exibe[id_compra]'
                     style='
                     color: black; 
                     text-decoration: none; 
                     background-color: #6a65fe; 
                     border: none; font-size: 15px; 
                     border-radius: 5px; width: 90%; 
                     height: 25px; margin-left: 5px; 
                     display: inline; font-weight: bold;
                     '
                     >Ver</a>
                  </td>
                  </tr>
                        ";
                      }
                      ?>
            </tbody>
               </tbody>
            </table>
         </div>
      </div>
      <!---->

      <div class="divisao"></div>
      <!---->
      <div class="sumir">
         <div class="separar2">
            <h1>Detalhes da Compra</h1>
            <div class="tabela">
               <table id="tableHist">
                  <thead>
                     <tr>
                        <th class="thproduto">Produto</th>
                        <th class="thpreco">Preço Unidade</th>
                        <th class="thquantidade">Quantidade</th>
                        <th class="thtaxa">Taxa(%)</th>
                        <th class="thtotal">Total</th>
                     </tr>
                  </thead>
                  <tbody id="listaNovoDetalhesBody">

                  <?php
            function exibir($id_compra){
               include "../conexao.php";
             
               $consulta = $myPDO->prepare("SELECT produto_nomeprod, precound, quantidade, total, taxa
                                            FROM public.historico
                                            WHERE id_compra = $id_compra");
               $consulta->execute();
               
               while ($exibe = $consulta->fetch(PDO::FETCH_ASSOC)) {
                  echo "
                     <tr>
                        <td>$exibe[produto_nomeprod]</td>
                        <td>R$exibe[precound]</td>
                        <td>$exibe[quantidade]</td>
                        <td>$exibe[taxa]</td>
                        <td>R$$exibe[total]</td>
                     </tr>
                  ";
               }
            }
                      ?>
            </tbody>
            <?php
               if (isset($_GET['id_compra'])) {
                  $id_compra = $_GET['id_compra'];
                  exibir($id_compra);
               }
            ?>
               </table>

               <div class="infos">
                  <label for="inputTaxaTotal">Taxa Total:</label>
                  <input type="text" id="inputTaxaTotal" name="taxa" value="R$00.00" class="inputTaxaTotal" readonly />
                  <br />
                  <label for="inputNovoValorTotal">Valor Total:</label>
                  <input type="text" id="inputNovoValorTotal" name="total" value="R$00,00" class="inputValorTotal" readonly />
                  <br />

                  </div>
            </div>
         </div>
      </div>
   </div>
   <hr />
</body>

<?php
if (isset($_GET['id_compra'])) {
   $id_compra = $_GET['id_compra'];


// Taxa
  $querySumTaxa = $myPDO->query("SELECT SUM(taxa) AS total_taxa FROM historico where id_compra = $id_compra")->fetchAll();
  foreach ($querySumTaxa as $rowTotalTaxa) {
  }
  $totaltaxa = $rowTotalTaxa["total_taxa"];


  ////////////
  // Total //
  ///////////
  $querySumTotal = $myPDO->query("SELECT SUM(total) AS total_total FROM historico where id_compra = $id_compra")->fetchAll();
  foreach ($querySumTotal as $rowTotal) {
  }
  $totaltotal = $rowTotal["total_total"];
}
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

</html>