<?php
include "../conexao.php";
$produto = $_GET["selectProduto"];
 
$querypreco = $myPDO->prepare("SELECT c.preco, p.quantidade
FROM public.carrinho p
JOIN public.produtos c ON p.produto_nomeprod = c.nomeprod
WHERE produto_nomeprod = :produto");
 
$datatodos = [":produto" => $produto];
$querypreco->execute($datatodos);
 
$result = $querypreco->fetchAll(PDO::FETCH_ASSOC);
error_log(print_r($result));
 
echo('<br>');
echo('<br>');
 
$precototal = 0;
$quantidadetotal = 0;
foreach($result as $teste){
    $precototal += $teste["preco"];
    $quantidadetotal += $teste["quantidade"];
};
echo("Soma de VALORES de todos os produtos no carrinho: $precototal");
echo('<br>');;
echo("Soma da QUANTIDADE de produtos no carrinho: $quantidadetotal");
 
echo('<br>');;
 
$total = $precototal * $quantidadetotal;
echo($total);
?>
 
<?php
 
require_once '../conexao.php';
 
if (!empty($_POST)){
    try{
 
        $sql = "INSERT INTO public.carrinho(
    total)
    VALUES :total;";
 
    $stmt = $myPDO->prepare($sql);
 
        $dados = array(
            ':total' => $_POST[$total]
        );
 
        if ($stmt->execute($dados)){
            echo "<meta http-equiv='refresh' content='0'>";
        }
    } catch (\Exception $e){
            echo '<br>';
        echo "Falha ao adicionar";
    }
} else{
    echo "Dados Incompletos";
 
}
die();
 
?>

