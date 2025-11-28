<h1 class="page-title mb-4">Posição de Estoque Atual</h1>

<?php
// Adicionei 'p.estoque_minimo' na consulta para podermos avisar quando estiver acabando
$sql = "SELECT 
    COALESCE(e.quantidade, 0) AS quantidade_atual, 
    p.nome_produto, 
    p.unidade_produto, 
    p.preco_produto, 
    p.estoque_minimo,
    c.nome_categoria 
FROM 
    produto p
INNER JOIN 
    categoria c ON p.id_categoria = c.id_categoria
LEFT JOIN 
    estoque e ON p.id_produto = e.id_produto
ORDER BY 
    p.nome_produto ASC;"; 

$res = $conn->query($sql);
$qtd = $res->num_rows;

if($qtd > 0){
    print "<div class='table-responsive table-card shadow-sm'>";
    print "<table class='table table-hover align-middle mb-0'>";
    
    // Cabeçalho
    print "<thead class='table-light'>
            <tr>
                <th class='ps-4'>Produto / Modelo</th>
                <th>Categoria</th>
                <th class='text-center'>Status</th>
                <th class='text-center'>Qtd.</th>
                <th>Preço Unitário</th>
            </tr>
           </thead>";
    
    print "<tbody>";
    
    while($row = $res->fetch_object()){
        
        // Lógica para definir a cor e o status do estoque
        $qtd_atual = (int)$row->quantidade_atual;
        $minimo = (int)$row->estoque_minimo;
        
        // Definição da Badge (Etiqueta Visual)
        if ($qtd_atual == 0) {
            $badge = "<span class='badge bg-danger rounded-pill'>Esgotado</span>";
            $cor_qtd = "text-danger fw-bold";
        } elseif ($qtd_atual <= $minimo) {
            $badge = "<span class='badge bg-warning text-dark rounded-pill'>Baixo Estoque</span>";
            $cor_qtd = "text-warning fw-bold";
        } else {
            $badge = "<span class='badge bg-success rounded-pill'>Disponível</span>";
            $cor_qtd = "text-dark fw-semibold";
        }

        print "<tr>";
        print "<td class='ps-4 fw-bold text-secondary'>".$row->nome_produto."</td>";
        print "<td><span class='badge bg-light text-secondary border'>".$row->nome_categoria."</span></td>";
        
        // Coluna Status (Visual)
        print "<td class='text-center'>{$badge}</td>";
        
        // Coluna Quantidade Numérica
        print "<td class='text-center {$cor_qtd}' style='font-size: 1.1rem;'>
                ".$qtd_atual." <small class='text-muted fw-normal'>".$row->unidade_produto."</small>
              </td>";
        
        // Coluna Preço
        print "<td class='fw-bold text-primary'>R$ ".number_format($row->preco_produto, 2, ',', '.')."</td>";
        print "</tr>";
    }
    
    print "</tbody></table>";
    print "</div>"; // Fecha table-card
    
    // Rodapé com totalizador simples
    print "<div class='mt-3 text-end text-muted small'>
            Total de itens cadastrados: <strong>{$qtd}</strong>
           </div>";

} else {
    // Empty State mais bonito
    print "<div class='alert alert-light text-center border shadow-sm py-5'>
            <h4 class='text-muted'>Seu estoque está vazio</h4>
            <p class='mb-4'>Nenhum produto foi encontrado no sistema.</p>
            <a href='?page=cadastrar-produto' class='btn btn-primary'>Cadastrar Primeiro Produto</a>
           </div>";
}
?>