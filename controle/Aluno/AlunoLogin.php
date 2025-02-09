<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\MeuTokenJWT;

require_once "modelo/Aluno.php";
require_once "modelo/MeuTokenJWT.php";

$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);

if (!isset($obj->email) || !isset($obj->senha)) {
    echo json_encode([
        "cod" => 400,
        "msg" => "Dados incorretos ou incompletos. Por favor, forneça email e senha válidos."
    ]);
    exit();
}

$emailAluno = $obj->email;
$senhaAluno = $obj->senha;

$aluno = new Aluno();
$aluno->setEmailAluno($emailAluno);
$aluno->setSenhaAluno($senhaAluno);

if ($aluno->login()) {
  
    $tokenJWT = new MeuTokenJWT();
    $objectClaimsToken = new stdClass();
    $objectClaimsToken->emailAluno = $aluno->getEmailAluno();
    $objectClaimsToken->senhaAluno = $aluno->getSenhaAluno();
    $novoToken = $tokenJWT->gerarToken($objectClaimsToken);
    
    echo json_encode([
        "cod" => 200,
        "msg" => "Login realizado com sucesso!!!",
        "Aluno" => [
            "id_a" => $aluno->getIdAluno(),
            "nome" => $aluno->getNomeAluno(),
            "email" => $aluno->getEmailAluno(),
//            "senha" => $aluno->getSenhaAluno(),
            "id_curso" => $aluno->getIdCursoAluno()
        ],
        "token" => $novoToken
    ]);
} else {
    echo json_encode([
        "cod" => 401,
        "msg" => "ERRO: Login inválido. Verifique suas credenciais."
    ]);
}
?>
