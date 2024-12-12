<?php
require "models/consultas.php";

if (!empty($_GET['id'])) {

    $id = $_GET['id'];

    // paginação
    $itens_por_pagina = 15;
    $pagina_atual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
    $inicio = ($pagina_atual - 1) * $itens_por_pagina;

    // requisicao da ordem 
    $coluna = isset($_GET['coluna']) ? $_GET['coluna'] : 'PRODUTO';
    $ordem = isset($_GET['ordem']) ? $_GET['ordem'] : 'ASC';

    // verifica se é uma coluna valida 
    $colunas_validas = ['PRODUTO', 'DESCRICAO_PRODUTO', 'DESCRICAO_GRUPO_PRODUTO'];
    $ordens_validas = ['ASC', 'DESC'];

    if (!in_array($coluna, $colunas_validas)) {
        $coluna = 'PRODUTO';
    }

    if (!in_array($ordem, $ordens_validas)) {
        $ordem = 'ASC';
    }

    $sqlCount = "SELECT COUNT(*) as total 
                 FROM produto, empresa 
                 WHERE produto.EMPRESA = empresa.EMPRESA 
                 AND empresa.EMPRESA = $id";

    $resultCount = $mysqli->query($sqlCount);
    $total_registros = $resultCount->fetch_assoc()['total'];
    $total_paginas = ceil($total_registros / $itens_por_pagina);

    $sqlSelect = "SELECT empresa.RAZAO_SOCIAL, produto.DESCRICAO_PRODUTO, produto.PRODUTO, grupo_produto.DESCRICAO_GRUPO_PRODUTO 
                  FROM produto, empresa, grupo_produto 
                  WHERE produto.EMPRESA = empresa.EMPRESA
                  AND produto.GRUPO_PRODUTO = grupo_produto.GRUPO_PRODUTO 
                  AND empresa.EMPRESA = $id 
                  ORDER BY $coluna $ordem 
                  LIMIT $inicio, $itens_por_pagina";

    $resultadoEmpresa = $mysqli->query($sqlSelect);

    // razão da empresa
    $sqlEmpresa = "SELECT RAZAO_SOCIAL FROM empresa WHERE EMPRESA = $id LIMIT 1";
    $resultEmpresa = $mysqli->query($sqlEmpresa);
    $razaoSocial = $resultEmpresa->fetch_assoc()['RAZAO_SOCIAL'];

    // controle de ordenação
    $proximaOrdem = ($ordem === 'ASC') ? 'DESC' : 'ASC';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $razaoSocial; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="container mt-4">
        <a href='index.php'>
            <button type="button" class="btn btn-primary mb-2">Página Principal</button>
        </a>
        <div class="flex justify-center mb-5">
            <form class="w-2/6 flex gap-x-2">
                <input type="text" class="form-control" name="pesquisar" placeholder="Pesquisar">
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </button>
                <!-- adicionar -->
                <a href="adicionar.php?id=<?php echo $id; ?>" type="button" class="btn btn-success flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-plus">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                </a>
            </form>
        </div>
        <h2 class="mb-4 text-center"><?php echo $razaoSocial; ?></h2>
        <table class="table table-hover text-center align-middle">
            <thead>
                <tr class="align-middle">
                    <th scope="col">
                        <a href="?id=<?php echo $id; ?>&coluna=PRODUTO&ordem=<?php echo $proximaOrdem; ?>">Identificação
                            Produto</a>
                    </th>
                    <th scope="col">
                        <a href="?id=<?php echo $id; ?>&coluna=DESCRICAO_PRODUTO&ordem=<?php echo $proximaOrdem; ?>">Descrição
                            de Produto</a>
                    </th>
                    <th scope="col">
                        <a
                            href="?id=<?php echo $id; ?>&coluna=DESCRICAO_GRUPO_PRODUTO&ordem=<?php echo $proximaOrdem; ?>">Grupo
                            Produto</a>
                    </th>
                    <th scope="col">
                        <a href="">
                            Opções</a>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($registro = $resultadoEmpresa->fetch_assoc()) {
                    $idProduto = $registro["PRODUTO"];
                    $descricaoProduto = $registro["DESCRICAO_PRODUTO"];
                    $grupoProduto = $registro["DESCRICAO_GRUPO_PRODUTO"];
                    ?>
                    <tr>
                        <td><?php echo $idProduto ?></td>
                        <td><?php echo $descricaoProduto ?></td>
                        <td><?php echo $grupoProduto ?></td>
                        <td>
                            <!-- editar -->
                            <a href="editar.php?idProduto=<?php echo $idProduto; ?>&idEmpresa=<?php echo $id; ?>">
                                <button type="button" class="btn btn-warning">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-pen">
                                        <path
                                            d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                                    </svg>
                                </button>
                            </a>
                            <!-- remover -->
                            <button type="button" class="btn btn-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-x">
                                    <path d="M18 6 6 18" />
                                    <path d="m6 6 12 12" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>

        <!-- Paginação -->
        <nav aria-label="Navegação de páginas" class="mt-4">
            <ul class="pagination justify-content-center p-2">
                <?php if ($pagina_atual > 1): ?>
                    <li class="page-item">
                        <a class="page-link"
                            href="?id=<?php echo $id; ?>&pagina=<?php echo ($pagina_atual - 1); ?>&coluna=<?php echo $coluna; ?>&ordem=<?php echo $ordem; ?>">Anterior</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <li class="page-item <?php echo ($i == $pagina_atual) ? 'active' : ''; ?>">
                        <a class="page-link"
                            href="? id=<?php echo $id; ?>&pagina=<?php echo $i; ?>&coluna=<?php echo $coluna; ?>&ordem=<?php echo $ordem; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($pagina_atual < $total_paginas): ?>
                    <li class="page-item">
                        <a class="page-link"
                            href="?id=<?php echo $id; ?>&pagina=<?php echo ($pagina_atual + 1); ?>&coluna=<?php echo $coluna; ?>&ordem=<?php echo $ordem; ?>">Próxima</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>