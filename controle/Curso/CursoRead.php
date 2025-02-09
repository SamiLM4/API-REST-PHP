<?php
    require_once "modelo/Curso.php";

    $vetor = explode("/", $_SERVER['REQUEST_URI']);
    $pagina =  $vetor[2];

    $curso = new Curso();
    $cursos = $curso->readPage($pagina);

    header("Content-Type: application/json");
    if ($cursos) {
        header("HTTP/1.1 200 OK");

        echo json_encode([
            "cod" => 200,
            "msg" => "busca realizado com sucesso!!!",
            "Professor" => [ 
                "dados" => $cursos
            ],
        ]);
    } else {
        header("HTTP/1.1 404 Not Found");
        echo json_encode(["mensagem" => "Nenhum curso encontrado."]);
    }

?>
