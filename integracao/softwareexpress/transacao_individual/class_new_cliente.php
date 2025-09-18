<?php
class Database {
    private $host = 'localhost'; // seu host
    private $db_name = 'nome_do_seu_banco_de_dados'; // nome do seu banco de dados
    private $username = 'username'; // seu usuário
    private $password = 'password'; // sua senha
    public $conn;

    public function dbConnection() {
        $this->conn = null;        
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Erro na conexão: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

class NewClientes {
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->db_conn = $this->db->dbConnection();
    }

    public function add_new_client($data){
        $results = [];
        foreach($this->servers as $server){
            $conn = mysqli_connect($server['ip'], $server['user'], $server['pass'], $server['db']);
            $sql = "INSERT INTO sys_inss_clientes (cliente_cpf, cliente_nome, cliente_nascimento, cliente_sexo, cliente_endereco, cliente_endereco_complemento, cliente_bairro, cliente_cidade, cliente_uf, cliente_cep, cliente_celular, cliente_email) 
                VALUES ('$data['cpf']', '$data['nome']', '$data['dataNascimento']', '$data['sexo']', '$data['logradouro']', '$data['complemento']', '$data['bairro']', '$data['cidade']', '$data['estado']', '$data['cep']', '$data['celular']', '$data['email']''])
                ON DUPLICATE KEY UPDATE
                cliente_nome = VALUES(cliente_nome),
                cliente_nascimento = VALUES(cliente_nascimento),
                cliente_sexo = VALUES(cliente_sexo),
                cliente_endereco = VALUES(cliente_endereco),
                cliente_endereco_complemento = VALUES(cliente_endereco_complemento),
                cliente_bairro = VALUES(cliente_bairro),
                cliente_cidade = VALUES(cliente_cidade),
                cliente_uf = VALUES(cliente_uf),
                cliente_cep = VALUES(cliente_cep),
                cliente_celular = VALUES(cliente_celular),
                cliente_email = VALUES(cliente_email)";
        }
    }
}
?>
