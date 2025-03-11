<?php
require_once 'Database.php';

class Curso {
    private PDO $conn;
    private string $table = 'courses';

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    // Listar todos os cursos
    public function getAllCourses(): array {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar um curso pelo ID
    public function getCourseById(int $id): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Adicionar novo curso
    public function addCourse(string $name, ?string $description, string $code, string $status): bool {
        // Verificar se os campos obrigatórios estão vazios
        if (empty($name) || empty($description) || empty($code) || empty($status)) {
            echo "<script>window.location.href = '". BASE_URL ."/admin/adicionar_curso.php?error=campos_obrigatorios';</script>";
            exit();
        }
        if ($status == "Selecione") {
            $status = "inativo";
        }
        $sql = "INSERT INTO {$this->table} (name, description, code, status) 
                VALUES (:name, :description, :code, :status)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'name' => $name,
            'description' => $description,
            'code' => $code,
            'status' => $status
        ]);
    }

    // Editar curso existente
    public function updateCourse(int $id, string $name, ?string $description, string $code, string $status): bool {
        if ($status == "Selecione") {
            $status = "inativo";
        }
        $sql = "UPDATE {$this->table} SET 
                    name = :name, 
                    description = :description, 
                    code = :code, 
                    status = :status,
                    updated_at = NOW()
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'code' => $code,
            'status' => $status
        ]);
    }

    // Deletar curso com verificação de existência
    public function deleteCourse(int $id): bool {
        // Verificar se o curso existe antes de deletar
        $sqlCheck = "SELECT id FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->execute(['id' => $id]);
        
        if (!$stmtCheck->fetch()) {
            // Curso não encontrado, redirecionar
            header("Location: /admin/index.php?error=curso_nao_encontrado");
            exit();
        }

        // Curso encontrado, prosseguir com a exclusão
        $sqlDelete = "DELETE FROM {$this->table} WHERE id = :id";
        $stmtDelete = $this->conn->prepare($sqlDelete);
        
        return $stmtDelete->execute(['id' => $id]);
    }
}
?>
