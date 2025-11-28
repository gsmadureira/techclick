<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title m-0">Categorias de Hardware</h1>
    <a href="?page=cadastrar-categoria" class="btn btn-primary fw-bold">
        + Nova Categoria
    </a>
</div>

<?php
require_once 'config.php';

$sql = "SELECT * FROM categoria ORDER BY nome_categoria ASC";
$res = $conn->query($sql);
$qtd = $res->num_rows;

if($qtd > 0){
    echo "<div class='card shadow-sm border-0 table-card'>";
    echo "<div class='card-body p-0'>"; // p-0 remove o espaçamento para a tabela colar nas bordas
    echo "<div class='table-responsive'>";
    
    echo "<table class='table table-hover align-middle mb-0'>";
    
    // Cabeçalho
    echo "<thead>
            <tr>
                <th class='ps-4' style='width: 80px;'>ID</th>
                <th>Nome da Categoria</th>
                <th class='text-end pe-4' style='width: 200px;'>Gerenciar</th>
            </tr>
          </thead>";
    
    echo "<tbody>";

    while($row = $res->fetch_object()){
        echo "<tr>";
        
        // ID com cor secundária
        echo "<td class='ps-4 fw-bold text-secondary'>#{$row->id_categoria}</td>";
        
        // Nome da Categoria em negrito
        echo "<td class='fw-semibold text-dark'>{$row->nome_categoria}</td>";
        
        // Botões de Ação alinhados à direita
        echo "<td class='text-end pe-4'>
                <a href='index.php?page=editar-categoria&id_categoria={$row->id_categoria}' 
                   class='btn btn-sm btn-edit me-2' title='Editar'>
                   Editar
                </a>

                <a href='index.php?page=salvar-categoria&acao=excluir&id_categoria={$row->id_categoria}'
                   class='btn btn-sm btn-delete'
                   onclick=\"return confirm('Tem certeza que deseja excluir a categoria {$row->nome_categoria}? Isso pode afetar produtos vinculados!');\" 
                   title='Excluir'>
                   Excluir
                </a>
              </td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    echo "</div>"; // fecha table-responsive
    echo "</div>"; // fecha card-body
    echo "</div>"; // fecha card

    // Contador no rodapé
    echo "<div class='mt-3 text-end text-muted small'>
            Total de categorias: <strong>{$qtd}</strong>
          </div>";

} else {
    // Empty State (Estado Vazio)
    echo "<div class='alert alert-light border shadow-sm text-center py-5'>
            <h4 class='text-muted'>Nenhuma categoria encontrada</h4>
            <p class='mb-4'>Cadastre categorias para organizar seus produtos de hardware.</p>
            <a href='?page=cadastrar-categoria' class='btn btn-outline-primary'>
                Cadastrar Primeira Categoria
            </a>
          </div>";
}

// Boa prática: fechar a conexão se não for usada mais abaixo, 
// mas em alguns frameworks simples isso é feito no final da página index.
// $conn->close(); 
?>