<?php
    if(!empty($_GET['id'])) {
        include_once '../conexao.php';
        try{
        $id = $_GET['id'];

        $sqlSelect = "SELECT *  FROM public.categorias WHERE id=$id";

        $result = $myPDO->query($sqlSelect);

            $sqlDelete = "DELETE FROM public.categorias WHERE id=$id";
            $resultDelete = $myPDO->query($sqlDelete);

            header("Location: ../pages/categorias.php");
     
    }catch(Exception $e){
        echo "<script>alert('Voce nao pode excluir esta categoria, pois existe produtos dependentes dela, exclua os produtos primeiro')</script>";
        echo "<script>window.location = '../pages/categorias.php'</script>";
    }
} else{
        echo "Erro ao excluir categoria selecionada";
   }
?>