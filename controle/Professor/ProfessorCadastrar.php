<?php
require_once "modelo/Professor.php";

$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);

if (!isset($obj->nome) || !isset($obj->email) || !isset($obj->senha)) {
    echo json_encode([
        "cod" => 400,
        "msg" => "Dados incompletos. Por favor, forneça id_prof, nome, email e senha."
    ]);
    exit();
}

$nomeProf = $obj->nome;
$emailProf = $obj->email;
$senhaProf = $obj->senha;


// Sanitize input
$nomeProf = strip_tags($nomeProf);

$Professor = new Professor();
$Professor->setNomeProf($nomeProf);
$Professor->setEmailProf($emailProf);
$Professor->setSenhaProf($senhaProf);


if ($Professor->cadastrarProf()) {
    echo json_encode([
        "cod" => 204,
        "msg" => "Cadastrado com sucesso!!!",
        "Professor" => $Professor
    ]);
} else {
    echo json_encode([
        "cod" => 500,
        "msg" => "ERRO ao cadastrar o professor"
    ]);
}

/*

{
    "nome": "",
    "email": "",
    "senha": ""
}

*/

?>

