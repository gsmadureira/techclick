<h1 class="page-title mb-4">Novo Hardware</h1>

<div class="card shadow-sm p-4 form-card border-0">
    <form action="?page=salvar-produto" method="POST">
        <input type="hidden" name="acao" value="cadastrar">

        <div class="mb-3">
            <label class="form-label fw-bold text-secondary">Nome do Produto / Modelo</label>
            <input type="text" 
                   name="nome_produto" 
                   class="form-control form-control-lg" 
                   placeholder="Ex: Placa de Vídeo RTX 4060, Processador Ryzen 5..." 
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
                           placeholder="0.00" 
                           required>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold text-secondary">Unidade</label>
                <input type="text" 
                       name="unidade_produto" 
                       class="form-control" 
                       placeholder="Ex: un, kit, box" 
                       value="un" 
                       required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold text-secondary">Categoria</label>
            <select name="id_categoria" class="form-select" required>
                <option value="">Selecione a Categoria...</option>
                <?php
                    // Certifique-se de que a conexão $conn existe (vinda do index.php)
                    $sql_cat = "SELECT id_categoria, nome_categoria FROM categoria ORDER BY nome_categoria";
                    $res_cat = $conn->query($sql_cat);
                    
                    if ($res_cat && $res_cat->num_rows > 0) {
                        while($row_cat = $res_cat->fetch_object()) {
                            print "<option value='{$row_cat->id_categoria}'>{$row_cat->nome_categoria}</option>";
                        }
                    } else {
                        print "<option disabled>Nenhuma categoria cadastrada</option>";
                    }
                ?>
            </select>
        </div>

        <hr class="my-4 text-muted">

        <h5 class="mb-3 text-secondary">Parâmetros de Estoque</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Estoque Mínimo (Alerta)</label>
                <input type="number" name="estoque_minimo" class="form-control" value="5">
                <div class="form-text">Quantidade mínima antes de repor.</div>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Capacidade Máxima</label>
                <input type="number" name="estoque_maximo" class="form-control" value="100">
            </div>
        </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary px-5 fw-bold">
                Salvar Produto
            </button>
        </div>
    </form>
</div>