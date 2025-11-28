<?php
// Mantendo a lógica de conexão e consulta original
require_once 'config.php';

$id_produto = $conn->real_escape_string($_REQUEST["id_produto"]);
$sql_produto = "SELECT * FROM produto WHERE id_produto = " . $id_produto;
$res_produto = $conn->query($sql_produto);
$row = $res_produto->fetch_object();

$sql_cat = "SELECT id_categoria, nome_categoria FROM categoria ORDER BY nome_categoria";
$res_cat = $conn->query($sql_cat);

if ($row) {
?>

<h1 class="page-title mb-4">
    Editar Hardware 
    <small class="text-muted fw-light fs-5">Ref: #<?php print $row->id_produto; ?></small>
</h1>

<div class="card shadow-sm p-4 form-card border-0">
    <form action="?page=salvar-produto" method="POST">
        <input type="hidden" name="acao" value="editar">
        <input type="hidden" name="id_produto" value="<?php print $row->id_produto; ?>">
        
        <div class="mb-3">
            <label class="form-label fw-bold text-secondary">Nome do Produto / Modelo</label>
            <input type="text" 
                   name="nome_produto" 
                   class="form-control form-control-lg" 
                   value="<?php print htmlspecialchars($row->nome_produto); ?>" 
                   required>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold text-secondary">Preço de Venda</label>
                <div class="input-group">
                    <span class="input-group-text bg-light fw-bold">R$</span>
                    <input type="number" 
                           step="0.01" 
                           name="preco_produto" 
                           class="form-control" 
                           value="<?php print number_format($row->preco_produto, 2, '.', ''); ?>" 
                           required>
                </div>
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold text-secondary">Unidade</label>
                <input type="text" 
                       name="unidade_produto" 
                       class="form-control" 
                       value="<?php print htmlspecialchars($row->unidade_produto); ?>" 
                       required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold text-secondary">Categoria</label>
            <select name="id_categoria" class="form-select" required>
                <option value="">Selecione a Categoria</option>
                <?php
                // Reseta o ponteiro do resultado das categorias caso precise reutilizar
                $res_cat->data_seek(0); 
                while($row_cat = $res_cat->fetch_object()) {
                    $selected = ($row_cat->id_categoria == $row->id_categoria) ? 'selected' : '';
                    print "<option value='{$row_cat->id_categoria}' {$selected}>{$row_cat->nome_categoria}</option>";
                }
                ?>
            </select>
        </div>

        <hr class="my-4 text-muted">
        <h5 class="mb-3 text-secondary">Configurações de Estoque</h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Estoque Mínimo (Alerta)</label>
                <input type="number" 
                       name="estoque_minimo" 
                       class="form-control" 
                       value="<?php print $row->estoque_minimo; ?>" 
                       required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Capacidade Máxima</label>
                <input type="number" 
                       name="estoque_maximo" 
                       class="form-control" 
                       value="<?php print $row->estoque_maximo; ?>" 
                       required>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="?page=listar-produto" class="btn btn-outline-secondary px-4">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary px-4 fw-bold">
                Salvar Alterações
            </button> 
        </div>
    </form>
</div>

<?php 
} else {
    print "<div class='alert alert-warning shadow-sm'>
            <h4 class='alert-heading'>Produto não encontrado!</h4>
            <p>O produto que você está tentando editar não existe ou foi excluído.</p>
            <hr>
            <a href='?page=listar-produto' class='btn btn-outline-dark btn-sm'>Voltar para a lista</a>
           </div>";
}
?>