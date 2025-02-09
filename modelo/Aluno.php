<?php
require_once "Banco.php";

class Aluno {
    private $idAluno;
    private $nomeAluno;
    private $emailAluno;
    private $senhaAluno;
    private $id_cursoAluno;
    
  

    // Métodos getters e setters...

    // Método necessário pela interface JsonSerializable para serialização do objeto para JSON
    public function jsonSerialize()
    {
        // Cria um objeto stdClass para armazenar os dados do cargo
        $objetoResposta = new stdClass();
        // Define as propriedades do objeto com os valores das propriedades da classe
        $objetoResposta->idAluno = $this->idAluno;
        $objetoResposta->nomeAluno = $this->nomeAluno;
        $objetoResposta->emailAluno = $this->emailAluno;
        $objetoResposta->senhaAluno = $this->senhaAluno;
        $objetoResposta->id_cursoAluno = $this->id_cursoAluno;

        // Retorna o objeto para serialização
        return $objetoResposta;
    }


    

    // Método para converter a instância para um array associativo
    public function toArray() {
        return [
            'id_a' =>$this->getIdAluno(),
            'nome' =>$this->getNomeAluno(),
            'email' =>$this->getEmailAluno(),
            'id_curso' =>$this->getIdCursoAluno()
          
        ];
    }


    public function cadastrarAluno() {
        $meuBanco = new Banco();
        $conexao = $meuBanco->getConexao();

        $stmt = $conexao->prepare("INSERT INTO Aluno(nome, email, senha, id_curso) VALUES (?, ?, md5(?), ?)");
        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param("sssi", $this->nomeAluno, $this->emailAluno, $this->senhaAluno, $this->id_cursoAluno);

        return $stmt->execute();
    }

    public function login() {
        $meuBanco = new Banco();
        $conexao = $meuBanco->getConexao();
        $SQL = "SELECT * FROM Aluno WHERE email = ? AND senha = md5(?);";
        $prepareSQL = $conexao->prepare($SQL);
        $prepareSQL->bind_param("ss", $this->emailAluno, $this->senhaAluno);
        $prepareSQL->execute();
        $matrizTupla = $prepareSQL->get_result();
    
        if ($tupla = $matrizTupla->fetch_object()) {
            $this->setidAluno($tupla->id_a);
            $this->setNomeAluno($tupla->nome);
            $this->setEmailAluno($tupla->email);
            $this->setIdCursoAluno($tupla->id_curso);
            return true;  // Login bem-sucedido
        }
    
        return false;  // Login falhou
    }
    
    

    public function readPage($pagina) {
        $itempagina = 10;
        $inicio = ($pagina - 1)*$itempagina;
        // Obtém a conexão com o banco de dados
        $meuBanco = new Banco();
        // Define a consulta SQL para selecionar todos os cargos ordenados por nome
        $stm = $meuBanco->getConexao()->prepare("SELECT * FROM Aluno Limit ?,?");

        $stm->bind_param("ii", $inicio, $itempagina);

        $stm->execute();
        $resultado = $stm->get_result();

        if (!$resultado) {
            throw new Exception("Erro ao executar a consulta SQL");
        }
        $resultado = $resultado->fetch_all(MYSQLI_ASSOC);
        return $resultado;
/*
        if ($resultado->num_rows === 0) {
            return null; // Se nenhum curso for encontrado, retorna null
        }

        // Inicializa um vetor para armazenar os cursos
        $vetorAlunos = array();
        // Itera sobre as tuplas do resultado
        while ($tupla = $resultado->fetch_object()) {
            // Cria uma nova instância de Curso para cada tupla encontrada
            $aluno = new Aluno();
            // Define o ID e o nome do curso na instância
            $aluno->setIdAluno($tupla->id_a);
            $aluno->setNomeAluno($tupla->nome);
            $aluno->setEmailAluno($tupla->email);
            $aluno->setSenhaAluno($tupla->id_curso);
            $aluno->setSenhaAluno($tupla->id_curso);
           
            $vetorAlunos[] = $aluno;
        }
        // Retorna o vetor com os cursos encontrados
        return $vetorAlunos;
*/
    }

    public function readID() {
        $meuBanco = new Banco();

        $stm = $meuBanco->getConexao()->prepare("SELECT * FROM Aluno WHERE id_a = ?");
        $stm->bind_param("i", $this->idAluno);
        $stm->execute();
        $resultado = $stm->get_result();

        if ($resultado->num_rows === 0) {
            return null; // Se nenhum curso for encontrado, retorna null
        }

        $linha = $resultado->fetch_object();
        $Aluno = new Aluno(); // Instancia um novo objeto Curso

        // Define as propriedades do curso com os valores do banco de dados
        $Aluno->setidAluno($linha->id_a);
        $Aluno->setNomeAluno($linha->nome);
        $Aluno->setEmailAluno($linha->email);
        $Aluno->setIdcursoAluno($linha->id_curso);
       

        return $Aluno; // Retorna o objeto Curso encontrado
    }

    public function update() {
        $meuBanco = new Banco();
        
        $sql="UPDATE Aluno SET nome=?,email=?,senha=md5(?),id_curso=? WHERE  id_a = ? ";
        $stm = $meuBanco->getConexao()->prepare($sql);

        // Tipos de parâmetros: "s" para strings, "d" para doubles, "i" para inteiros
        $stm->bind_param("sssii", $this->nomeAluno, $this->emailAluno, $this->senhaAluno,$this->id_cursoAluno,$this->idAluno);
        
        $resultado = $stm->execute();
        
        return $resultado;
    }

    public function delete() {
        $meuBanco= new Banco();
        $conexao =  $meuBanco->getConexao();

        // Verifica se a conexão foi bem-sucedida
        if ($conexao->connect_error) {
            die("Falha na conexão: " . $conexao->connect_error);
        }

        // Define a consulta SQL para excluir um curso pelo ID
        $SQL = "DELETE FROM Aluno WHERE id_a = ?;"; 

        // Prepara a consulta
        if ($prepareSQL = $conexao->prepare($SQL)) {
            // Define o parâmetro da consulta com o ID do curso
            $prepareSQL->bind_param("i", $this->idAluno);

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
    ///////////////////////////////////////////////////
       // Getter e Setter para idAluno
       public function getIdAluno() {
        return $this->idAluno;
    }

    public function setIdAluno($idAluno) {
        $this->idAluno = $idAluno;
    }

    // Getter e Setter para nomeAluno
    public function getNomeAluno() {
        return $this->nomeAluno;
    }

    public function setNomeAluno($nomeAluno) {
        $this->nomeAluno = $nomeAluno;
    }

    // Getter e Setter para emailAluno
    public function getEmailAluno() {
        return $this->emailAluno;
    }

    public function setEmailAluno($emailAluno) {
        $this->emailAluno = $emailAluno;
    }

    // Getter e Setter para senhaAluno
    public function getSenhaAluno() {
        return $this->senhaAluno;
    }

    public function setSenhaAluno($senhaAluno) {
        $this->senhaAluno = $senhaAluno;
    }

    // Getter e Setter para id_cursoAluno
    public function getIdCursoAluno() {
        return $this->id_cursoAluno;
    }

    public function setIdCursoAluno($id_cursoAluno) {
        $this->id_cursoAluno = $id_cursoAluno;
    }

}
?>
