<?php
require_once 'config.php';

$id_movimento = $conn->real_escape_string($_REQUEST["id_movimento"] ?? 0);
$sql_mov = "SELECT id_movimento, id_produto, tipo, quantidade, observacoes FROM movimentacoes WHERE id_movimento = {$id_movimento}";
$res_mov = $conn->query($sql_mov);

if ($res_mov->num_rows == 0) {
    print "<div class='alert alert-danger'>Movimentação não encontrada.</div>";
    print "<script>setTimeout(function(){ location.href='index.php?page=listar-movimentacao'; }, 2000);</script>";
    exit;
}

$dados_mov = $res_mov->fetch_object();
$sql_produtos = "SELECT id_produto, nome_produto, unidade_produto FROM produto ORDER BY nome_produto ASC";
$res_produtos = $conn->query($sql_produtos);
?>

<h1 class="page-title mb-4">
    Editar Movimentação 
    <small class="text-muted fw-light fs-5">#<?= $dados_mov->id_movimento ?></small>
</h1>

<div class="card shadow-sm p-4 form-card border-0">
    <form action="?page=salvar-movimentacao" method="POST">
        <input type="hidden" name="acao" value="editar">
        <input type="hidden" name="id_movimento" value="<?= $dados_mov->id_movimento ?>">
        
        <div class="mb-4">
            <label class="form-label fw-bold text-secondary">Hardware / Componente</label>
            <select name="id_produto" class="form-select form-select-lg" required>
                <option value="">Selecione o Produto</option>
                <?php
                if ($res_produtos->num_rows > 0) {
                    while($row_prod = $res_produtos->fetch_object()) {
                        $selected = ($row_prod->id_produto == $dados_mov->id_produto) ? 'selected' : '';
                        print "<option value='{$row_prod->id_produto}' {$selected}>{$row_prod->nome_produto} ({$row_prod->unidade_produto})</option>";
                    }
                } else {
                    print "<option value='' disabled>Nenhum produto cadastrado.</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold text-secondary">Tipo de Operação</label>
                <select name="tipo" class="form-select form-select-lg" required>
                    <option value="ENTRADA" <?= ($dados_mov->tipo == 'ENTRADA') ? 'selected' : '' ?>>⬆ Entrada (Adicionar)</option>
                    <option value="SAIDA" <?= ($dados_mov->tipo == 'SAIDA') ? 'selected' : '' ?>>⬇ Saída (Remover)</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold text-secondary">Quantidade</label>
                <input type="number" 
                       name="quantidade" 
                       class="form-control form-control-lg" 
                       min="1" 
                       required 
                       value="<?= $dados_mov->quantidade ?>">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold text-secondary">Observações / Motivo</label>
            <textarea name="observacoes" 
                      class="form-control" 
                      rows="3" 
                      placeholder="Justifique a alteração..."><?= $dados_mov->observacoes ?></textarea>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="?page=listar-movimentacao" class="btn btn-outline-secondary px-4">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary px-4 fw-bold">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>