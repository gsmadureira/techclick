<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title m-0">Catálogo de Hardware</h1>
    <a href="?page=cadastrar-produto" class="btn btn-primary fw-bold">
        + Novo Produto
    </a>
</div>

<?php
require_once 'config.php';

// Fiz um LEFT JOIN para trazer o nome da categoria junto com o produto
$sql = "SELECT p.*, c.nome_categoria 
        FROM produto p 
        LEFT JOIN categoria c ON p.id_categoria = c.id_categoria 
        ORDER BY p.nome_produto ASC";

$res = $conn->query($sql);
$qtd = $res->num_rows;

if($qtd > 0){
    echo "<div class='card shadow-sm border-0 table-card'>";
    echo "<div class='card-body p-0'>"; // Sem padding para colar nas bordas
    echo "<div class='table-responsive'>";
    
    echo "<table class='table table-hover align-middle mb-0'>";
    
    // Cabeçalho
    echo "<thead class='bg-light'>
            <tr>
                <th class='ps-4'>Produto / Modelo</th>
                <th>Categoria</th>
                <th>Preço Unitário</th>
                <th class='text-center'>Estoque Min/Max</th>
                <th class='text-end pe-4' style='width: 200px;'>Gerenciar</th>
            </tr>
          </thead>";
    
    echo "<tbody>";

    while($row = $res->fetch_object()) {
        // Formatação do preço
        $preco = number_format($row->preco_produto, 2, ',', '.');
        
        // Tratamento caso a categoria tenha sido excluída
        $categoria = $row->nome_categoria ? $row->nome_categoria : "<span class='text-muted fst-italic'>Sem Categoria</span>";

        echo "<tr>";
        
        // Nome e Unidade
        echo "<td class='ps-4'>
                <div class='fw-bold text-dark'>{$row->nome_produto}</div>
                <small class='text-muted'>Unidade: {$row->unidade_produto}</small>
              </td>";
        
        // Categoria
        echo "<td><span class='badge bg-light text-secondary border'>{$categoria}</span></td>";
        
        // Preço
        echo "<td class='fw-bold text-primary'>R$ {$preco}</td>";
        
        // Estoque Mínimo/Máximo (Informativo)
        echo "<td class='text-center text-secondary'>
                <small>{$row->estoque_minimo} / {$row->estoque_maximo}</small>
              </td>";

        // Botões
        echo "<td class='text-end pe-4'>
                <a href='index.php?page=editar-produto&id_produto={$row->id_produto}' 
                   class='btn btn-sm btn-edit me-2' title='Editar Detalhes'>
                   Editar
                </a>

                <a href='index.php?page=salvar-produto&acao=excluir&id_produto={$row->id_produto}'
                   class='btn btn-sm btn-delete'
                   onclick=\"return confirm('Tem certeza que deseja excluir o produto {$row->nome_produto}? O histórico de movimentações também pode ser perdido.');\"
                   title='Excluir Produto'>
                   Excluir
                </a>
              </td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
    echo "</div>"; // fecha responsive
    echo "</div></div>"; // fecha card

    // Rodapé
    echo "<div class='mt-3 text-end text-muted small'>
            Total de produtos cadastrados: <strong>{$qtd}</strong>
          </div>";

} else {
    // Empty State
    echo "<div class='alert alert-light border shadow-sm text-center py-5'>
            <h4 class='text-muted'>Nenhum hardware cadastrado</h4>
            <p class='mb-4'>Comece cadastrando os produtos para gerenciar o estoque.</p>
            <a href='?page=cadastrar-produto' class='btn btn-primary'>
                Cadastrar Primeiro Produto
            </a>
          </div>";
}

$conn->close();
?>