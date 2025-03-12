# Sistema-Gerenciamento-Educacional
Sistema de Gerenciamento Educacional foi criado para administrar dados de instituições de ensino como professores, alunos, cursos, disciplinas e notas.

## Demo on-line <a href="https://pedrosilva.tech/sistema-gerenciamento-educacional/">Click Aqui.</a>

### Usuário para a parte administrativa:
- Como administrador você pode adicionar/editar/excluir cursos, disciplinas, docentes e discentes. 
Usuário: admin@test.com
Senha: 123456

### Usuários para a o perfil do professor:
- Como professor você pode exibir as disciplinas que ele é regente, exibir os alunos matriculados nessas disciplinas e adicionar/editar/excluir notas.
Usuário: professor@test.com
Senha: 123456

Usuário: joao.silva@educacao.com
Senha: 123456

Usuário: maria.oliveira@educacao.com
Senha: 123456


### Usuários para o perfil do aluno:
- Como aluno você pode exibir os seus dados e notas.
Usuário: lucas.andrade@email.com
Senha: 123456

Usuário: mariana.souza@email.com
Senha: 123456

Usuário: rafael.lima@email.com
Senha: 123456

# Requisitos
Servidor Web como Apache ou o Nginx, O php 8+, Mysql, PHPMyAdmin.

# 1 - Para instalar você precisa configurar os atributos no arquivo config.php que está na pasta includes

define('DB_HOST', 'mysql');
define('DB_NAME', 'gerenciamento_educacional'); // Nome da database
define('DB_USER', 'root'); // Substitua pelo seu usuário
define('DB_PASS', 'rootpassword'); // Substitua pela sua senha
define('SESSION_NAME', 'sge_session'); // Nome da sessão
define('BASE_URL', 'http://localhost/sistema-gerenciamento-educacional/'); // URL Base do Sistema com / no final

# 2 - Precisa criar o banco de dados e tabelas usando esses comandos usando o PHPMyAdmin

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    identity VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT NULL,
    code VARCHAR(50) UNIQUE NOT NULL,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    gender ENUM('masculino', 'feminino', 'outro') NOT NULL,
    identity VARCHAR(20) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    nationality VARCHAR(100) NOT NULL,
    birthplace VARCHAR(100) NOT NULL,
    birth_date DATE NOT NULL,
    father_name VARCHAR(150) NOT NULL,
    mother_name VARCHAR(150) NOT NULL,
    enrollment_number VARCHAR(50) UNIQUE NOT NULL,
    course_id INT NOT NULL,
    status ENUM('cursando', 'formado', 'jubilado') DEFAULT 'cursando',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_students_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

CREATE TABLE course_disciplines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    code VARCHAR(50) NOT NULL,
    teacher_id INT NOT NULL,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE
);

CREATE TABLE college_enrollment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    discipline_id INT NOT NULL,
    grade1 FLOAT,
    grade2 FLOAT,
    grade3 FLOAT,
    grade4 FLOAT,
    final_average FLOAT GENERATED ALWAYS AS ((grade1 + grade2 + grade3 + grade4) / 4) STORED,
    enrollment_date DATE NOT NULL DEFAULT (CURRENT_DATE),

    CONSTRAINT fk_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    CONSTRAINT fk_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    CONSTRAINT fk_discipline FOREIGN KEY (discipline_id) REFERENCES course_disciplines(id) ON DELETE CASCADE,

    INDEX idx_student (student_id),
    INDEX idx_course (course_id),
    INDEX idx_discipline (discipline_id)
);

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    start DATETIME NOT NULL,
    end DATETIME NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

# 3 - Fazer o upload dos arquivos para o seu servidor.

# 4 - Como funciona.
1 - Na parte administrativa crie os cursos, crie os docentes, depois crie as disciplinas, depois crie os discentes, depois de criar os discentes os edite adicionando as disciplinas que ele está matriculado.

2 - No perfil do docente você pode exibir as disciplinas vinculadas e editar as notas dos discentes matriculados nas disciplinas.

3 - No perfil do discente você pode exibir os dados e suas notas.
