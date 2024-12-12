<?php
require "controllers/adicionarController.php";
require "models/conexao.php";

$idEmpresa = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$idEmpresa) {
    die('Erro: ID da empresa não foi fornecido.');
}

$consultaEmpresa = "SELECT RAZAO_SOCIAL FROM empresa WHERE EMPRESA = $idEmpresa LIMIT 1";
$resultadoEmpresa = $mysqli->query($consultaEmpresa);
$razaoSocial = $resultadoEmpresa->fetch_assoc()['RAZAO_SOCIAL'] ?? 'Empresa não encontrada';

$consultaDescricaoGrupoProduto = "SELECT grupo_produto.GRUPO_PRODUTO, grupo_produto.DESCRICAO_GRUPO_PRODUTO
                                    FROM grupo_produto
                                    WHERE grupo_produto.EMPRESA = $idEmpresa";
$resultadoGrupoProduto = $mysqli->query($consultaDescricaoGrupoProduto);

if (isset($_POST["submit"])) {
    $idProduto = rand(100, 400);
    $descricaoProduto = $_POST["descricaoProduto"];
    $apelidoProduto = $_POST["apelidoProduto"];
    $grupoProduto = $_POST["grupoProduto"];
    $subGrupoProduto = 0;
    $situacao = "A";
    $pesoLiquido = $_POST["pesoLiquido"];
    $classificacaoFiscal = $_POST["classificacaoFiscal"];
    $colecao = 1;

    $resultado = mysqli_query($mysqli, "INSERT INTO produto(EMPRESA, PRODUTO, DESCRICAO_PRODUTO, APELIDO_PRODUTO, GRUPO_PRODUTO, SUBGRUPO_PRODUTO, SITUACAO, PESO_LIQUIDO, CLASSIFICACAO_FISCAL, COLECAO)
                                                        VALUES ('$idEmpresa', '$idProduto', '$descricaoProduto', '$apelidoProduto', '$grupoProduto', '$subGrupoProduto', '$situacao', '$pesoLiquido', '$classificacaoFiscal', '$colecao')");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="container mt-4">
        <a href='produtos.php?id=<?php echo $idEmpresa ?>'>
            <button type="button" class="btn btn-primary mb-2">Voltar</button>
        </a>
    </div>

    <div class="flex flex-col items-center">
        <label class="font-bold text-xl mb-5"><?php echo $razaoSocial ?></label>
        <form class="w-5/12" action="adicionar.php" method="post">
            <input type="hidden" name="id" value="<?php echo $idEmpresa; ?>">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Descrição do Produto</label>
                <input type="text" name="descricaoProduto" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Apelido do Produto</label>
                <input type="text" name="apelidoProduto" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Grupo Produto</label>
                <select class="form-select" name="grupoProduto">
                    <option selected>Escolha o grupo</option>
                    <?php while ($registro = $resultadoGrupoProduto->fetch_assoc()) {
                        $idGrupoProduto = $registro["GRUPO_PRODUTO"];
                        $descricaoGrupoProduto = $registro["DESCRICAO_GRUPO_PRODUTO"];
                        ?>
                        <option value="<?php echo htmlspecialchars($registro['GRUPO_PRODUTO']); ?>">
                            <?php echo $descricaoGrupoProduto; ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Peso Líquido</label>
                <input type="text" name="pesoLiquido" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Classificação Fiscal</label>
                <input type="text" name="classificacaoFiscal" class="form-control" required>
            </div>
            <div class="flex justify-center">
                <button type="submit" name="submit" id="submit" class="btn btn-success">Adicionar Produto</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>