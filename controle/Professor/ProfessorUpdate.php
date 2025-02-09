<?php

require_once "modelo/Professor.php";

$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);

$vetor = explode("/", $_SERVER['REQUEST_URI']);
$id_Prof =  $vetor[2];

$nomeProf = $obj->nome;
$emailProf = $obj->email;
$senhaProf = $obj->senha;

/*

{
    "id_prof": ,
    "nome": "",
    "email": "",
    "senha": ""
}

*/

// Remover tags HTML e PHP
$id_Prof = strip_tags($id_Prof);
$nomeProf = strip_tags($nomeProf);
$emailProf = strip_tags($emailProf);
$senhaProf = strip_tags($senhaProf);


$resposta = array();
$verificador = 0;

// Verificação se o nome do curso está vazio
if ($nomeProf== '') {
    $resposta['cod'] = 3;
    $resposta['msg'] = "nome nao pode ser vazio";
    $verificador = 1;
} else if ($emailProf == '') {
    $resposta['cod'] = 3;
    $resposta['msg'] = "email nao pode ser vazio";
    $verificador = 1;
} else if ($senhaProf == '') {
    $resposta['cod'] = 3;
    $resposta['msg'] = "senha nao pode ser vazio";
    $verificador = 1;
}

// Verificação se os anos de conclusão e preço do curso são válidos


if (!is_numeric($id_Prof)) { // Verificação do id_professor
    $resposta['cod'] = 3;
    $resposta['msg'] = "id_prof deve ser um número";
    $verificador = 1;
}

if ($verificador == 0) {
    // Instanciar e atualizar o curso
    $professor = new Professor();
    // Certifique-se de que $rota[2] está definido corretamente
    $professor->setIdProf($id_Prof );
    $professor->setNomeProf($nomeProf);
    $professor->setEmailProf($emailProf);
    $professor->setSenhaProf($senhaProf);
 

    $resultado = $professor->update();
    if ($resultado == true) {
        header("HTTP/1.1 201 Created");
        $resposta['cod'] = 4;
        $resposta['msg'] = "Atualizado com sucesso!!!";
        $resposta['Professor Atualizado'] = $resultado;
    } else {
        header("HTTP/1.1 500 Internal Server Error");
        $resposta['cod'] = 5;
        $resposta['msg'] = "ERRO ao atualizar o Professor";
    }
}

echo json_encode($resposta);

?>

