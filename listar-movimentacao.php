<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title m-0">Histórico de Transações</h1>
    <a href="?page=cadastrar-movimentacao" class="btn btn-success fw-bold">
        + Registrar Movimentação
    </a>
</div>

<?php
require_once 'config.php';

$sql = "SELECT 
            m.id_movimento,
            m.tipo,
            m.quantidade,
            m.data_movimento,
            m.observacoes,
            p.nome_produto,
            p.unidade_produto
        FROM 
            movimentacoes m
        INNER JOIN 
            produto p ON m.id_produto = p.id_produto
        ORDER BY 
            m.data_movimento DESC"; 

$res = $conn->query($sql);
$qtd = $res->num_rows;

if($qtd > 0){
    echo "<div class='card shadow-sm border-0 table-card'>";
    echo "<div class='card-body p-0'>"; // Sem padding para a tabela encostar na borda
    echo "<div class='table-responsive'>";
    
    echo "<table class='table table-hover align-middle mb-0'>";
    
    // Cabeçalho
    echo "<thead class='bg-light'>
            <tr>
                <th class='ps-4'>Data / Hora</th>
                <th>Produto / Hardware</th>
                <th>Operação</th>
                <th>Qtd.</th>
                <th class='text-end pe-4'>Ações</th>
            </tr>
          </thead>";
    
    echo "<tbody>";
    
    while($row = $res->fetch_object()) {
        
        // Formatação de Data
        $data_formatada = date('d/m/Y', strtotime($row->data_movimento));
        $hora_formatada = date('H:i', strtotime($row->data_movimento));

        // Lógica Visual para Entrada/Saída
        if ($row->tipo == 'ENTRADA') {
            $badge = "<span class='badge bg-success bg-opacity-75 text-white shadow-sm'>⬆ Entrada</span>";
            $classe_qtd = "text-success fw-bold";
            $sinal = "+";
        } else {
            $badge = "<span class='badge bg-danger bg-opacity-75 text-white shadow-sm'>⬇ Saída</span>";
            $classe_qtd = "text-danger fw-bold";
            $sinal = "-";
        }

        // Tooltip para observações (só aparece se tiver obs)
        $info_obs = "";
        if(!empty($row->observacoes)){
            $info_obs = "<span class='ms-2 text-muted' title='Obs: {$row->observacoes}' style='cursor:help;'>ℹ️</span>";
        }

        echo "<tr>";
        
        // Coluna Data (Duas linhas para economizar largura)
        echo "<td class='ps-4'>
                <div class='fw-bold text-dark'>{$data_formatada}</div>
                <div class='small text-muted'>às {$hora_formatada}</div>
              </td>";

        // Coluna Produto
        echo "<td>
                <div class='fw-semibold'>{$row->nome_produto}</div>
                <div class='small text-secondary'>Unidade: {$row->unidade_produto} {$info_obs}</div>
              </td>";

        // Coluna Tipo (Badge)
        echo "<td>{$badge}</td>";

        // Coluna Quantidade
        echo "<td class='{$classe_qtd}' style='font-size: 1.1rem;'>
                {$sinal}{$row->quantidade}
              </td>";

        // Botões
        echo "<td class='text-end pe-4'>
                <a href='index.php?page=editar-movimentacao&id_movimento={$row->id_movimento}' 
                   class='btn btn-sm btn-edit me-2' title='Editar registro'>
                   Editar
                </a>

                <a href='index.php?page=salvar-movimentacao&acao=excluir&id_movimento={$row->id_movimento}'
                   class='btn btn-sm btn-delete'
                   onclick=\"return confirm('ATENÇÃO: Excluir esta movimentação irá reverter o saldo do estoque. Confirma a exclusão do registro #{$row->id_movimento}?');\"
                   title='Excluir registro'>
                   Excluir
                </a>
              </td>";
        echo "</tr>";
    }
    
    echo "</tbody></table>";
    echo "</div>"; // fecha responsive
    echo "</div></div>"; // fecha card

} else {
    // Empty State
    echo "<div class='alert alert-light border shadow-sm text-center py-5'>
            <h4 class='text-muted'>Sem histórico de transações</h4>
            <p class='mb-4'>Nenhuma entrada ou saída de mercadoria foi registrada ainda.</p>
            <a href='?page=cadastrar-movimentacao' class='btn btn-success fw-bold'>
                Registrar Primeira Entrada
            </a>
          </div>";
}

$conn->close();
?>