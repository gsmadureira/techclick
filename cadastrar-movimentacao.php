<?php
require_once 'config.php';

// Mantendo a consulta original para não quebrar a lógica do banco
$sql_produtos = "SELECT id_produto, nome_produto, unidade_produto FROM produto ORDER BY nome_produto ASC";
$res_produtos = $conn->query($sql_produtos);
?>

<h1 class="page-title mb-4">Nova Movimentação</h1>

<div class="card shadow-sm p-4 form-card border-0">
    <form action="?page=salvar-movimentacao" method="POST">
        <input type="hidden" name="acao" value="cadastrar">

        <div class="mb-4">
            <label class="form-label fw-bold text-secondary">Hardware / Componente</label>
            <select name="id_produto" class="form-select form-select-lg" required>
                <option value="">Selecione o Item...</option>
                <?php
                if ($res_produtos->num_rows > 0) {
                    while($row_prod = $res_produtos->fetch_object()) {
                        print "<option value='{$row_prod->id_produto}'>{$row_prod->nome_produto} ({$row_prod->unidade_produto})</option>";
                    }
                } else {
                    print "<option value='' disabled>Nenhum hardware cadastrado.</option>";
                }
                ?>
            </select>
            <div class="form-text">Selecione a peça que será movimentada no estoque.</div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold text-secondary">Tipo de Operação</label>
                <select name="tipo" class="form-select form-select-lg" required>
                    <option value="">Selecione...</option>
                    <option value="ENTRADA" class="text-success fw-bold">⬆ Entrada (Compra / Reposição)</option>
                    <option value="SAIDA" class="text-danger fw-bold">⬇ Saída (Venda / Baixa)</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold text-secondary">Quantidade</label>
                <input type="number" name="quantidade" class="form-control form-control-lg" min="1" placeholder="0" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold text-secondary">Observações / Referência</label>
            <textarea name="observacoes" class="form-control" rows="3" 
                      placeholder="Ex: Nota Fiscal 554, Montagem PC Gamer Cliente X, RMA..."></textarea>
        </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary px-5 fw-bold">
                Confirmar Transação
            </button>
        </div>
    </form>
</div>