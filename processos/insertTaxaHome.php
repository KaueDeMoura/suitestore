<?php
include "../conexao.php";

$produto = $_GET["selectProduto"];

$query = $myPDO->prepare("SELECT c.taxa
FROM public.produtos p
JOIN public.categorias c ON p.categoria_nomecat = c.nomecat
WHERE nomeprod = :produto");

$data = [":produto" => $produto];
$query->execute($data);

$registros = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($registros as $option) {
    echo $option["taxa"];
}


