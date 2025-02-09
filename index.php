<?php
// Inclui o arquivo Router.php, que provavelmente contém a definição da classe Router
require_once ("modelo/Router.php");

// Instancia um objeto da classe Router
$roteador = new Router();

// Define uma rota para a obtenção de todos os cursos
$roteador->get("/cursos/(\d+)", function ($pagina) {
    // Requer o arquivo de controle responsável por obter todos os curso
    require_once ("controle/Curso/CursoRead.php");
});

// Define uma rota para a obtenção de um curso específico pelo ID
$roteador->get("/cursosid/(\d+)", function ($id_curso) {
    // Requer o arquivo de controle responsável por obter um curso pelo ID
    require_once ("controle/Curso/CursoReadID.php");
});

// Define uma rota para a criação de um novo curso
$roteador->post("/cursos", function () {
    // Requer o arquivo de controle responsável por criar um novo curso
    require_once ("controle/Curso/CursoCadastrar.php");
});


// Define uma rota para a atualização de um curso existente pelo ID
$roteador->put("/cursos/(\d+)", function ($id_curso) {
    // Requer o arquivo de controle responsável por atualizar um curso pelo ID
    require_once ("controle/Curso/CursoUpdate.php");
});

// Define uma rota para a exclusão de um curso existente pelo ID
$roteador->delete("/cursos/(\d+)", function ($id_curso) {
    // Requer o arquivo de controle responsável por excluir um curso pelo ID
    require_once ("controle/Curso/CursoDelete.php");
});



// Define uma rota para a criação de um novo curso
$roteador->post("/professores/login", function () {
    // Requer o arquivo de controle responsável por criar um novo curso
    require_once ("controle/Professor/ProfessorLogin.php");
});

$roteador->get("/professores/(\d+)", function ($pagina) {
    // Requer o arquivo de controle responsável por obter todos os curso
    require_once ("controle/Professor/ProfessorRead.php");
});

// Define uma rota para a obtenção de um curso específico pelo ID
$roteador->get("/professoresid/(\d+)", function ($id_prof) {
    // Requer o arquivo de controle responsável por obter um curso pelo ID
    require_once ("controle/Professor/ProfessorReadID.php");
});

// Define uma rota para a criação de um novo curso
$roteador->post("/professores", function () {
    // Requer o arquivo de controle responsável por criar um novo curso
    require_once ("controle/Professor/ProfessorCadastrar.php");
});


// Define uma rota para a atualização de um curso existente pelo ID
$roteador->put("/professores/(\d+)", function ($id_prof) {
    // Requer o arquivo de controle responsável por atualizar um curso pelo ID
    require_once ("controle/Professor/ProfessorUpdate.php");
});

// Define uma rota para a exclusão de um curso existente pelo ID
$roteador->delete("/professores/(\d+)", function ($id_prof) {
    // Requer o arquivo de controle responsável por excluir um curso pelo ID
    require_once ("controle/Professor/ProfessorDelete.php");
});


// Define uma rota para a criação de um novo curso
$roteador->post("/alunos/login", function () {
    // Requer o arquivo de controle responsável por criar um novo curso
    require_once ("controle/Aluno/AlunoLogin.php");
});


// Define uma rota para a obtenção de todos os cursos
$roteador->get("/alunos/(\d+)", function ($pagina) {
    // Requer o arquivo de controle responsável por obter todos os curso
    require_once ("controle/Aluno/AlunoRead.php");
});

// Define uma rota para a obtenção de um curso específico pelo ID
$roteador->get("/alunosid/(\d+)", function ($id_a) {
    // Requer o arquivo de controle responsável por obter um curso pelo ID
    require_once ("controle/Aluno/AlunoReadID.php");
});

// Define uma rota para a criação de um novo curso
$roteador->post("/alunos", function () {
    // Requer o arquivo de controle responsável por criar um novo curso
    require_once ("controle/Aluno/AlunoCadastrar.php");
});


// Define uma rota para a atualização de um curso existente pelo ID
$roteador->put("/alunos/(\d+)", function ($id_a) {
    // Requer o arquivo de controle responsável por atualizar um curso pelo ID
    require_once ("controle/Aluno/AlunoUpdate.php");
});

// Define uma rota para a exclusão de um curso existente pelo ID
$roteador->delete("/alunos/(\d+)", function ($id_a) {
    // Requer o arquivo de controle responsável por excluir um curso pelo ID
    require_once ("controle/Aluno/AlunoDelete.php");
});

// Executa o roteador para lidar com as requisições
$roteador->run();
?>