# Sistema-Gerenciamento-Educacional
Sistema de Gerenciamento Educacional foi criado para administrar dados de instituições de ensino como professores, alunos, cursos, disciplinas e notas.

## Demo on-line <a href="https://pedrosilva.tech/sistema-gerenciamento-educacional/">Click Aqui.</a>

### Usuário para a parte administrativa:
Como administrador você pode adicionar/editar/excluir cursos, disciplinas, docentes e discentes. <br>
Usuário: admin@test.com
Senha: 123456

### Usuários para a o perfil do professor:
- Como professor você pode exibir as disciplinas que ele é regente, exibir os alunos matriculados nessas disciplinas e adicionar/editar/excluir notas. <br>
Usuário: professor@test.com <br>
Senha: 123456

Usuário: joao.silva@educacao.com <br>
Senha: 123456

Usuário: maria.oliveira@educacao.com <br>
Senha: 123456


### Usuários para o perfil do aluno:
- Como aluno você pode exibir os seus dados e notas.<br>
Usuário: lucas.andrade@email.com<br>
Senha: 123456<br>

Usuário: mariana.souza@email.com<br>
Senha: 123456

Usuário: rafael.lima@email.com<br>
Senha: 123456<br>

# Requisitos
Servidor Web como Apache ou o Nginx, O php 8+, Mysql, PHPMyAdmin.

# 1 - Para instalar você precisa configurar os atributos no arquivo config.php que está na pasta includes

define('DB_HOST', 'mysql');<br>
define('DB_NAME', 'gerenciamento_educacional'); // Nome da database<br>
define('DB_USER', 'root'); // Substitua pelo seu usuário<br>
define('DB_PASS', 'rootpassword'); // Substitua pela sua senha<br>
define('SESSION_NAME', 'sge_session'); // Nome da sessão<br>
define('BASE_URL', 'http://localhost/sistema-gerenciamento-educacional/'); // URL Base do Sistema com / no final<br>

# 2 - Precisa criar o banco de dados e tabelas usando esses comandos usando o PHPMyAdmin

CREATE TABLE admins (<br>
    id INT AUTO_INCREMENT PRIMARY KEY,<br>
    name VARCHAR(100) NOT NULL,<br>
    email VARCHAR(100) UNIQUE NOT NULL,<br>
    password VARCHAR(255) NOT NULL,<br>
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP<br>
);<br>

CREATE TABLE teachers (<br>
    id INT AUTO_INCREMENT PRIMARY KEY,<br>
    name VARCHAR(150) NOT NULL,<br>
    telephone VARCHAR(20) NOT NULL,<br>
    email VARCHAR(100) UNIQUE NOT NULL,<br>
    password VARCHAR(255) NOT NULL,<br>
    cpf VARCHAR(14) NOT NULL,<br>
    identity VARCHAR(20) NOT NULL,<br>
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP<br>
);<br>

CREATE TABLE courses (<br>
    id INT AUTO_INCREMENT PRIMARY KEY,<br>
    name VARCHAR(150) NOT NULL,<br>
    description TEXT NULL,<br>
    code VARCHAR(50) UNIQUE NOT NULL,<br>
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',<br>
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,<br>
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP<br>
);<br>

CREATE TABLE students (<br>
    id INT AUTO_INCREMENT PRIMARY KEY,<br>
    name VARCHAR(150) NOT NULL,<br>
    email VARCHAR(100) UNIQUE NOT NULL,<br>
    password VARCHAR(255) NOT NULL,<br>
    gender ENUM('masculino', 'feminino', 'outro') NOT NULL,<br>
    identity VARCHAR(20) NOT NULL,<br>
    cpf VARCHAR(14) NOT NULL,<br>
    nationality VARCHAR(100) NOT NULL,<br>
    birthplace VARCHAR(100) NOT NULL,<br>
    birth_date DATE NOT NULL,<br>
    father_name VARCHAR(150) NOT NULL,<br>
    mother_name VARCHAR(150) NOT NULL,<br>
    enrollment_number VARCHAR(50) UNIQUE NOT NULL,<br>
    course_id INT NOT NULL,<br>
    status ENUM('cursando', 'formado', 'jubilado') DEFAULT 'cursando',<br>
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,<br>
    
    CONSTRAINT fk_students_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

CREATE TABLE course_disciplines (<br>
    id INT AUTO_INCREMENT PRIMARY KEY,<br>
    course_id INT NOT NULL,<br>
    name VARCHAR(150) NOT NULL,<br>
    code VARCHAR(50) NOT NULL,<br>
    teacher_id INT NOT NULL,<br>
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',<br>
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,<br>
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,<br>

    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,<br>
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE<br>
);<br>

CREATE TABLE college_enrollment (<br>
    id INT AUTO_INCREMENT PRIMARY KEY,<br>
    student_id INT NOT NULL,<br>
    course_id INT NOT NULL,<br>
    discipline_id INT NOT NULL,<br>
    grade1 FLOAT,<br>
    grade2 FLOAT,<br>
    grade3 FLOAT,<br>
    grade4 FLOAT,<br>
    final_average FLOAT GENERATED ALWAYS AS ((grade1 + grade2 + grade3 + grade4) / 4) STORED,<br>
    enrollment_date DATE NOT NULL DEFAULT (CURRENT_DATE),<br>

    CONSTRAINT fk_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,<br>
    CONSTRAINT fk_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,<br>
    CONSTRAINT fk_discipline FOREIGN KEY (discipline_id) REFERENCES course_disciplines(id) ON DELETE CASCADE,<br>

    INDEX idx_student (student_id),<br>
    INDEX idx_course (course_id),<br>
    INDEX idx_discipline (discipline_id)<br>
);<br>

CREATE TABLE events (<br>
    id INT AUTO_INCREMENT PRIMARY KEY,<br>
    title VARCHAR(255) NOT NULL,<br>
    start DATETIME NOT NULL,<br>
    end DATETIME NOT NULL,<br>
    description TEXT,<br>
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP<br>
);<br>

# 3 - Fazer o upload dos arquivos para o seu servidor.<br>

# 4 - Como funciona.
1 - Na parte administrativa crie os cursos, crie os docentes, depois crie as disciplinas, depois crie os discentes, depois de criar os discentes os edite adicionando as disciplinas que ele está matriculado.

2 - No perfil do docente você pode exibir as disciplinas vinculadas e editar as notas dos discentes matriculados nas disciplinas.

3 - No perfil do discente você pode exibir os dados e suas notas.
