<?php



ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

set_time_limit(1200000);

//configurações

//01 - DADOS DO BANCO DE DADOS
$DB_CONNECTION='mysql';
$DB_HOST='abacotecnologia.com.br';
$DB_PORT='3306';
$DB_DATABASE='sistemas_g';
$DB_USERNAME='sistemas_g';
$DB_PASSWORD='HZPAa7ekE';

//$conexao = mysqli_connect("localhost", "dalves", "athais30", "sistemas_g");
$conexao = mysqli_connect("$DB_HOST", "$DB_USERNAME", "$DB_PASSWORD", "$DB_DATABASE");

// $conexao = mysqli_connect("localhost", "rest", "rest", "unilabs_opencart");



//02 - ARQUIVOS DE CARGA

$carga = file("dados.tsv");

// $dibcarga = file("dibcarga.txt");





/*
$result = mysqli_query($conexao, "update  company_client set mail_company = '".strtolower($linha[28])."', updated_at = now() where id = "$linha[23]";"

");*/



for ($d = 0; $d < count($carga); $d++) {
    $linha = explode('|', $carga[$d]);
    if($d==0){
        for ($e = 0; $e < count($linha); $e++) {
            echo $e. ' : ' . $linha[$e].'<br>'; 
        }

    }else{
        echo ($linha[23].' : '.strtolower($linha[28]).'<br>');
        if($linha[28] != '(NULL)'){
            $result = mysqli_query($conexao, "update  company_client set mail_company = '".strtolower($linha[28])."', updated_at = now() where id = ".$linha[23].";");
        }else{
            echo ($linha[23].' : NADA FEITO<br>');
        }
    }
}
