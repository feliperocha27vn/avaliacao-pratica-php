<?php

require "conexao.php";

$consultaBanco = "SELECT empresa.EMPRESA, empresa.RAZAO_SOCIAL, cidade.DESCRICAO_CIDADE
    FROM empresa
    INNER JOIN cidade
    ON empresa.CIDADE = cidade.CIDADE 
    AND empresa.EMPRESA = cidade.EMPRESA;";
$resultadoBancoDeDados = $mysqli->query($consultaBanco);

$consultaEmpresa = "SELECT empresa.EMPRESA
            FROM empresa";

$resultadoEmpresa = $mysqli->query($consultaEmpresa);

