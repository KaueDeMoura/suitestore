<?php

    if(!empty($_GET['id']))
    {
        include_once '../conexao.php';

        $id = $_GET['id'];

        $sqlSelect = "SELECT *  FROM public.carrinho WHERE id=$id";

        $result = $myPDO->query($sqlSelect);

            $sqlDelete = "DELETE FROM public.carrinho WHERE id=$id";
            $resultDelete = $myPDO->query($sqlDelete);

            header("Location: ../index.php");
           exit;
    }else{
        echo "Erro ao excluir produto selecionado";
    }
?>