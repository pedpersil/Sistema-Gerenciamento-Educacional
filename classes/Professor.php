<?php
require_once 'Database.php';

class Professor {
    private PDO $conn;
    private string $table = 'teachers';

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    // Listar todos os professores
    public function getAllTeachers(): array {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtem todas as informações do professor
    /*  Saida do metodo.
    Array
(
    [id] => 10
    [name] => Carlos Oliveira
    [telephone] => (11) 99999-8888
    [email] => carlos.oliveira@email.com
    [cpf] => 123.456.789-00
    [identity] => RG-1234567
    [created_at] => 2023-02-15 10:30:00
    [courses] => Ciência da Computação, Engenharia de Software
    [disciplines] => Array
        (
            [0] => Array
                (
                    [id] => 5
                    [name] => Estruturas de Dados
                    [code] => ED101
                    [course_name] => Ciência da Computação
                )
            [1] => Array
                (
                    [id] => 7
                    [name] => Programação Orientada a Objetos
                    [code] => POO202
                    [course_name] => Engenharia de Software
                )
            [2] => Array
                (
                    [id] => 12
                    [name] => Banco de Dados
                    [code] => BD303
                    [course_name] => Ciência da Computação
                )
        )
)
    */
    public function getAllInfoTeacherById(int $id): ?array {
        // Buscar dados do professor
        $sql = "SELECT t.*, GROUP_CONCAT(DISTINCT c.name SEPARATOR ', ') AS courses
                FROM teachers t
                LEFT JOIN course_disciplines cd ON t.id = cd.teacher_id
                LEFT JOIN courses c ON cd.course_id = c.id
                WHERE t.id = :id
                GROUP BY t.id";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$teacher) {
            return null; // Retorna null se o professor não for encontrado
        }
    
        // Buscar disciplinas que o professor ministra
        $sqlDisciplines = "SELECT d.id, d.name, d.code, c.name AS course_name 
                           FROM course_disciplines d
                           INNER JOIN courses c ON d.course_id = c.id
                           WHERE d.teacher_id = :id";
        
        $stmtDisciplines = $this->conn->prepare($sqlDisciplines);
        $stmtDisciplines->execute(['id' => $id]);
        $teacher['disciplines'] = $stmtDisciplines->fetchAll(PDO::FETCH_ASSOC);
    
        return $teacher;
    }
    
    // Buscar um professor pelo ID
    public function getTeacherById(int $id): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Buscar professor por CPF
    public function getTeacherByCPF(string $cpf): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE cpf = :cpf";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['cpf' => $cpf]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Buscar professor por identidade (RG)
    public function getTeacherByIdentity(string $identity): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE identity = :identity";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['identity' => $identity]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Pega todas as informações de um professor pelo discipline_id
     /* Exemplo de saida do metodo:
    Array
        (
            [0] => Array
                (
                    [teacher_id] => 1
                    [teacher_name] => João Silva
                    [telephone] => (11) 98765-4321
                    [email] => joao.silva@email.com
                    [cpf] => 123.456.789-00
                    [identity] => 123456789
                    [course_id] => 2
                    [course_name] => Engenharia de Software
                    [discipline_id] => 1
                    [discipline_name] => Algoritimos
                    [student_id] => 5
                    [student_name] => Maria Souza
                    [student_email] => maria.souza@email.com
                    [gender] => feminino
                    [student_identity] => 987654321
                    [student_cpf] => 987.654.321-00
                    [nationality] => Brasileira
                    [birthplace] => São Paulo
                    [birth_date] => 2000-05-15
                    [father_name] => José Souza
                    [mother_name] => Ana Souza
                    [enrollment_number] => 2023123456
                    [student_status] => cursando
                    [grade1] => 8.5
                    [grade2] => 7.0
                    [grade3] => 9.0
                    [grade4] => 8.0
                    [final_average] => 8.125
                )
        )
     */
    public function getAllInfoTeacherByDisciplineId($discipline_id){
        $sql = "
            SELECT 
                t.id AS teacher_id, 
                t.name AS teacher_name, 
                t.telephone, 
                t.email, 
                t.cpf, 
                t.identity, 
                c.id AS course_id, 
                c.name AS course_name,
                cd.id AS discipline_id,
                cd.name AS discipline_name,
                s.id AS student_id,
                s.name AS student_name,
                s.email AS student_email,
                s.gender,
                s.identity AS student_identity,
                s.cpf AS student_cpf,
                s.nationality,
                s.birthplace,
                s.birth_date,
                s.father_name,
                s.mother_name,
                s.enrollment_number,
                s.status AS student_status,
                ce.grade1,
                ce.grade2,
                ce.grade3,
                ce.grade4,
                ce.final_average
            FROM course_disciplines cd
            INNER JOIN teachers t ON cd.teacher_id = t.id
            INNER JOIN courses c ON cd.course_id = c.id
            LEFT JOIN college_enrollment ce ON cd.id = ce.discipline_id
            LEFT JOIN students s ON ce.student_id = s.id
            WHERE cd.id = :discipline_id;
        ";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':discipline_id', $discipline_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Adicionar novo professor
    public function addTeacher(string $name, string $email, string $telephone, string $password, string $cpf, string $identity): bool {
        // Verificar se os campos obrigatórios estão vazios
        if (empty($name) || empty($email) || empty($telephone) || empty($password) || empty($cpf) || empty($identity)) {
            echo "<script>window.location.href = '". BASE_URL ."/admin/adicionar_professor.php?error=campos_obrigatorios';</script>";
            exit();
        }

        // Criptografar a senha antes da inserção no banco de dados
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO {$this->table} (name, email, telephone, password, cpf, identity) 
                VALUES (:name, :email, :telephone, :password, :cpf, :identity)";
        $stmt = $this->conn->prepare($sql);
        
        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'telephone' => $telephone,
            'password' => $hashedPassword, // Senha criptografada
            'cpf' => $cpf,
            'identity' => $identity
        ]);
    }

    // Editar professor existente 
   // Editar professor existente
    public function updateTeacher(int $id, string $name, string $email, string $telephone, ?string $password, string $cpf, string $identity): bool {
        // Iniciar a query básica
        $sql = "UPDATE {$this->table} SET 
                    name = :name, 
                    email = :email, 
                    telephone = :telephone,
                    cpf = :cpf,
                    identity = :identity";

        // Array de parâmetros
        $params = [
            'name' => $name,
            'email' => $email,
            'telephone' => $telephone,
            'cpf' => $cpf,
            'identity' => $identity
        ];

        // Verifica se a senha foi informada
        if (!empty($password)) {
            // Criptografar a senha antes da atualização
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql .= ", password = :password";
            $params['password'] = $hashedPassword;
        }

        // Adiciona a condição WHERE no final
        $sql .= " WHERE id = :id";
        $params['id'] = $id; // Agora, id é adicionado corretamente no final

        // Preparação e execução da query
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }



    // Deletar professor com verificação de existência
    public function deleteTeacher(int $id): bool {
        // Verificar se o professor existe antes de deletar
        $sqlCheck = "SELECT id FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->execute(['id' => $id]);

        if (!$stmtCheck->fetch()) {
            // Professor não encontrado, redirecionar
            header("Location: /professor/index.php?error=teacher_not_found");
            exit();
        }

        // Professor encontrado, prosseguir com a exclusão
        $sqlDelete = "DELETE FROM {$this->table} WHERE id = :id";
        $stmtDelete = $this->conn->prepare($sqlDelete);

        return $stmtDelete->execute(['id' => $id]);
    }

    // Altera a senha do professor.
    public function changeTeacherPassword(string $teacherEmail, string $password, string $newPassword): bool {
        // Verifica se o professor existe pelo email
        $stmt = $this->conn->prepare("SELECT * FROM teachers WHERE email = :email");
        $stmt->execute(['email' => $teacherEmail]);
        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$teacher) {
            // Retorna falso se o professor não existir
            return false;
        }

        // Verifica se a senha atual está correta
        if (password_verify($password, $teacher['password'])) {
            // Atualiza a senha se a senha atual for válida
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

            $updateStmt = $this->conn->prepare("UPDATE teachers SET password = :newPassword WHERE email = :email");
            $updateStmt->execute([
                'newPassword' => $newPasswordHash,
                'email' => $teacherEmail
            ]);

            return true;  // Senha alterada com sucesso
        } else {
            // Retorna falso se a senha atual não for válida
            return false;
        }
    }
}
?>
