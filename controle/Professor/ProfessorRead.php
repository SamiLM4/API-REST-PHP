<?php
use Firebase\JWT\MeuTokenJWT2;
    require_once "modelo/Professor.php";
    require_once "modelo/MeuTokenJWT2.php";
    $headers=getallheaders();
    $autorization=$headers['Authorization'];
   $meutoken= new MeuTokenJWT2();
    
   if($meutoken->validarToken($autorization)==true){
//    $payloadRecuperado=$meutoken->getPayload();
    
    $professor = new Professor();

    $vetor = explode("/", $_SERVER['REQUEST_URI']);
    $pagina =  $vetor[2];
    
        $professores = $professor->readPage($pagina);

        header("Content-Type: application/json");
        if ($professores) {
            header("HTTP/1.1 200 OK");

            echo json_encode([
                "cod" => 200,
                "msg" => "busca realizado com sucesso!!!",
                "Professor" => [ 
                    "dados" => $professores
                ],
            ]);

        } else {
            header("HTTP/1.1 404 Not Found");
            echo json_encode(["mensagem" => "Nenhum professor encontrado."]);
        }

    }else{
    
        header("HTTP/1.1 404 Not Found");
        echo json_encode(["mensagem" => "Token Invalido!!."]);
    
       }
        

?>