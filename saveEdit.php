<?php
require "models/conexao.php";

if (isset($_POST["update"])) {
    // Valida e sanitiza os dados recebidos do formulário
    $idProduto = isset($_POST["idProduto"]) ? intval($_POST["idProduto"]) : 0;
    $descricaoProduto = $_POST["descricaoProduto"] ?? '';
    $apelidoProduto = $_POST["apelidoProduto"] ?? '';
    $grupoProduto = $_POST["grupoProduto"] ?? '';
    $pesoLiquido = $_POST["pesoLiquido"] ?? '';
    $classificacaoFiscal = $_POST["classificacaoFiscal"] ?? '';

    // Verifica se o ID do produto é válido
    if ($idProduto <= 0) {
        die("ID do produto inválido.");
    }

    // Prepara a consulta para evitar SQL Injection
    $stmt = $mysqli->prepare("
        UPDATE produto 
        SET DESCRICAO_PRODUTO = ?, 
            APELIDO_PRODUTO = ?, 
            GRUPO_PRODUTO = ?, 
            PESO_LIQUIDO = ?, 
            CLASSIFICACAO_FISCAL = ? 
        WHERE PRODUTO = ?
    ");

    // Verifica se a preparação foi bem-sucedida
    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $mysqli->error);
    }

    // Vincula os parâmetros ao statement
    $stmt->bind_param("sssssi", $descricaoProduto, $apelidoProduto, $grupoProduto, $pesoLiquido, $classificacaoFiscal, $idProduto);

    // Executa a consulta
    if ($stmt->execute()) {
        echo "Produto atualizado com sucesso!";
        // Redireciona para a página anterior ou para a lista de produtos
        header("Location: produtos.php?id=" . intval($_POST["idEmpresa"]));
        exit;
    } else {
        echo "Erro ao atualizar o produto: " . $stmt->error;
    }

    // Fecha o statement
    $stmt->close();
}

// Fecha a conexão com o banco
$mysqli->close();
?>
