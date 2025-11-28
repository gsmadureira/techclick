<h1 class="page-title mb-4">Nova Categoria de Hardware</h1>

<div class="card shadow-sm p-4 form-card border-0">
    <form action="?page=salvar-categoria" method="POST">
        <input type="hidden" name="acao" value="cadastrar">

        <div class="mb-3">
            <label class="form-label fw-bold text-secondary">Nome da Categoria</label>
            
            <input type="text" 
                   name="nome_categoria" 
                   class="form-control form-control-lg" 
                   placeholder="Ex: Processadores, Placas de Vídeo, SSDs..." 
                   required>
                   
            <div class="form-text">
                Crie categorias para organizar melhor o inventário de peças.
            </div>
        </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary px-5 fw-bold">
                Salvar Categoria
            </button>
        </div>
    </form>
</div>