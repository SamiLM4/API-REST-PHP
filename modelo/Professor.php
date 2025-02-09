<?php
require_once "Banco.php";

class Professor {
    private $id_Prof;
    private $nomeProf;
    private $emailProf;
    private $senhaProf;
    
   
  

    // Métodos getters e setters...

    // Método necessário pela interface JsonSerializable para serialização do objeto para JSON
    public function jsonSerialize()
    {
        // Cria um objeto stdClass para armazenar os dados do cargo
        $objetoResposta = new stdClass();
        // Define as propriedades do objeto com os valores das propriedades da classe
        $objetoResposta->id_Prof = $this->id_Prof;
        $objetoResposta->nomeProf = $this->nomeProf;
        $objetoResposta->emailProf = $this->emailProf;
        $objetoResposta->senhaProf = $this->senhaProf;

        // Retorna o objeto para serialização
        return $objetoResposta;
    }

 /*
    // Método para converter a instância para um array associativo
    public function toArray() {
        return [
            'id_prof' => $this->getId_prof(),
            'nome' => $this->getNome(),
            'data_nascimento' => $this->getData_nascimento(),
           
        ];
    }
   */ 

    public function cadastrarProf() {
        $meuBanco = new Banco();
        $conexao = $meuBanco->getConexao();

        $stmt = $conexao->prepare("INSERT INTO Professor (nome, email, senha) VALUES (?, ?, md5(?))");
        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param("sss", $this->nomeProf, $this->emailProf,$this->senhaProf);

        return $stmt->execute();
    }


    public function login() {
        $meuBanco = new Banco();
        $conexao = $meuBanco->getConexao();
        $SQL = "SELECT * FROM Professor WHERE email = ? AND senha = md5(?);";
        $prepareSQL = $conexao->prepare($SQL);
        $prepareSQL->bind_param("ss", $this->emailProf, $this->senhaProf);
        $prepareSQL->execute();
        $matrizTupla = $prepareSQL->get_result();
    
        if ($tupla = $matrizTupla->fetch_object()) {
            $this->setIdProf($tupla->id_prof);
            $this->setNomeProf($tupla->nome);
            $this->setEmailProf($tupla->email);
            $this->setsenhaProf($tupla->senha);
        
            return true;  // Login bem-sucedido
        }
    
        return false;  // Login falhou
    }
    

    public function readPage($pagina) {
        $itempaginas = 10;
        $inicio = ($pagina - 1) * $itempaginas;
        // Obtém a conexão com o banco de dados
        $meuBanco = new Banco();
        // Define a consulta SQL para selecionar todos os cargos ordenados por nome
        $stm = $meuBanco->getConexao()->prepare("SELECT * FROM Professor limit ?,?");
        $stm->bind_param("ii", $inicio, $itempaginas);
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
        $id_Prof = $this->id_Prof;

        $stm = $meuBanco->getConexao()->prepare("SELECT * FROM Professor WHERE id_prof = ?");
        $stm->bind_param("i", $this->id_Prof);
        $stm->execute();
        $resultado = $stm->get_result();

        if ($resultado->num_rows === 0) {
            return null; // Se nenhum curso for encontrado, retorna null
        }

        $linha = $resultado->fetch_object();
        $Professor = new Professor(); // Instancia um novo objeto Curso

        // Define as propriedades do curso com os valores do banco de dados
        $Professor->setIdProf($linha->id_prof);
        $Professor->setNomeProf($linha->nome);
        $Professor->setEmailProf($linha->email);
        $Professor->setSenhaProf($linha->senha);

        return $Professor; // Retorna o objeto Curso encontrado
    }

   


        public function update() {
            $meuBanco = new Banco();
            $sql="UPDATE Professor SET nome=?, email=?, senha=md5(?) WHERE id_prof = ? ";
            $stm = $meuBanco->getConexao()->prepare($sql);
    
            if ($stm === false) {
                // Handle error if the statement couldn't be prepared
                return false;
            }
    
            // Tipos de parâmetros: "s" para strings, "d" para doubles, "i" para inteiros
            $stm->bind_param("sssi", $this->nomeProf, $this->emailProf,$this->senhaProf ,$this->id_Prof);
            
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
        $SQL = "DELETE FROM Professor WHERE id_prof = ?;"; 

        // Prepara a consulta
        if ($prepareSQL = $conexao->prepare($SQL)) {
            // Define o parâmetro da consulta com o ID do curso
            $prepareSQL->bind_param("i", $this->id_Prof);

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

          // Getter e Setter para id_Prof
          public function getIdProf() {
            return $this->id_Prof;
        }
    
        public function setIdProf($id_Prof) {
            $this->id_Prof = $id_Prof;
        }
    
        // Getter e Setter para nomeProf
        public function getNomeProf() {
            return $this->nomeProf;
        }
    
        public function setNomeProf($nomeProf) {
            $this->nomeProf = $nomeProf;
        }
    
        // Getter e Setter para emailProf
        public function getEmailProf() {
            return $this->emailProf;
        }
    
        public function setEmailProf($emailProf) {
            $this->emailProf = $emailProf;
        }
    
        // Getter e Setter para senhaProf
        public function getSenhaProf() {
            return $this->senhaProf;
        }
    
        public function setSenhaProf($senhaProf) {
            $this->senhaProf = $senhaProf;
        }
    
}
?>
