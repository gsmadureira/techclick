<?php
require_once 'config.php';

switch ($_REQUEST["acao"]) {
    
    case "cadastrar":
        // Sanitização e Tipagem de dados para segurança
        $nome         = $conn->real_escape_string($_POST["nome_produto"]);
        $preco        = (float)$_POST["preco_produto"]; // Força ser número decimal
        $unidade      = $conn->real_escape_string($_POST["unidade_produto"]);
        $minimo       = (int)$_POST["estoque_minimo"];
        $maximo       = (int)$_POST["estoque_maximo"];
        $id_categoria = (int)$_POST["id_categoria"];

        $sql = "INSERT INTO produto (nome_produto, preco_produto, unidade_produto, estoque_minimo, estoque_maximo, id_categoria) 
                VALUES ('{$nome}', {$preco}, '{$unidade}', {$minimo}, {$maximo}, {$id_categoria})";

        $res = $conn->query($sql);

        if ($res == true) {
            print "<script>alert('Hardware cadastrado com sucesso!');</script>";
            print "<script>location.href='index.php?page=listar-produto';</script>";
        } else {
            print "<script>alert('Erro ao cadastrar: " . $conn->error . "');</script>";
            print "<script>location.href='index.php?page=listar-produto';</script>";    
        }
        break;
    
    case "editar":
        $id_produto   = (int)$_POST["id_produto"]; 
        $nome         = $conn->real_escape_string($_POST["nome_produto"]);
        $preco        = (float)$_POST["preco_produto"];   
        $unidade      = $conn->real_escape_string($_POST["unidade_produto"]);
        $minimo       = (int)$_POST["estoque_minimo"];
        $maximo       = (int)$_POST["estoque_maximo"];
        $id_categoria = (int)$_POST["id_categoria"];

        $sql = "UPDATE produto SET 
                    nome_produto = '{$nome}', 
                    preco_produto = {$preco}, 
                    unidade_produto = '{$unidade}', 
                    estoque_minimo = {$minimo}, 
                    estoque_maximo = {$maximo},     
                    id_categoria = {$id_categoria} 
                WHERE id_produto = {$id_produto}";

        $res = $conn->query($sql);

        if ($res == true) {
            print "<script>alert('Dados do produto atualizados com sucesso!');</script>";
            print "<script>location.href='index.php?page=listar-produto';</script>";
        } else {
            print "<script>alert('Não foi possível editar. Erro: " . $conn->error . "');</script>";
            print "<script>location.href='index.php?page=listar-produto';</script>";
        }
        break;

    case "excluir":
        $id_produto = (int)$_REQUEST["id_produto"];
        
        $sql = "DELETE FROM produto WHERE id_produto = {$id_produto}";
        
        // Tenta executar a exclusão
        $res = $conn->query($sql);

        if ($res == true) {
            print "<script>alert('Produto removido do catálogo!');</script>";
            print "<script>location.href='index.php?page=listar-produto';</script>";
        } else {
            // Mensagem explicativa caso falhe (geralmente por ter movimentações vinculadas)
            print "<script>alert('ERRO: Não foi possível excluir este produto. Verifique se ele possui histórico de movimentações ou vendas registradas antes de tentar apagá-lo.');</script>";
            print "<script>location.href='index.php?page=listar-produto';</script>";
        }
        break;
}
?>