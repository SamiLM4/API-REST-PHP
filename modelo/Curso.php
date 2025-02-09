<?php
require_once "Banco.php";

class Curso {
    private $id_curso;
    private $nome_curso;
    private $preco_curso_por_mes;
    private $anos_conclusao;
    private $id_professor;

    // Métodos getters e setters...

    // Método necessário pela interface JsonSerializable para serialização do objeto para JSON
    public function jsonSerialize()
    {
        // Cria um objeto stdClass para armazenar os dados do cargo
        $objetoResposta = new stdClass();
        // Define as propriedades do objeto com os valores das propriedades da classe
        $objetoResposta->id_curso = $this->id_curso;
        $objetoResposta->nome_curso = $this->nome_curso;
        $objetoResposta->preco_curso_por_mes = $this->preco_curso_por_mes;
        $objetoResposta->anos_conclusao = $this->anos_conclusao;
        $objetoResposta->id_professor = $this->id_professor;

        // Retorna o objeto para serialização
        return $objetoResposta;
    }



    // Método para converter a instância para um array associativo
    public function toArray() {
        return [
            'id_curso' => $this->getIdCurso(),
            'nome_curso' => $this->getNomeCurso(),
            'preco_curso_por_mes' => $this->getPrecoCurso_por_mes(),
            'anos_conclusao' => $this->getAnosConclusao(),
            'id_professor' => $this->getIdProfessor()
        ];
    }
    

    public function cadastrar() {
        $meuBanco = new Banco();
        $conexao = $meuBanco->getConexao();

        $stm = $conexao->prepare("INSERT INTO Curso (nome_curso, preco_curso_por_mes, anos_conclusao, id_professor) VALUES (?, ?, ?, ?)");

        $stm->bind_param("siii", $this->nome_curso, $this->preco_curso_por_mes, $this->anos_conclusao, $this->id_professor);

        $resultado = $stm->execute();
        
        return $resultado;
    }

    public function readPage($pagina){
        $itempaginas = 10;
        $incio = ($pagina - 1) * $itempaginas;
        // Obtém a conexão com o banco de dados
        $meuBanco = new Banco();
        // Define a consulta SQL para selecionar todos os cargos ordenados por nome
        $stm = $meuBanco->getConexao()->prepare("SELECT * FROM Curso limit ?,?");
        $stm->bind_param("ii", $incio, $itempaginas);
        $stm->execute();
        $executou = $stm->get_result();

        if (!$executou) {
            throw new Exception("Erro ao executar a consulta SQL");
        }
        $executou = $executou->fetch_all(MYSQLI_ASSOC);
        return $executou;
    }

    public function readID() {
        $meuBanco = new Banco();
        $id_curso = $this->id_curso;

        $stm = $meuBanco->getConexao()->prepare("SELECT * FROM Curso WHERE id_curso = ?");
        $stm->bind_param("i", $this->id_curso);
        $stm->execute();
        $resultado = $stm->get_result();

        if ($resultado->num_rows === 0) {
            return null; // Se nenhum curso for encontrado, retorna null
        }

        $linha = $resultado->fetch_object();
        $curso = new Curso(); // Instancia um novo objeto Curso

        // Define as propriedades do curso com os valores do banco de dados
        $curso->setIdCurso($linha->id_curso);
        $curso->setNomeCurso($linha->nome_curso);
        $curso->setPrecoCurso_por_mes($linha->preco_curso_por_mes);
        $curso->setAnosConclusao($linha->anos_conclusao);
        $curso->setIdProfessor($linha->id_professor);

        return $curso; // Retorna o objeto Curso encontrado
    }

    public function update() {
        $meuBanco = new Banco();
        $sql = "UPDATE Curso SET nome_curso = ?, preco_curso_por_mes = ?, anos_conclusao = ?, id_professor = ? WHERE id_curso = ?";
        $stm = $meuBanco->getConexao()->prepare($sql);

        if ($stm === false) {
            // Handle error if the statement couldn't be prepared
            return false;
        }

        // Tipos de parâmetros: "s" para strings, "d" para doubles, "i" para inteiros
        $stm->bind_param("sdiii", $this->nome_curso, $this->preco_curso_por_mes, $this->anos_conclusao, $this->id_professor, $this->id_curso);
        
        $resultado = $stm->execute();

        return $resultado;
    }

    public function delete() {
        $meuBanco= new Banco();
        $conexao = $meuBanco->getConexao();

        // Verifica se a conexão foi bem-sucedida
        if ($conexao->connect_error) {
            die("Falha na conexão: " . $conexao->connect_error);
        }

        // Define a consulta SQL para excluir um curso pelo ID
        $SQL = "DELETE FROM Curso WHERE id_curso = ?;"; 

        // Prepara a consulta
        if ($prepareSQL = $conexao->prepare($SQL)) {
            // Define o parâmetro da consulta com o ID do curso
            $prepareSQL->bind_param("i", $this->id_curso);

            // Executa a consulta
            if ($prepareSQL->execute()) {
                // Fecha a consulta preparada
                $prepareSQL->close();
                return true;
            } else {
                // Exibe o erro de execução da consulta
                echo "Erro na execução da consulta: " . $prepareSQL->error;
                return false;
            }
        } else {
            // Exibe o erro na preparação da consulta
            echo "Erro na preparação da consulta: " . $conexao->error;
            return false;
        }
    }

    public function getIdCurso() {
        return $this->id_curso;
    }

    public function setIdCurso($id_curso) {
        $this->id_curso = $id_curso;
    }

    public function getNomeCurso() {
        return $this->nome_curso;
    }

    public function setNomeCurso($nome_curso) {
        $this->nome_curso = $nome_curso;
    }

    public function getPrecoCurso_por_mes() {
        return $this->preco_curso_por_mes;
    }

    public function setPrecoCurso_por_mes($preco_curso_por_mes) {
        $this->preco_curso_por_mes = $preco_curso_por_mes;
    }

    public function getAnosConclusao() {
        return $this->anos_conclusao;
    }

    public function setAnosConclusao($anos_conclusao) {
        $this->anos_conclusao = $anos_conclusao;
    }

    public function getIdProfessor() {
        return $this->id_professor;
    }

    public function setIdProfessor($id_professor) {
        $this->id_professor = $id_professor;
    }
    
}
?>
