<?php

require_once "modelo/Curso.php";

$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);


$vetor = explode("/", $_SERVER['REQUEST_URI']);
$id_curso =  $vetor[2];

$nome_curso = $obj->nome_curso;
$preco_curso_por_mes = $obj->preco_curso_por_mes;
$anos_conclusao = $obj->anos_conclusao;
$id_professor = $obj->id_professor; 

/*

{
    "nome_curso": "",
    "preco_curso_por_mes": ,
    "anos_conclusao": ,
    "id_professor" 
}

*/


// Remover tags HTML e PHP
$id_curso = strip_tags($id_curso);
$nome_curso = strip_tags($nome_curso);
$preco_curso_por_mes = strip_tags($preco_curso_por_mes);
$anos_conclusao = strip_tags($anos_conclusao);
$id_professor = strip_tags($id_professor);

$resposta = array();
$verificador = 0;

// Verificação se o nome do curso está vazio
if ($nome_curso == '') {
    $resposta['cod'] = 3;
    $resposta['msg'] = "nome nao pode ser vazio";
    $verificador = 1;
} else if ($anos_conclusao == '') {
    $resposta['cod'] = 3;
    $resposta['msg'] = "anos nao pode ser vazio";
    $verificador = 1;
}

// Verificação se os anos de conclusão e preço do curso são válidos
if (!is_numeric($preco_curso_por_mes)) {
    $resposta['cod'] = 3;
    $resposta['msg'] = "preco_curso deve ser um número";
    $verificador = 1;
}

if (!is_numeric($anos_conclusao)) {
    $resposta['cod'] = 3;
    $resposta['msg'] = "anos_conclusao deve ser um número";
    $verificador = 1;
}

if (!is_numeric($id_professor)) { // Verificação do id_professor
    $resposta['cod'] = 3;
    $resposta['msg'] = "id_professor deve ser um número";
    $verificador = 1;
}

if ($verificador == 0) {
    // Instanciar e atualizar o curso
    $curso = new Curso();
    // Certifique-se de que $rota[2] está definido corretamente
    $curso->setIdcurso($id_curso );
    $curso->setNomecurso($nome_curso);
    $curso->setPrecocurso_por_mes($preco_curso_por_mes);
    $curso->setAnosconclusao($anos_conclusao);
    $curso->setIdprofessor($id_professor); // Definindo o id_professor

    $resultado = $curso->update();
    if ($resultado == true) {
        header("HTTP/1.1 201 Created");
        $resposta['cod'] = 4;
        $resposta['msg'] = "Atualizado com sucesso!!!";
        $resposta['curso'] = $curso;
    } else {
        header("HTTP/1.1 500 Internal Server Error");
        $resposta['cod'] = 5;
        $resposta['msg'] = "ERRO ao Atualizar o curso";
    }
}

echo json_encode($resposta);

?>

