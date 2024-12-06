<?php
include_once "./conexao.php";

$consultaBanco = "SELECT empresa.EMPRESA, empresa.RAZAO_SOCIAL, cidade.DESCRICAO_CIDADE
                        FROM empresa
                        INNER JOIN cidade
                        ON empresa.CIDADE = cidade.CIDADE 
                        AND empresa.EMPRESA = cidade.EMPRESA;";
$resultadoBancoDeDados = $mysqli->query($consultaBanco);
?>
<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trovata</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <main class="h-screen">
        <div class="flex w-full h-full justify-center items-center">
            <div class="flex gap-10 flex-col">
                <?php
                $contador = 0;

                while ($registro = $resultadoBancoDeDados->fetch_assoc()) {
                    if ($contador >= 2) {
                        break;
                    }
                    $idEmpresa = $registro["EMPRESA"];
                    $razaoSocial = $registro["RAZAO_SOCIAL"];
                    $descricaoCidade = $registro["DESCRICAO_CIDADE"];
                    ?>

                    <div class='card' style="width: 18rem;">
                        <div class="card-body">
                            <h5 class='card-title'><?php echo $razaoSocial; ?></h5>
                            <p class='card-text py-3'><?php echo $descricaoCidade; ?></p>
                            <a href='produtos.php?id=<?php echo $idEmpresa; ?>' class="btn btn-primary">Selecionar
                                empresa</a>
                        </div>
                    </div>

                    <?php
                    $contador++;
                }
                ?>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>