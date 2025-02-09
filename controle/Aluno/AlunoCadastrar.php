<?php
require_once "modelo/Aluno.php";

$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);

if (!isset($obj->nome) || !isset($obj->email) || !isset($obj->senha) || !isset($obj->id_curso)) {
    echo json_encode([
        "cod" => 400,
        "msg" => "Dados incompletos. Por favor, forneça id_a, nome, email, senha, id_curso."
    ]);
    exit();
}

$nomeAluno = $obj->nome;
$emailAluno= $obj->email;
$senhaAluno = $obj->senha;
$id_cursoAluno = $obj->id_curso;


// Sanitize input
//$nome = strip_tags($nome);

$aluno = new Aluno();
//$aluno->setIdAluno(0);
$aluno->setNomeAluno($nomeAluno);
$aluno->setEmailAluno($emailAluno);
$aluno->setSenhaAluno($senhaAluno);
$aluno->setIdCursoAluno($id_cursoAluno);


if ($aluno->cadastrarAluno()) {
    echo json_encode([
        "cod" => 201,
        "msg" => "Cadastrado com sucesso!!!",
        "Aluno" =>$aluno
    ]);
} else {
    echo json_encode([
        "cod" => 500,
        "msg" => "ERRO ao cadastrar o Aluno"
    ]);
}

/*

{
    "nome": "",
    "email": "",
    "senha": "",
    "id_curso": 
}

*/

?>




