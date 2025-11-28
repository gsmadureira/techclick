<?php

    // Verifica se a ação foi definida
    switch ($_REQUEST["acao"]) {
        
        case "cadastrar":
            // Protege contra caracteres especiais e SQL Injection
            $nome = $conn->real_escape_string($_POST["nome_categoria"]);

            if(empty($nome)){
                print "<script>alert('Por favor, preencha o nome da categoria!');</script>";
                print "<script>location.href='index.php?page=cadastrar-categoria';</script>";
                exit;
            }

            $sql = "INSERT INTO categoria (nome_categoria) VALUES ('{$nome}')";

            $res = $conn->query($sql);

            if($res == true){
                print "<script>alert('Sucesso! Nova categoria cadastrada.');</script>";
                print "<script>location.href='index.php?page=listar-categoria';</script>";
            } else {
                print "<script>alert('Erro ao cadastrar. Tente novamente.');</script>";
                print "<script>location.href='index.php?page=listar-categoria';</script>";
            }
            break;


        case "editar":
            // Protege o nome e garante que o ID seja um número
            $nome = $conn->real_escape_string($_POST["nome_categoria"]);
            $id_categoria = (int)$_POST["id_categoria"];

            $sql = "UPDATE categoria SET nome_categoria = '{$nome}' WHERE id_categoria = {$id_categoria}";

            $res = $conn->query($sql);

            if($res == true){
                print "<script>alert('Categoria atualizada com sucesso!');</script>";
                print "<script>location.href='index.php?page=listar-categoria';</script>";
            } else {
                print "<script>alert('Erro ao atualizar a categoria.');</script>";
                print "<script>location.href='index.php?page=listar-categoria';</script>";
            }
            break;


        case "excluir":
            // Garante que o ID seja um número inteiro para segurança
            $id_categoria = (int)$_REQUEST["id_categoria"];

            $sql = "DELETE FROM categoria WHERE id_categoria = {$id_categoria}";

            // Tenta executar. Se falhar, geralmente é porque existem produtos vinculados
            $res = $conn->query($sql);

            if($res == true){
                print "<script>alert('Categoria excluída com sucesso!');</script>";
                print "<script>location.href='index.php?page=listar-categoria';</script>";
            } else {
                // Mensagem mais explicativa
                print "<script>alert('Não foi possível excluir! Verifique se existem produtos vinculados a esta categoria antes de tentar apagá-la.');</script>";
                print "<script>location.href='index.php?page=listar-categoria';</script>";
            }
            break;
    }

?>