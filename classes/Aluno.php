<?php
require_once 'Database.php';

class Aluno {
    private PDO $conn;
    private string $table = 'students';

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    // Listar todos os alunos
    public function getAllStudents(): array {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Pega o total de estudantes.
    public function getTotalStudents(): int {
        $sql = "SELECT COUNT(*) AS total FROM {$this->table}";
        $stmt = $this->conn->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalRecords = $result['total'];

        return $totalRecords;
    }

    // Obtem o course_id pelo studentId.
    /*
    public function getStudentCourseId(int $studentId): ?int {
        $sql = "SELECT course_id FROM college_enrollment WHERE student_id = :student_id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['student_id' => $studentId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result ? (int) $result['course_id'] : null;
    }*/
    public function getStudentCourseId(int $studentId): ?int {
        $sql = "SELECT course_id FROM students WHERE id = :student_id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['student_id' => $studentId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result ? (int) $result['course_id'] : null;
    }

    /*
    // Obtém o course_id da tabela college_enrollment pelo studentId.
    public function getStudentCourseId(int $studentId): ?int {
        $sql = "SELECT course_id FROM college_enrollment WHERE student_id = :student_id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['student_id' => $studentId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se há um resultado antes de tentar acessar o índice
        return $result && isset($result['course_id']) ? (int) $result['course_id'] : null;
    }*/

    // Obter o nome do curso pelo student_id
    public function getCourseNameByStudentId(int $studentId): ?string {
        $sql = "SELECT c.name 
                FROM students s
                JOIN courses c ON s.course_id = c.id
                WHERE s.id = :student_id
                LIMIT 1";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['student_id' => $studentId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result ? $result['name'] : null;
    }
    
    // Obtem as disciplinas que o estudante está matriculado.
    public function getStudentDisciplinesIds(int $studentId): array {
        $sql = "SELECT discipline_id FROM college_enrollment WHERE student_id = :student_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['student_id' => $studentId]);
        $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
        return $results ?: []; // Retorna um array vazio se não encontrar disciplinas
    }

    // Buscar um aluno pelo ID
    public function getStudentById(int $id): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Buscar um aluno e todos os seus dados pelo Student ID
    /* O array final conterá.
    (
            [student_id] => 5
            [student_name] => João Silva
            [email] => joao.silva@email.com
            [gender] => masculino
            [identity] => 12345678
            [cpf] => 123.456.789-00
            [nationality] => Brasileira
            [birthplace] => São Paulo
            [birth_date] => 2000-05-15
            [father_name] => Carlos Silva
            [mother_name] => Maria Silva
            [enrollment_number] => 20231001
            [student_status] => cursando
            [student_created_at] => 2023-01-10 10:30:00
            [course_id] => 2
            [course_name] => Engenharia de Software
            [course_code] => ENG-SFTW-01
            [course_status] => ativo
            [discipline_id] => 7
            [discipline_name] => Programação Web
            [discipline_code] => PW-101
            [discipline_status] => ativo
            [teacher_id] => 3
            [teacher_name] => Prof. Marcos Souza
            [grade1] => 8.5
            [grade2] => 7.0
            [grade3] => 9.0
            [grade4] => 8.0
            [final_average] => 8.125
            [enrollment_date] => 2023-02-20
        )
    */
    /*
    public function getAllInfoStudentById(int $studentId): ?array {
        $sql = "SELECT 
                    s.id AS student_id,
                    s.name AS student_name,
                    s.email,
                    s.gender,
                    s.identity,
                    s.cpf,
                    s.nationality,
                    s.birthplace,
                    s.birth_date,
                    s.father_name,
                    s.mother_name,
                    s.enrollment_number,
                    s.status AS student_status,
                    s.created_at AS student_created_at,
                    c.id AS course_id,
                    c.name AS course_name,
                    c.code AS course_code,
                    c.status AS course_status,
                    d.id AS discipline_id,
                    d.name AS discipline_name,
                    d.code AS discipline_code,
                    d.status AS discipline_status,
                    t.id AS teacher_id,
                    t.name AS teacher_name,
                    e.grade1,
                    e.grade2,
                    e.grade3,
                    e.grade4,
                    e.final_average,
                    e.enrollment_date
                FROM students s
                LEFT JOIN courses c ON s.course_id = c.id
                LEFT JOIN college_enrollment e ON s.id = e.student_id
                LEFT JOIN course_disciplines d ON e.discipline_id = d.id
                LEFT JOIN teachers t ON d.teacher_id = t.id
                WHERE s.id = :student_id";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['student_id' => $studentId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $result ?: null;
    } */
    /* Saida do metodo abaixo. 
        Array
            (
                [student_id] => 5
                [student_name] => João Silva
                [email] => joao.silva@email.com
                [gender] => masculino
                [identity] => 12345678
                [cpf] => 123.456.789-00
                [nationality] => Brasileira
                [birthplace] => São Paulo
                [birth_date] => 2000-05-15
                [father_name] => Carlos Silva
                [mother_name] => Maria Silva
                [enrollment_number] => 20231001
                [student_status] => cursando
                [student_created_at] => 2023-01-10 10:30:00
                [course_id] => 2
                [course_name] => Engenharia de Software
                [course_code] => ENG-SFTW-01
                [course_status] => ativo
                [disciplines] => Array
                    (
                        [0] => 7
                        [1] => 10
                    )
                [grades] => Array
                    (
                        [0] => Array
                            (
                                [discipline_id] => 7
                                [discipline_name] => Programação Web
                                [discipline_code] => PW-101
                                [discipline_status] => ativo
                                [teacher_id] => 3
                                [teacher_name] => Prof. Marcos Souza
                                [grade1] => 8.5
                                [grade2] => 7.0
                                [grade3] => 9.0
                                [grade4] => 8.0
                                [final_average] => 8.125
                                [enrollment_date] => 2023-02-20
                            )
                        [1] => Array
                            (
                                [discipline_id] => 10
                                [discipline_name] => Banco de Dados
                                [discipline_code] => BD-202
                                [discipline_status] => ativo
                                [teacher_id] => 4
                                [teacher_name] => Prof. Ana Pereira
                                [grade1] => 7.5
                                [grade2] => 8.0
                                [grade3] => 6.5
                                [grade4] => 7.0
                                [final_average] => 7.25
                                [enrollment_date] => 2023-02-20
                            )
                    )
            )

    */
    public function getAllInfoStudentById(int $studentId): ?array {
        // Primeiro, buscar as informações principais do aluno, curso, disciplinas e notas
        $sql = "SELECT 
                    s.id AS student_id,
                    s.name AS student_name,
                    s.email,
                    s.gender,
                    s.identity,
                    s.cpf,
                    s.nationality,
                    s.birthplace,
                    s.birth_date,
                    s.father_name,
                    s.mother_name,
                    s.enrollment_number,
                    s.status AS student_status,
                    s.created_at AS student_created_at,
                    c.id AS course_id,
                    c.name AS course_name,
                    c.code AS course_code,
                    c.status AS course_status,
                    d.id AS discipline_id,
                    d.name AS discipline_name,
                    d.code AS discipline_code,
                    d.status AS discipline_status,
                    t.id AS teacher_id,
                    t.name AS teacher_name,
                    e.grade1,
                    e.grade2,
                    e.grade3,
                    e.grade4,
                    e.final_average,
                    e.enrollment_date
                FROM students s
                LEFT JOIN courses c ON s.course_id = c.id
                LEFT JOIN college_enrollment e ON s.id = e.student_id
                LEFT JOIN course_disciplines d ON e.discipline_id = d.id
                LEFT JOIN teachers t ON d.teacher_id = t.id
                WHERE s.id = :student_id";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['student_id' => $studentId]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        if (!$results) {
            return null;
        }
    
        // Criando um array base para armazenar todas as informações
        $studentData = [
            'student_id' => $results[0]['student_id'],
            'student_name' => $results[0]['student_name'],
            'email' => $results[0]['email'],
            'gender' => $results[0]['gender'],
            'identity' => $results[0]['identity'],
            'cpf' => $results[0]['cpf'],
            'nationality' => $results[0]['nationality'],
            'birthplace' => $results[0]['birthplace'],
            'birth_date' => $results[0]['birth_date'],
            'father_name' => $results[0]['father_name'],
            'mother_name' => $results[0]['mother_name'],
            'enrollment_number' => $results[0]['enrollment_number'],
            'student_status' => $results[0]['student_status'],
            'student_created_at' => $results[0]['student_created_at'],
            'course_id' => $results[0]['course_id'],
            'course_name' => $results[0]['course_name'],
            'course_code' => $results[0]['course_code'],
            'course_status' => $results[0]['course_status'],
            'disciplines' => [], // Inicializa a lista de disciplinas
            'grades' => []
        ];
    
        foreach ($results as $row) {
            // Adiciona os discipline_id à lista, garantindo que não haja duplicatas
            if (!in_array($row['discipline_id'], $studentData['disciplines'])) {
                $studentData['disciplines'][] = $row['discipline_id'];
            }
    
            // Adiciona as informações das disciplinas e notas
            $studentData['grades'][] = [
                'discipline_id' => $row['discipline_id'],
                'discipline_name' => $row['discipline_name'],
                'discipline_code' => $row['discipline_code'],
                'discipline_status' => $row['discipline_status'],
                'teacher_id' => $row['teacher_id'],
                'teacher_name' => $row['teacher_name'],
                'grade1' => $row['grade1'],
                'grade2' => $row['grade2'],
                'grade3' => $row['grade3'],
                'grade4' => $row['grade4'],
                'final_average' => $row['final_average'],
                'enrollment_date' => $row['enrollment_date']
            ];
        }
    
        return $studentData;
    }

    // Buscar aluno por CPF
    public function getStudentByCPF(string $cpf): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE cpf = :cpf";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['cpf' => $cpf]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Buscar alunos e suas notas por ID da disciplina
    /* A saida do array é:
        Array
        (
            [0] => Array
                (
                    [teacher_id] => 5
                    [teacher_name] => João Silva
                    [course_id] => 2
                    [course_name] => Engenharia
                    [discipline_id] => 3
                    [discipline_name] => Álgebra Linear
                    [student_id] => 8
                    [student_name] => Lucas Pereira
                    [enrollment_number] => 20231001
                    [grade1] => 9.0
                    [grade2] => 8.5
                    [grade3] => 7.8
                    [grade4] => 9.2
                    [final_average] => 8.63
                )
        )
    */
    public function getStudentByDisciplineId(int $disciplineId): array {
        $sql = "SELECT 
                    t.id AS teacher_id, 
                    t.name AS teacher_name,
                    c.id AS course_id, 
                    c.name AS course_name, 
                    d.id AS discipline_id, 
                    d.name AS discipline_name, 
                    s.id AS student_id, 
                    s.name AS student_name, 
                    s.enrollment_number, 
                    e.grade1, 
                    e.grade2, 
                    e.grade3, 
                    e.grade4, 
                    e.final_average
                FROM college_enrollment e
                JOIN students s ON e.student_id = s.id
                JOIN course_disciplines d ON e.discipline_id = d.id
                JOIN courses c ON e.course_id = c.id
                JOIN teachers t ON d.teacher_id = t.id
                WHERE e.discipline_id = :discipline_id
                ORDER BY s.name ASC";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['discipline_id' => $disciplineId]);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $students; // Retorna um array com os dados dos alunos matriculados
    }
    




    // Adicionar novo aluno
    public function addStudent(string $name, 
                               string $email, 
                               string $password, 
                               string $gender, 
                               string $identity, 
                               string $cpf, 
                               string $nationality, 
                               string $birthplace, 
                               string $birth_date, 
                               string $father_name, 
                               string $mother_name, 
                               string $enrollment_number, 
                               int $course_id, 
                               string $status): bool {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO {$this->table} (name, email, password, gender, identity, cpf, nationality, birthplace, birth_date, father_name, mother_name, enrollment_number, course_id, status) 
                VALUES (:name, :email, :password, :gender, :identity, :cpf, :nationality, :birthplace, :birth_date, :father_name, :mother_name, :enrollment_number, :course_id,  :status)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'gender' => $gender,
            'identity' => $identity,
            'cpf' => $cpf,
            'nationality' => $nationality,
            'birthplace' => $birthplace,
            'birth_date' => $birth_date,
            'father_name' => $father_name,
            'mother_name' => $mother_name,
            'enrollment_number' => $enrollment_number,
            'course_id' => $course_id,
            'status' => $status
        ]);
    }


    // Atualizar aluno existente
    public function updateStudent(int $id, 
                                  string $name, 
                                  string $email, 
                                  ?string $password, 
                                  bool $notChangePassword, 
                                  string $gender, 
                                  string $identity, 
                                  string $cpf, 
                                  string $nationality, 
                                  string $birthplace, 
                                  string $birth_date, 
                                  string $father_name, 
                                  string $mother_name, 
                                  string $enrollment_number, 
                                  string $status): bool {

        // if (!isset($notChangePassword) && empty($password)) {
        //    echo "<script>window.location.href = '". BASE_URL ."/admin/editar_aluno.php?error=passwordNeeded';</script>";
        //    exit();
        // }
        
        $sql = "UPDATE {$this->table} SET 
                    name = :name, 
                    email = :email, 
                    gender = :gender,
                    identity = :identity,
                    cpf = :cpf,
                    nationality = :nationality,
                    birthplace = :birthplace,
                    birth_date = :birth_date,
                    father_name = :father_name,
                    mother_name = :mother_name,
                    enrollment_number = :enrollment_number,
                    status = :status";
        
        $params = [
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'gender' => $gender,
            'identity' => $identity,
            'cpf' => $cpf,
            'nationality' => $nationality,
            'birthplace' => $birthplace,
            'birth_date' => $birth_date,
            'father_name' => $father_name,
            'mother_name' => $mother_name,
            'enrollment_number' => $enrollment_number,
            'status' => $status
        ];
        
        if (empty($notChangePassword)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql .= ", password = :password";
            $params['password'] = $hashedPassword;
        }
        
        $sql .= " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    // Adiciona o student_id, course_id, discipline_id
    public function addCollegeEnrollment(int $student_id, int $course_id, array $disciplines): bool {
        try {
            // Inicia transação para garantir consistência dos dados
            $this->conn->beginTransaction();
    
            // Obtém as disciplinas já cadastradas para o estudante e curso fornecidos
            $sqlExisting = "SELECT discipline_id FROM college_enrollment WHERE student_id = :student_id AND course_id = :course_id";
            $stmtExisting = $this->conn->prepare($sqlExisting);
            $stmtExisting->execute([
                'student_id' => $student_id,
                'course_id' => $course_id
            ]);
            $existingDisciplines = $stmtExisting->fetchAll(PDO::FETCH_COLUMN);
    
            // **1️⃣ Verifica se há disciplinas para remover antes de adicionar novas**
            $disciplinesToRemove = array_diff($existingDisciplines, $disciplines);
            if (!empty($disciplinesToRemove)) {
                $placeholders = implode(',', array_fill(0, count($disciplinesToRemove), '?'));
                $sqlDelete = "DELETE FROM college_enrollment 
                              WHERE student_id = ? AND course_id = ? 
                              AND discipline_id IN ($placeholders)";
                $stmtDelete = $this->conn->prepare($sqlDelete);
                $stmtDelete->execute(array_merge([$student_id, $course_id], $disciplinesToRemove));
            }
    
            // **2️⃣ Adiciona novas disciplinas, garantindo que não sejam duplicadas**
            $disciplinesToAdd = array_diff($disciplines, $existingDisciplines);
            if (!empty($disciplinesToAdd)) {
                $sqlInsert = "INSERT INTO college_enrollment (student_id, course_id, discipline_id, enrollment_date) 
                              VALUES (:student_id, :course_id, :discipline_id, CURRENT_DATE)";
                $stmtInsert = $this->conn->prepare($sqlInsert);
    
                foreach ($disciplinesToAdd as $discipline_id) {
                    $stmtInsert->execute([
                        'student_id' => $student_id,
                        'course_id' => $course_id,
                        'discipline_id' => $discipline_id
                    ]);
                }
            }
    
            // Confirma transação
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            // Se houver erro, reverte transação
            $this->conn->rollBack();
            return false;
        }
    }
    
    // Salvar as  notas do aluno por studentId
    public function SaveStudentGradesByStudentIdCourseIdDisciplineId(int $studentId, int $courseId, int $disciplineId, ?float $grade1, ?float $grade2, ?float $grade3, ?float $grade4) {
        // Conectando ao banco de dados
        // $db = (new Database())->connect();
        
        // Verifica se o aluno está matriculado no curso e na disciplina
        $sqlCheck = "SELECT id FROM college_enrollment WHERE student_id = :student_id AND course_id = :course_id AND discipline_id = :discipline_id";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->execute([
            ':student_id' => $studentId,
            ':course_id' => $courseId,
            ':discipline_id' => $disciplineId
        ]);
    
        // Se não houver matrícula correspondente, retornamos um erro ou mensagem
        if ($stmtCheck->rowCount() === 0) {
            throw new Exception("Matrícula não encontrada para o aluno neste curso e disciplina.");
        }
    
        // Atualiza as notas, apenas se elas foram fornecidas
        $sqlUpdate = "UPDATE college_enrollment SET ";
        
        $fields = [];
        $params = [':student_id' => $studentId, ':course_id' => $courseId, ':discipline_id' => $disciplineId];
    
        if ($grade1 !== null) {
            $fields[] = "grade1 = :grade1";
            $params[':grade1'] = $grade1;
        }
        if ($grade2 !== null) {
            $fields[] = "grade2 = :grade2";
            $params[':grade2'] = $grade2;
        }
        if ($grade3 !== null) {
            $fields[] = "grade3 = :grade3";
            $params[':grade3'] = $grade3;
        }
        if ($grade4 !== null) {
            $fields[] = "grade4 = :grade4";
            $params[':grade4'] = $grade4;
        }
    
        // Se não houver notas para atualizar, lança um erro
        if (empty($fields)) {
            throw new Exception("Nenhuma nota fornecida para atualização.");
        }
    
        $sqlUpdate .= implode(", ", $fields) . " WHERE student_id = :student_id AND course_id = :course_id AND discipline_id = :discipline_id";
    
        // Executa a atualização
        $stmtUpdate = $this->conn->prepare($sqlUpdate);
        $stmtUpdate->execute($params);
    
        return true;
    }
    
    // Excluir aluno
    public function deleteStudent(int $id): bool {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // Converte para float.
    function convertToFloat(string $input): float {
        // Substitui a vírgula por ponto e converte para float
        return (float) str_replace(',', '.', $input);
    }

    // Alterar senha de Aluno.
    public function changeStudentPassword(string $studentEmail, string $password, string $newPassword): bool {
        // Verifica se o aluno existe pelo email
        $stmt = $this->conn->prepare("SELECT * FROM students WHERE email = :email");
        $stmt->execute(['email' => $studentEmail]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$student) {
            // Retorna falso se o aluno não existir
            return false;
        }

        // Verifica se a senha atual está correta
        if (password_verify($password, $student['password'])) {
            // Atualiza a senha se a senha atual for válida
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

            $updateStmt = $this->conn->prepare("UPDATE students SET password = :newPassword WHERE email = :email");
            $updateStmt->execute([
                'newPassword' => $newPasswordHash,
                'email' => $studentEmail
            ]);

            return true;  // Senha alterada com sucesso
        } else {
            // Retorna falso se a senha atual não for válida
            return false;
        }
    }
}
