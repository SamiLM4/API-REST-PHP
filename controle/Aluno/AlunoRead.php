<?php
use Firebase\JWT\MeuTokenJWT;
    require_once "modelo/Aluno.php";
    require_once "modelo/MeuTokenJWT.php";
    $headers=getallheaders();
    $autorization=$headers['Authorization'];
   $meutoken= new MeuTokenJWT();
   
   if($meutoken->validarToken($autorization)==true){
    $payloadRecuperado=$meutoken->getPayload();

    $vetor = explode("/", $_SERVER['REQUEST_URI']);
    $pagina =  $vetor[2];

    $aluno = new Aluno();
    $alunos = $aluno->readPage($pagina);

    header("Content-Type: application/json");
    if ($alunos) {
        header("HTTP/1.1 200 OK");


        echo json_encode([
            "cod" => 200,
            "msg" => "busca realizado com sucesso!!!",
            "Professor" => [ 
                "dados" => $alunos
            ],
        ]);
        
    } else {
        header("HTTP/1.1 404 Not Found");
        echo json_encode(["mensagem" => "Nenhum Aluno encontrado."]);
    }


   }else{
    
    header("HTTP/1.1 404 Not Found");
    echo json_encode(["mensagem" => "Erro"]);

   }
    
    
?>