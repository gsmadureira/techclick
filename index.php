<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TechClick - Hardware e PCs</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- Navbar Dark Theme TechClick -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid">

            <a class="navbar-brand fw-bold" href="index.php">
                 TechClick
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                    data-bs-target="#navbarNav" aria-controls="navbarNav" 
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">

                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button"
                           data-bs-toggle="dropdown">
                            Hardware
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?page=cadastrar-produto">Cadastrar Peça</a></li>
                            <li><a class="dropdown-item" href="?page=listar-produto">Listar Peças</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button"
                           data-bs-toggle="dropdown">
                            Categorias
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?page=cadastrar-categoria">Nova Categoria</a></li>
                            <li><a class="dropdown-item" href="?page=listar-categoria">Listar Categorias</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button"
                           data-bs-toggle="dropdown">
                            Vendas & Estoque
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?page=cadastrar-movimentacao">Registrar Venda/Entrada</a></li>
                            <li><a class="dropdown-item" href="?page=listar-movimentacao">Histórico</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=consultar-estoque">Estoque Atual</a>
                    </li>
                </ul>
            </div>

        </div>
    </nav>

    <!-- Área Principal de Conteúdo -->
    <div class="container content-area shadow-sm rounded-3 mt-4 p-4 bg-white">

        <?php
            // Inclui a configuração do banco de dados
            include("config.php");

            // Roteamento de páginas
            switch (@$_REQUEST["page"]) {

                // Produtos (Hardware)
                case "cadastrar-produto": include("cadastrar-produto.php"); break;
                case "listar-produto": include("listar-produto.php"); break;
                case "editar-produto": include("editar-produto.php"); break;
                case "salvar-produto": include("salvar-produto.php"); break;

                // Categorias
                case "cadastrar-categoria": include("cadastrar-categoria.php"); break;
                case "listar-categoria": include("listar-categoria.php"); break;
                case "editar-categoria": include("editar-categoria.php"); break;
                case "salvar-categoria": include("salvar-categoria.php"); break;

                // Movimentações (Vendas)
                case "cadastrar-movimentacao": include("cadastrar-movimentacao.php"); break;
                case "listar-movimentacao": include("listar-movimentacao.php"); break;
                case "editar-movimentacao": include("editar-movimentacao.php"); break;
                case "salvar-movimentacao": include("salvar-movimentacao.php"); break;

                // Estoque
                case "consultar-estoque": include("consultar-estoque.php"); break;

                // DASHBOARD (Página Inicial)
                default:
                    // --- CONSULTAS PARA O DASHBOARD --- //
    
                    // 1. Total de Produtos Cadastrados
                    $sql_prod = "SELECT count(*) as total FROM produto";
                    $total_produtos = $conn->query($sql_prod)->fetch_object()->total ?? 0;

                    // 2. Valor Total do Estoque (Preço * Quantidade)
                    $sql_valor = "SELECT SUM(p.preco_produto * e.quantidade) as total_valor 
                                  FROM produto p 
                                  INNER JOIN estoque e ON p.id_produto = e.id_produto";
                    $res_valor = $conn->query($sql_valor);
                    $total_valor = ($res_valor) ? $res_valor->fetch_object()->total_valor : 0;

                    // 3. Produtos com Estoque Baixo (Abaixo do Mínimo)
                    $sql_baixo = "SELECT count(*) as total FROM produto p 
                                  INNER JOIN estoque e ON p.id_produto = e.id_produto 
                                  WHERE e.quantidade <= p.estoque_minimo";
                    $total_baixo = $conn->query($sql_baixo)->fetch_object()->total ?? 0;
                    
                    // 4. Total de Movimentações Hoje
                    $sql_mov = "SELECT count(*) as total FROM movimentacoes WHERE DATE(data_movimento) = CURDATE()";
                    $total_mov = $conn->query($sql_mov)->fetch_object()->total ?? 0;

                    // Fechamos o PHP momentaneamente para renderizar o HTML do Dashboard
                    ?>
                    
                    <!-- Cabeçalho do Dashboard -->
                    <div class="mb-4 border-bottom pb-3">
                        <h1 class="fw-bold text-dark">Dashboard TechClick</h1>
                        <p class="text-muted mb-0">Visão geral do inventário de hardware e performance de vendas.</p>
                    </div>

                    <!-- LINHA DE CARDS (INDICADORES) -->
                    <div class="row mb-4">
                        
                        <!-- Card 1: Total Produtos -->
                        <div class="col-md-3">
                            <div class="card shadow-sm border-0 border-start border-4 border-primary h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-uppercase text-muted small fw-bold mb-1">Catálogo</h6>
                                            <h2 class="text-dark fw-bold mb-0"><?= $total_produtos ?></h2>
                                            <small class="text-primary">Produtos Cadastrados</small>
                                        </div>
                                        <div class="fs-1 text-primary opacity-25">📦</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Valor em Estoque -->
                        <div class="col-md-3">
                            <div class="card shadow-sm border-0 border-start border-4 border-success h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-uppercase text-muted small fw-bold mb-1">Valor em Estoque</h6>
                                            <h2 class="text-success fw-bold mb-0">R$ <?= number_format($total_valor, 0, ',', '.') ?></h2>
                                            <small class="text-success">Capital alocado</small>
                                        </div>
                                        <div class="fs-1 text-success opacity-25">💲</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Estoque Baixo (Alerta) -->
                        <div class="col-md-3">
                            <div class="card shadow-sm border-0 border-start border-4 border-danger h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-uppercase text-muted small fw-bold mb-1">Estoque Crítico</h6>
                                            <h2 class="text-danger fw-bold mb-0"><?= $total_baixo ?></h2>
                                            <small class="text-danger">Itens para repor</small>
                                        </div>
                                        <div class="fs-1 text-danger opacity-25">⚠️</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 4: Movimentações Hoje -->
                        <div class="col-md-3">
                            <div class="card shadow-sm border-0 border-start border-4 border-info h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-uppercase text-muted small fw-bold mb-1">Movimentos Hoje</h6>
                                            <h2 class="text-info fw-bold mb-0"><?= $total_mov ?></h2>
                                            <small class="text-info">Operações realizadas</small>
                                        </div>
                                        <div class="fs-1 text-info opacity-25">📊</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEÇÃO DE ATALHOS E STATUS -->
                    <div class="row">
                        
                        <!-- Coluna da Esquerda: Atalhos Rápidos -->
                        <div class="col-md-5 mb-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-white fw-bold py-3">⚡ Ações Rápidas</div>
                                <div class="card-body d-grid gap-3 align-content-start">
                                    <a href="?page=cadastrar-movimentacao" class="btn btn-outline-primary btn-lg text-start px-4">
                                        <span class="fs-5 me-2">⬆⬇</span> Registrar Entrada/Saída
                                    </a>
                                    <a href="?page=cadastrar-produto" class="btn btn-outline-dark btn-lg text-start px-4">
                                        <span class="fs-5 me-2">➕</span> Cadastrar Novo Hardware
                                    </a>
                                    <a href="?page=consultar-estoque" class="btn btn-outline-secondary btn-lg text-start px-4">
                                        <span class="fs-5 me-2">📋</span> Conferir Estoque Completo
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Coluna da Direita: Últimas Movimentações (Mini Tabela) -->
                        <div class="col-md-7 mb-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                                    <span>🕒 Últimas Movimentações</span>
                                    <a href="?page=listar-movimentacao" class="btn btn-sm btn-link text-decoration-none">Ver tudo</a>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0 align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="ps-4">Produto</th>
                                                    <th>Tipo</th>
                                                    <th class="text-end pe-4">Qtd</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    // Busca as últimas 5 movimentações
                                                    $sql_last = "SELECT p.nome_produto, m.tipo, m.quantidade 
                                                                FROM movimentacoes m 
                                                                INNER JOIN produto p ON m.id_produto = p.id_produto 
                                                                ORDER BY m.data_movimento DESC LIMIT 5";
                                                    $res_last = $conn->query($sql_last);
                                                    
                                                    if($res_last && $res_last->num_rows > 0){
                                                        while($row = $res_last->fetch_object()){
                                                            $cor = ($row->tipo == 'ENTRADA') ? 'text-success' : 'text-danger';
                                                            $sinal = ($row->tipo == 'ENTRADA') ? '+' : '-';
                                                            $badge_bg = ($row->tipo == 'ENTRADA') ? 'bg-success' : 'bg-danger';
                                                            
                                                            echo "<tr>
                                                                    <td class='ps-4 small fw-bold text-dark'>{$row->nome_produto}</td>
                                                                    <td><span class='badge {$badge_bg} bg-opacity-75'>{$row->tipo}</span></td>
                                                                    <td class='text-end pe-4 fw-bold {$cor}'>{$sinal}{$row->quantidade}</td>
                                                                </tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='3' class='text-center p-4 text-muted'>Nenhuma atividade recente encontrada.</td></tr>";
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php
                    // Fim do conteúdo do Dashboard
            }
        ?>

    </div>

    <script src="js/bootstrap.bundle.min.js"></script>

</body>
</html>