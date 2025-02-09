<?php
use Firebase\JWT\MeuTokenJWT;
require_once "modelo/Aluno.php";
require_once "modelo/MeuTokenJWT.php";
$vetor = explode("/", $_SERVER['REQUEST_URI']);
$metodo = $_SERVER['REQUEST_METHOD'];

$headers=getallheaders();
    $autorization=$headers['Authorization'];
   $meutoken= new MeuTokenJWT();

   if($meutoken->validarToken($autorization)==true){
    $payloadRecuperado=$meutoken->getPayload();
    
    if ($metodo == "GET") {
        $id_a = $vetor[2];
        
        $aluno = new Aluno();
        $aluno->setIdAluno($id_a);
        $alunoSelecionado = $aluno->readID();
        
        if ($alunoSelecionado) {
            
            header("HTTP/1.1 200 OK");
            echo json_encode([
                "cod" => 200,
                "msg" => "Aluno encontrado",
                "curso" => [
                    "id_a" => $alunoSelecionado->getIdAluno(),
                    "nome" => $alunoSelecionado->getNomeAluno(),
                    "email" => $alunoSelecionado->getEmailAluno(),
                    "id_curso" => $alunoSelecionado->getIdcursoAluno()
                    
                ],
            ]);
           
          
        
            
          
        } else {
            header("HTTP/1.1 404 Not Found");
            echo json_encode([
                "cod" => 404,
                "msg" => "Aluno não encontrado"
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