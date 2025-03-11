<?php
require_once 'Database.php'; // Classe de conexão com o banco de dados

class Auth {
    private PDO $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
        // session_start();
    }

    // Método de login que verifica o tipo de usuário
    public function login(string $email, string $password): bool {
        // Localiza o usuário nas três tabelas e obtém seu tipo
        $userType = $this->determineUserType($email);
        
        if (!$userType) {
            return false; // Usuário não encontrado
        }

        // Obter os dados do usuário da tabela correta
        $user = $this->getUserFromTable($email, $userType);

        // Verifica se a senha está correta
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION[SESSION_NAME] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'type' => $userType, // Armazena o tipo correto do usuário
            ];

            // Redirecionar para a página apropriada com base no tipo de usuário
            //$this->redirectBasedOnType($userType);

            return true;
        }

        return false;
    }

    // Registra um novo Admin na tabela admins.
    public function registerNewAdmin($name, $email, $password) {
        try {
            // Verifica se o e-mail já existe
            $sql = "SELECT id FROM admins WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                return ['status' => false, 'message' => 'Este e-mail já está cadastrado.'];
            }

            // Hash da senha
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Inserir novo administrador
            $sql = "INSERT INTO admins (name, email, password) VALUES (:name, :email, :password)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

            if ($stmt->execute()) {
                return ['status' => true, 'message' => 'Administrador cadastrado com sucesso!'];
            } else {
                return ['status' => false, 'message' => 'Erro ao cadastrar o administrador.'];
            }
        } catch (PDOException $e) {
            return ['status' => false, 'message' => 'Erro no banco de dados: ' . $e->getMessage()];
        }
    }



    // Método otimizado para determinar o tipo de usuário
    private function determineUserType(string $email): string {
        $queries = [
            'admin' => 'SELECT id FROM admins WHERE email = :email LIMIT 1',
            'professor' => 'SELECT id FROM teachers WHERE email = :email LIMIT 1',
            'aluno' => 'SELECT id FROM students WHERE email = :email LIMIT 1'
        ];

        foreach ($queries as $type => $sql) {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->fetch()) {
                return $type; // Retorna 'admin', 'professor' ou 'aluno'
            }
        }

        return ''; // Caso o usuário não seja encontrado em nenhuma tabela
    }

    // Método para obter os dados do usuário da tabela correta
    private function getUserFromTable(string $email, string $table) {
        $sql = "SELECT * FROM " . ($table === 'admin' ? 'admins' : ($table === 'professor' ? 'teachers' : 'students')) . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Função para retornar o caminho correto com base no tipo de usuário
    function getRedirectPath(string $userType): string {
        $redirectPaths = [
            'admin' => 'admin/index.php',
            'professor' => 'professor/index.php',
            'aluno' => 'aluno/index.php'
        ];

        return $redirectPaths[$userType] ?? 'login.php?error=tipo_invalido';
    }

    // Método para deslogar
    public function logout() {
        session_unset(); // Limpa todas as variáveis de sessão
        session_destroy(); // Destroi a sessão
        header("Location: ". BASE_URL ."login.php");
        exit();
    }

    // Compara newPassword e confirmNewPassword se forem iguais volta true se não volta false.
    public static function confirmPasswordsMatch(string $newPassword, string $confirmNewPassword): bool {
        return $newPassword === $confirmNewPassword;
    }
}
?>
