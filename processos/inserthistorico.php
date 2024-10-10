<?php
require_once '../conexao.php';
 
if (!empty($_GET['concluir'])) {
    include_once '../conexao.php';
    
 
    try {
        $sql_id = "SELECT MAX(id_compra) AS ultimo_id_compra FROM historico";
        $stmt_id = $myPDO->prepare($sql_id);
        $stmt_id->execute();
        $resultado = $stmt_id->fetch(PDO::FETCH_ASSOC);

 
        $ultimo_id_compra = $resultado['ultimo_id_compra'] ? $resultado['ultimo_id_compra'] : 0;
        $novo_id_compra = $ultimo_id_compra + 1;
 
        $sql = "INSERT INTO historico(produto_nomeprod, precound, quantidade, total, taxa, amount, id_compra, date)
                SELECT produto_nomeprod, precound, quantidade, total, taxa, amount, :id_compra, now()
                FROM carrinho";
        $stmt = $myPDO->prepare($sql);
        $stmt->bindParam(':id_compra', $novo_id_compra, PDO::PARAM_INT);
 
        if ($stmt->execute()) {

            $sql_sum_qtde = "SELECT c.produto_nomeprod, sum(c.quantidade) as qtde, p.quantidade
                            FROM carrinho c
                            inner join produtos p on c.produto_nomeprod = p.nomeprod
                            GROUP BY c.produto_nomeprod, p.quantidade;";
            $stmt_sum_qtde = $myPDO->prepare($sql_sum_qtde);
            $stmt_sum_qtde->execute();
            $result_sum_qtde = $stmt_sum_qtde->fetchALL(PDO::FETCH_ASSOC);

            foreach ($result_sum_qtde as $option) {
                $teste = $option['quantidade'] - $option['qtde'];
                $nomeprodalt = $option['produto_nomeprod'];

                $sql_update = "UPDATE produtos
                                SET quantidade = $teste
                                where nomeprod = '$nomeprodalt'";
                $stmt_update = $myPDO->prepare($sql_update);
                $stmt_update->execute();
            }
            $sqlex = "TRUNCATE TABLE carrinho";
            $stmtex = $myPDO->prepare($sqlex);
            if ($stmtex->execute()) {
                header("Location: ../index.home");
            } else {
                echo "Falha ao limpar o carrinho";
            }
        } else {
            echo "Falha ao adicionar itens ao histórico";
        }
        
    } catch (\Exception $e) {
        echo '<br>';
        echo "Falha ao adicionar: " . $e->getMessage();
    }
}

if (!empty($_GET['cancelar'])) {
    include_once '../conexao.php';
    
    try {
        $sqll = "TRUNCATE TABLE carrinho";
        $stmtt = $myPDO->prepare($sqll);
        
        if ($stmtt->execute()) {
            header("Location: ../index.home");
        }
        
    } catch (\Exception $e) {
        echo '<br>';
        echo "Falha ao cancelar: " . $e->getMessage();
    }
}
die();
// diminuir valores diferentes e ver se funciona
            // fazer string e separar os itens
// selecionar os itens da string e colocar no update 
// dar update
?>