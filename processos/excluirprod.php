<?php

    if(!empty($_GET['id'])) {
        include_once '../conexao.php';
        try{
        $id = $_GET['id'];

        $sqlSelect = "SELECT *  FROM public.produtos WHERE id=$id";

        $result = $myPDO->query($sqlSelect);

            $sqlDelete = "DELETE FROM public.produtos WHERE id=$id";
            $resultDelete = $myPDO->query($sqlDelete);

            header("Location: ../pages/produtos.php");
           exit;
    } catch(Exception $e){
        echo "<script>alert('Voce nao pode excluir este produto, pois existe este produto no carrinho, exclua o produto do carrinho primeiro')</script>";
        echo "<script>window.location = '../pages/produtos.php'</script>";
    }
} else{
    echo "Erro ao excluir produto selecionado";
}
?>