<?php
require_once "modelo/Curso.php";

$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);

if (!isset($obj->nome_curso) || !isset($obj->preco_curso_por_mes) || !isset($obj->anos_conclusao) || !isset($obj->id_professor)) {
    echo json_encode([
        "cod" => 400,
        "msg" => "Dados incompletos. Por favor, forneça id_curso, nome_curso, preco_curso, anos_conclusao e id_professor."
    ]);
    exit();
}

//$id_curso = $obj->id_curso;
$nome_curso = $obj->nome_curso;
$preco_curso_por_mes = $obj->preco_curso_por_mes;
$anos_conclusao = $obj->anos_conclusao;
$id_professor = $obj->id_professor;

// Sanitize input
$nome_curso = strip_tags($nome_curso);

$curso = new Curso();
//$curso->setIdCurso($id_curso);
$curso->setNomeCurso($nome_curso);
$curso->setPrecoCurso_por_mes($preco_curso_por_mes);
$curso->setAnosConclusao($anos_conclusao);
$curso->setIdProfessor($id_professor);

if ($curso->cadastrar()) {
    echo json_encode([
        "cod" => 201,
        "msg" => "Cadastrado com sucesso!!!",
        "curso" => $curso
    ]);
} else {
    echo json_encode([
        "cod" => 500,
        "msg" => "ERRO ao cadastrar o curso"
    ]);
}

/*

{
    "nome_curso": "",
    "preco_curso_por_mes": ,
    "anos_conclusao": ,
    "id_professor": 
}

*/

?>
