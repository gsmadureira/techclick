<?php
require_once 'config.php';

// Função auxiliar para verificar saldo atual
function getSaldoAtual($conn, $id_produto) {
    $sql = "SELECT quantidade FROM estoque WHERE id_produto = {$id_produto}";
    $res = $conn->query($sql);
    if ($res && $res->num_rows > 0) {
        return (int)$res->fetch_object()->quantidade;
    }
    return 0;
}

$acao = $_REQUEST["acao"];

try {
    // Inicia uma transação segura. Nada é salvo definitivo até o $conn->commit()
    $conn->begin_transaction();

    switch($acao){

        case "cadastrar":
            $id_produto  = (int)$_POST["id_produto"];
            $tipo        = $conn->real_escape_string($_POST["tipo"]);
            $quantidade  = (int)$_POST["quantidade"]; 
            $observacoes = $conn->real_escape_string($_POST["observacoes"]); 

            // Validação de Saída vs Saldo
            if ($tipo == 'SAIDA') {
                $saldo_atual = getSaldoAtual($conn, $id_produto);
                if ($quantidade > $saldo_atual) {
                    throw new Exception("Estoque insuficiente! Você tentou remover {$quantidade}, mas só existem {$saldo_atual} unidades.");
                }
                $qtd_estoque = -$quantidade; // Subtrai
            } else {
                $qtd_estoque = $quantidade; // Soma
            }
            
            // 1. Inserir Movimentação
            $sql_mov = "INSERT INTO movimentacoes (id_produto, tipo, quantidade, observacoes, data_movimento) 
                        VALUES ({$id_produto}, '{$tipo}', {$quantidade}, '{$observacoes}', NOW())";
            if (!$conn->query($sql_mov)) {
                throw new Exception("Erro ao registrar movimentação.");
            }

            // 2. Atualizar Estoque (UPSERT: Insere se não existe, Atualiza se existe)
            // IMPORTANTE: A tabela estoque deve ter uma chave UNIQUE ou PRIMARY KEY no campo id_produto
            $sql_est = "INSERT INTO estoque (id_produto, quantidade) VALUES ({$id_produto}, {$qtd_estoque}) 
                        ON DUPLICATE KEY UPDATE quantidade = quantidade + ({$qtd_estoque})";
            
            if (!$conn->query($sql_est)) {
                throw new Exception("Erro ao atualizar o saldo do estoque.");
            }

            $msg_sucesso = "Movimentação registrada com sucesso!";
            break;


        case "editar":
            $id_movimento = (int)$_POST["id_movimento"];
            $id_produto   = (int)$_POST["id_produto"];
            $tipo_novo    = $conn->real_escape_string($_POST["tipo"]);
            $qtd_nova     = (int)$_POST["quantidade"];
            $obs_nova     = $conn->real_escape_string($_POST["observacoes"]);

            // 1. Buscar dados antigos para reverter
            $sql_antigo = "SELECT id_produto, tipo, quantidade FROM movimentacoes WHERE id_movimento = {$id_movimento}";
            $res_antigo = $conn->query($sql_antigo);
            if (!$res_antigo || $res_antigo->num_rows == 0) throw new Exception("Movimentação original não encontrada.");
            $dados_antigos = $res_antigo->fetch_object();

            // 2. Reverter o estoque antigo
            // Se era ENTRADA, agora subtraímos. Se era SAIDA, agora somamos.
            $reversao = ($dados_antigos->tipo == 'ENTRADA') ? -$dados_antigos->quantidade : $dados_antigos->quantidade;
            
            // Verifica se a reversão vai deixar o estoque negativo (ex: apagar uma entrada que já foi vendida)
            $saldo_antes_reversao = getSaldoAtual($conn, $dados_antigos->id_produto);
            if (($saldo_antes_reversao + $reversao) < 0) {
                throw new Exception("Não é possível editar: A reversão desta operação deixaria o estoque negativo.");
            }

            $sql_rev = "UPDATE estoque SET quantidade = quantidade + ({$reversao}) WHERE id_produto = {$dados_antigos->id_produto}";
            if (!$conn->query($sql_rev)) throw new Exception("Erro ao reverter estoque antigo.");

            // 3. Aplicar a nova movimentação
            $ajuste_novo = ($tipo_novo == 'ENTRADA') ? $qtd_nova : -$qtd_nova;

            // Verifica saldo para a nova operação (se for saída)
            // Nota: O saldo agora já considera a reversão feita acima
            if ($tipo_novo == 'SAIDA') {
                $saldo_pos_reversao = getSaldoAtual($conn, $id_produto);
                if ($qtd_nova > $saldo_pos_reversao) {
                    throw new Exception("Novo valor de saída ({$qtd_nova}) é maior que o saldo disponível ({$saldo_pos_reversao}).");
                }
            }

            $sql_aplica = "UPDATE estoque SET quantidade = quantidade + ({$ajuste_novo}) WHERE id_produto = {$id_produto}";
            if (!$conn->query($sql_aplica)) throw new Exception("Erro ao atualizar estoque com novos valores.");

            // 4. Atualizar registro da movimentação
            $sql_update = "UPDATE movimentacoes SET 
                           id_produto = {$id_produto}, tipo = '{$tipo_novo}', 
                           quantidade = {$qtd_nova}, observacoes = '{$obs_nova}' 
                           WHERE id_movimento = {$id_movimento}";
            if (!$conn->query($sql_update)) throw new Exception("Erro ao salvar dados da movimentação.");

            $msg_sucesso = "Registro atualizado com sucesso!";
            break;


        case "excluir":
            $id_movimento = (int)$_REQUEST["id_movimento"];

            // 1. Buscar dados para reverter
            $sql_antigo = "SELECT id_produto, tipo, quantidade FROM movimentacoes WHERE id_movimento = {$id_movimento}";
            $res_antigo = $conn->query($sql_antigo);
            if (!$res_antigo || $res_antigo->num_rows == 0) throw new Exception("Registro não encontrado.");
            $dados = $res_antigo->fetch_object();

            // 2. Calcular reversão
            $reversao = ($dados->tipo == 'ENTRADA') ? -$dados->quantidade : $dados->quantidade;

            // 3. Verificar se reversão é possível
            $saldo_atual = getSaldoAtual($conn, $dados->id_produto);
            if (($saldo_atual + $reversao) < 0) {
                throw new Exception("Impossível excluir: O estoque ficaria negativo (provavelmente estes itens já foram vendidos).");
            }

            // 4. Atualizar Estoque
            $sql_est = "UPDATE estoque SET quantidade = quantidade + ({$reversao}) WHERE id_produto = {$dados->id_produto}";
            if (!$conn->query($sql_est)) throw new Exception("Erro ao atualizar estoque.");

            // 5. Deletar Movimentação
            $sql_del = "DELETE FROM movimentacoes WHERE id_movimento = {$id_movimento}";
            if (!$conn->query($sql_del)) throw new Exception("Erro ao excluir registro.");

            $msg_sucesso = "Movimentação excluída e estoque estornado!";
            break;
    }

    // Se chegou até aqui, tudo deu certo. Confirma as alterações no banco.
    $conn->commit();
    print "<script>alert('{$msg_sucesso}');</script>";
    print "<script>location.href='index.php?page=listar-movimentacao';</script>";

} catch (Exception $e) {
    // Se deu erro em qualquer etapa, desfaz tudo (Rollback)
    $conn->rollback();
    print "<script>alert('ERRO: " . addslashes($e->getMessage()) . "');</script>";
    
    // Redireciona de volta conforme a ação
    if ($acao == 'cadastrar') {
        print "<script>location.href='index.php?page=cadastrar-movimentacao';</script>";
    } else {
        print "<script>location.href='index.php?page=listar-movimentacao';</script>";
    }
}
?>