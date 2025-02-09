<?php

use Firebase\JWT\MeuTokenJWT;
require_once "modelo/Aluno.php";
require_once "modelo/MeuTokenJWT.php";
    $headers=getallheaders();
    $autorization=$headers['Authorization'];
   $meutoken= new MeuTokenJWT();

$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);

$vetor = explode("/", $_SERVER['REQUEST_URI']);
$idAluno =  $vetor[2];

$nomeAluno= $obj->nome;
$emailAluno = $obj->email;
$senhaAluno = $obj->senha;
$id_cursoAluno = $obj->id_curso;

/*

{
    "nome": "",
    "email": "",
    "senha": "",
    "id_curso" 
}

*/


// Remover tags HTML e PHP
$idAluno = strip_tags($idAluno);
$nomeAluno = strip_tags($nomeAluno);
$emailAluno = strip_tags($emailAluno);
$senhaAluno = strip_tags($senhaAluno);
$id_cursoAluno = strip_tags($id_cursoAluno);


$resposta = array();
$verificador = 0;



// Verificação se o nome do curso está vazio
if ($nomeAluno== '') {
    $resposta['cod'] = 3;
    $resposta['msg'] = "nome nao pode ser vazio";
    $verificador = 1;
} else if ($emailAluno == '') {
    $resposta['cod'] = 3;
    $resposta['msg'] = "anos nao pode ser vazio";
    $verificador = 1;
}

// Verificação se os anos de conclusão e preço do curso são válidos


if (!is_numeric($id_a)) { // Verificação do id_professor
    $resposta['cod'] = 3;
    $resposta['msg'] = "id_prof deve ser um número";
    $verificador = 1;
}
if (!is_numeric($id_cursoAluno)) {
    $resposta['cod'] = 3;
    $resposta['msg'] = "id_curso deve ser um número";
    $verificador = 1;
}

if($meutoken->validarToken($autorization)==true){
    $payloadRecuperado=$meutoken->getPayload();
    
    // Instanciar e atualizar o curso
    $aluno = new Aluno();
        
    // Certifique-se de que $rota[2] está definido corretamente
    $aluno->setIdAluno($idAluno);
    $aluno->setNomeAluno($nomeAluno);
    $aluno->setEmailAluno($emailAluno);
    $aluno->setIdcursoAluno($id_cursoAluno);
    $resultado = $aluno->update();
    if ($resultado == true) {
        header("HTTP/1.1 201 Created");
        $resposta['cod'] = 4;
        $resposta['msg'] = "Alteracao feita com sucesso!!!";
           
    } else {
        header("HTTP/1.1 500 Internal Server Error");
        $resposta['cod'] = 5;
        $resposta['msg'] = "ERRO ao cadastrar o curso";
    }
  

    echo json_encode($resposta);
}

?>

