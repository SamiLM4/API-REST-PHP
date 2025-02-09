<?php
use Firebase\JWT\MeuTokenJWT2;
require_once "modelo/Professor.php";
require_once "modelo/MeuTokenJWT2.php";
$vetor = explode("/", $_SERVER['REQUEST_URI']);
$metodo = $_SERVER['REQUEST_METHOD'];

$headers=getallheaders();
    $autorization=$headers['Authorization'];
   $meutoken= new MeuTokenJWT2();

   if($meutoken->validarToken($autorization)==true){
            $payloadRecuperado=$meutoken->getPayload();
        if ($metodo == "GET") {
            $id_Prof = $vetor[2];
            
            $Professor = new Professor();
            $Professor->setIdProf($id_Prof);
            $ProfessorSelecionado = $Professor->readID();
            
            if ($ProfessorSelecionado) {
                header("HTTP/1.1 200 OK");
                echo json_encode([
                    "cod" => 200,
                    "msg" => "Curso encontrado",
                    "curso" => [
                        "id_prof" => $ProfessorSelecionado->getIdProf(),
                        "nome" => $ProfessorSelecionado->getNomeProf(),
                        "email" => $ProfessorSelecionado->getEmailProf(),
                        "senha" =>$ProfessorSelecionado->getSenhaProf()
                    
                    ],
                ]);
            } else {
                header("HTTP/1.1 404 Not Found");
                echo json_encode([
                    "cod" => 404,
                    "msg" => "Professor não encontrado"
                ]);
            }
        } else {
            header("HTTP/1.1 405 Method Not Allowed");
            echo json_encode([
                "cod" => 405,
                "msg" => "Método não permitido"
            ]);
        }
   }else{
    
    header("HTTP/1.1 404 Not Found");
    echo json_encode(["mensagem" => "Erro"]);

   }
?>