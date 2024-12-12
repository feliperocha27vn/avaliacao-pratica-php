<?php
require "models/conexao.php";

// Validação e sanitização dos parâmetros recebidos via GET
$idProduto = isset($_GET['idProduto']) ? intval($_GET['idProduto']) : 0;
$idEmpresa = isset($_GET['idEmpresa']) ? intval($_GET['idEmpresa']) : 0;

// Verifica se os parâmetros estão presentes e válidos
if (!$idProduto || !$idEmpresa) {
    die("Parâmetros inválidos ou ausentes.");
}

// Consulta para buscar a razão social da empresa
$stmtEmpresa = $mysqli->prepare("SELECT RAZAO_SOCIAL FROM empresa WHERE EMPRESA = ? LIMIT 1");
$stmtEmpresa->bind_param("i", $idEmpresa);
$stmtEmpresa->execute();
$resultadoEmpresa = $stmtEmpresa->get_result();
$razaoSocial = $resultadoEmpresa->fetch_assoc()['RAZAO_SOCIAL'] ?? 'Empresa não encontrada';

// Consulta para buscar os grupos de produto vinculados à empresa
$stmtGrupo = $mysqli->prepare("SELECT GRUPO_PRODUTO, DESCRICAO_GRUPO_PRODUTO FROM grupo_produto WHERE EMPRESA = ?");
$stmtGrupo->bind_param("i", $idEmpresa);
$stmtGrupo->execute();
$resultadoGrupoProduto = $stmtGrupo->get_result();

// Consulta para buscar os dados do produto
$stmtProduto = $mysqli->prepare("SELECT * FROM produto WHERE PRODUTO = ?");
$stmtProduto->bind_param("i", $idProduto);
$stmtProduto->execute();
$resultadoProduto = $stmtProduto->get_result();

// Inicialização das variáveis do produto
if ($resultadoProduto->num_rows > 0) {
    $produto = $resultadoProduto->fetch_assoc();
    $descricaoProduto = $produto["DESCRICAO_PRODUTO"] ?? '';
    $apelidoProduto = $produto["APELIDO_PRODUTO"] ?? '';
    $grupoProduto = $produto["GRUPO_PRODUTO"] ?? '';
    $pesoLiquido = $produto["PESO_LIQUIDO"] ?? '';
    $classificacaoFiscal = $produto["CLASSIFICACAO_FISCAL"] ?? '';
} else {
    die("Produto não encontrado.");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="container mt-4">
        <a href="produtos.php?id=<?php echo $idEmpresa; ?>">
            <button type="button" class="btn btn-primary mb-2">Voltar</button>
        </a>
    </div>

    <div class="flex flex-col items-center">
        <label class="font-bold text-xl mb-5"><?php echo $razaoSocial; ?></label>
        <form class="w-5/12" action="editar.php" method="post">
            <input type="hidden" name="idProduto" value="<?php echo $idProduto; ?>">
            <input type="hidden" name="idEmpresa" value="<?php echo $idEmpresa; ?>">
            <div class="mb-3">
                <label class="form-label">Descrição do Produto</label>
                <input type="text" name="descricaoProduto" class="form-control" required
                    value="<?php echo $descricaoProduto; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Apelido do Produto</label>
                <input type="text" name="apelidoProduto" class="form-control" required
                    value="<?php echo $apelidoProduto; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Grupo Produto</label>
                <select class="form-select" name="grupoProduto">
                    <option selected value="<?php echo $grupoProduto; ?>">
                        <?php echo $grupoProduto; ?>
                    </option>
                    <?php while ($registro = $resultadoGrupoProduto->fetch_assoc()): ?>
                        <option value="<?php echo $registro["GRUPO_PRODUTO"]; ?>">
                            <?php echo $registro["DESCRICAO_GRUPO_PRODUTO"]; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Peso Líquido</label>
                <input type="text" name="pesoLiquido" class="form-control" required value="<?php echo $pesoLiquido; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Classificação Fiscal</label>
                <input type="text" name="classificacaoFiscal" class="form-control" required
                    value="<?php echo $classificacaoFiscal; ?>">
            </div>
            <div class="flex justify-center">
                <button type="submit" name="update" id="update" class="btn btn-warning">Editar Produto</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>