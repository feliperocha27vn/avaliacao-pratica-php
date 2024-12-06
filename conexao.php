<?php 
    $hostname = "localhost";
    $bancoDeDados = "trovata";
    $usuario = "root";
    $senha = "123";

    $mysqli = new mysqli($hostname, $usuario, $senha, $bancoDeDados); 
    if ($mysqli->connect_errno) {
        echo"falha ao conectar: ". $mysqli->connect_error;
    } else {
         echo"";
    }
 