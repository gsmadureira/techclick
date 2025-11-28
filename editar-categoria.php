<h1 class="page-title mb-4">Editar Categoria</h1>

<?php
   // Mantendo a lógica original
   $sql = "SELECT * FROM categoria WHERE id_categoria = ".$_REQUEST["id_categoria"];
   $res = $conn->query($sql);
   $row = $res->fetch_object();
?>

<div class="card shadow-sm p-4 form-card border-0">
    <div class="card-body p-0">

        <form action="?page=salvar-categoria" method="POST">
            <input type="hidden" name="acao" value="editar">
            <input type="hidden" name="id_categoria" value="<?php print $row->id_categoria; ?>">

            <div class="mb-4">
                <label class="form-label fw-bold text-secondary">Nome da Categoria</label>
                <input 
                    type="text" 
                    class="form-control form-control-lg"
                    name="nome_categoria" 
                    value="<?php print $row->nome_categoria; ?>"
                    required
                >
                <div class="form-text">
                    Edite o nome da categoria para atualizar em todos os produtos vinculados.
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="?page=listar-categoria" class="btn btn-outline-secondary px-4">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary px-4 fw-bold">
                    Salvar Alterações
                </button>
            </div>
        </form>

    </div>
</div>