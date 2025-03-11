<?php
require_once 'Database.php';

class Disciplina {
    private PDO $conn;
    private string $table = 'course_disciplines';

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    // Listar todas as disciplinas
    public function getAllDisciplines(): array {
        $sql = "SELECT d.*, c.name AS course_name, t.name AS teacher_name
                FROM {$this->table} d
                JOIN courses c ON d.course_id = c.id
                JOIN teachers t ON d.teacher_id = t.id
                ORDER BY d.created_at DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar uma disciplina pelo ID
    public function getDisciplineById(int $id): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Buscar todas as disciplinas de um curso específico
    public function getDisciplineByCourseId(int $courseId): array {
        $sql = "SELECT d.*, c.name AS course_name, t.name AS teacher_name
                FROM course_disciplines d
                JOIN courses c ON d.course_id = c.id
                JOIN teachers t ON d.teacher_id = t.id
                WHERE d.course_id = :course_id
                ORDER BY d.name ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['course_id' => $courseId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDisciplineByCourseId002(int $courseId): array {
        $sql = "
            SELECT d.id, d.name, d.code 
            FROM course_disciplines d
            WHERE d.course_id = :course_id
            ORDER BY d.name ASC
        ";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['course_id' => $courseId]);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Buscar todas as disciplinas ministradas por um professor específico
    /* Saida: 
    Array (
            [0] => Array
                (
                    [teacher_id] => 5
                    [teacher_name] => João Silva
                    [course_id] => 2
                    [course_name] => Engenharia
                    [course_code] => ENG-001
                    [discipline_id] => 1
                    [discipline_name] => Matemática
                    [discipline_code] => MAT-101
                    [discipline_status] => ativo
                )
            )

    */
    public function getDisciplineByTeacherId(int $teacherId): array {
        $sql = "SELECT 
                    t.id AS teacher_id, 
                    t.name AS teacher_name,
                    c.id AS course_id, 
                    c.name AS course_name, 
                    c.code AS course_code, 
                    d.id AS discipline_id, 
                    d.name AS discipline_name, 
                    d.code AS discipline_code, 
                    d.status AS discipline_status
                FROM course_disciplines d
                JOIN courses c ON d.course_id = c.id
                JOIN teachers t ON d.teacher_id = t.id
                WHERE d.teacher_id = :teacher_id
                ORDER BY c.name ASC, d.name ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['teacher_id' => $teacherId]);
        $disciplinas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $disciplinas; // Retorna o array com as disciplinas
    }
    


    /*
    // Buscar todas as disciplinas ministradas por um professor específico
    public function getDisciplineByTeacherId(int $teacherId): array {
        $sql = "SELECT d.*, c.name AS course_name, t.name AS teacher_name
                FROM course_disciplines d
                JOIN courses c ON d.course_id = c.id
                JOIN teachers t ON d.teacher_id = t.id
                WHERE d.teacher_id = :teacher_id
                ORDER BY d.name ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['teacher_id' => $teacherId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } */


    // Adicionar nova disciplina
    public function addDiscipline(int $course_id, string $name, string $code, int $teacher_id, string $status): bool {
        // Verificar se os campos obrigatórios estão vazios
        if (empty($course_id) || empty($name) || empty($code) || empty($teacher_id) || empty($status)) {
            echo "<script>window.location.href = '". BASE_URL ."/admin/adicionar_disciplina.php?error=campos_obrigatorios';</script>";
            exit();
        }

        if ($status == "Selecione") {
            $status = "inativo";
        }

        $sql = "INSERT INTO {$this->table} (course_id, name, code, teacher_id, status) 
                VALUES (:course_id, :name, :code, :teacher_id, :status)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'course_id' => $course_id,
            'name' => $name,
            'code' => $code,
            'teacher_id' => $teacher_id,
            'status' => $status
        ]);
    }

    // Editar disciplina existente
    public function updateDiscipline(int $id, int $course_id, string $name, string $code, int $teacher_id, string $status): bool {
        // Verificar se os campos obrigatórios estão vazios
        if (empty($course_id) || empty($name) || empty($code) || empty($teacher_id) || empty($status)) {
            echo "<script>window.location.href = '". BASE_URL ."/admin/adicionar_disciplina.php?error=campos_obrigatorios';</script>";
            exit();
        }

        
        if ($status == "Selecione") {
            $status = "inativo";
        }

        $sql = "UPDATE {$this->table} SET 
                    course_id = :course_id,
                    name = :name, 
                    code = :code, 
                    teacher_id = :teacher_id, 
                    status = :status,
                    updated_at = NOW()
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'course_id' => $course_id,
            'name' => $name,
            'code' => $code,
            'teacher_id' => $teacher_id,
            'status' => $status
        ]);
    }

    // Deletar disciplina com verificação de existência
    public function deleteDiscipline(int $id): bool {
        // Verificar se a disciplina existe antes de deletar
        $sqlCheck = "SELECT id FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->execute(['id' => $id]);

        if (!$stmtCheck->fetch()) {
            // Disciplina não encontrada, redirecionar
            header("Location: /admin/index.php?error=disciplina_nao_encontrada");
            exit();
        }

        // Disciplina encontrada, prosseguir com a exclusão
        $sqlDelete = "DELETE FROM {$this->table} WHERE id = :id";
        $stmtDelete = $this->conn->prepare($sqlDelete);

        return $stmtDelete->execute(['id' => $id]);
    }
}
?>
