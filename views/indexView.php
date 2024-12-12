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