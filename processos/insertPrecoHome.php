<?php
include "../conexao.php";

$produto = $_GET["selectProduto"];

$query = $myPDO->prepare("SELECT preco
        FROM public.produtos where nomeprod = :produto");

$data = [":produto" => $produto];
$query->execute($data);

$registros = $query->fetchAll(PDO::FETCH_ASSOC);


foreach ($registros as $option) {
    echo $option["preco"]; 
}
?>

