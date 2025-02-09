<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\MeuTokenJWT2;

require_once "modelo/Professor.php";
require_once "modelo/MeuTokenJWT2.php";

$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);

if (!isset($obj->email) || !isset($obj->email)) {
    echo json_encode([
        "cod" => 400,
        "msg" => "Dados incorretos ou incompletos. Por favor, forneça email e id_curso válido."
    ]);
    exit();
}

$emailProf = $obj->email;
$senhaProf = $obj->senha;

$emailProf = strip_tags($emailProf);

$professor = new Professor();
$professor->setEmailProf($emailProf);
$professor->setSenhaProf($senhaProf);


if ($professor->login()) {

    $tokenJWT = new MeuTokenJWT2();
    $objectClaimsToken = new stdClass();
    $objectClaimsToken->id_prof = $professor->getIdProf();
    $objectClaimsToken->nome = $professor->getNomeProf();
    $objectClaimsToken->email = $professor->getEmailProf();
    $objectClaimsToken->senha = $professor->getSenhaProf();
    
    $novoToken = $tokenJWT->gerarToken($objectClaimsToken);
    
    echo json_encode([
        "cod" => 200,
        "msg" => "Login realizado com sucesso!!!",
        "Professor" => [
            "id_prof" => $professor->getIdProf(),
            "nome" => $professor->getNomeProf(),
            "email" => $professor->getEmailProf(),
            "senha" => $professor->getSenhaProf()
           
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
