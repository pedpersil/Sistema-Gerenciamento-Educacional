<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../classes/Aluno.php';
require_once __DIR__ . '/../classes/Curso.php';
require_once __DIR__ . '/../classes/Disciplina.php';
?>

<!DOCTYPE html>
<html dir="ltr" lang="pt-br">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="noindex,nofollow" />
    <title>Sistema de Gerenciamento Educacional - Adicionar Discente</title>
    <!-- Favicon icon -->
    <link
      rel="icon"
      type="image/png"
      sizes="16x16"
      href="../assets/images/favicon.png"
    />
    <!-- Custom CSS -->
    <link href="../assets/libs/flot/css/float-chart.css" rel="stylesheet" />
    <!-- Custom CSS -->
    <link href="../dist/css/style.min.css" rel="stylesheet" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- JavaScript para validar senha -->
    <script>
        function validatePassword() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;
            var passwordError = document.getElementById("passwordError");

            if (password !== confirmPassword) {
                passwordError.textContent = "As senhas não coincidem!";
                return false; // Impede o envio do formulário
            }

            passwordError.textContent = ""; // Limpa a mensagem de erro se as senhas forem iguais
            return true; // Permite o envio do formulário
        }
    </script>

    <!-- JavaScript para alternar exibição da senha -->
    <script>
        function togglePassword(fieldId, iconId) {
            var passwordField = document.getElementById(fieldId);
            var icon = document.getElementById(iconId);

            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>

    <!-- Link do FontAwesome para os ícones -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <!-- CSS para ajustar tamanho dos ícones -->
    <style>
        .small-icon {
            font-size: 0.9rem; /* Ícones menores */
            color: #555; /* Cor mais discreta */
        }
    </style>
  </head>

  <body>
  <?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Atributos para serem inseridos na tabela students
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $gender = $_POST['gender'];
    $identity = $_POST['identity'];
    $cpf = $_POST['cpf'];
    $nationality = $_POST['nationality'];
    $birthplace = $_POST['birthplace'];
    $birth_date = $_POST['birth_date'];
    $father_name = $_POST['father_name'];
    $mother_name = $_POST['mother_name'];
    $enrollment_number = $_POST['enrollment_number'];
    $course_id = $_POST['course'];
    $status = $_POST['status'];


    if ($password != $confirmPassword) {
        echo "<script>window.location.href = '". BASE_URL ."/admin/adicionar_aluno.php?error=passwordNaoConfere';</script>";
        exit();
    }

    $aluno = new Aluno();

    if ($aluno->addStudent($name, 
                           $email, 
                           $password, 
                           $gender, 
                           $identity, 
                           $cpf, 
                           $nationality, 
                           $birthplace, 
                           $birth_date, 
                           $father_name, 
                           $mother_name, 
                           $enrollment_number,
                           $course_id,
                           $status) 
                           ) {
        // Registro criado com sucesso, redireciona com uma mensagem de sucesso via SweetAlert2
        echo "<script>
                Swal.fire({
                    title: 'Sucesso!',
                    text: 'Discente adicionado com sucesso!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '" . BASE_URL . "admin/alunos.php';
                    }
                });
              </script>";
  } else {
      // Caso ocorra algum erro na criação, redireciona com uma mensagem de erro
      echo "<script>
              Swal.fire({
                  title: 'Erro!',
                  text: 'Não foi possível adicionar o discente. Tente novamente.',
                  icon: 'error',
                  confirmButtonText: 'OK'
              }).then((result) => {
                  if (result.isConfirmed) {
                      window.location.href = '" . BASE_URL . "admin/alunos.php';
                  }
              });
            </script>";
  }
  
}

?>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
      <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
      </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div
      id="main-wrapper"
      data-layout="vertical"
      data-navbarbg="skin5"
      data-sidebartype="full"
      data-sidebar-position="absolute"
      data-header-position="absolute"
      data-boxed-layout="full"
    >
      <!-- ============================================================== -->
      <!-- Topbar header - style you can find in pages.scss -->
      <!-- ============================================================== -->
      <header class="topbar" data-navbarbg="skin5">
        <nav class="navbar top-navbar navbar-expand-md navbar-dark">
          <div class="navbar-header" data-logobg="skin5">
            <!-- ============================================================== -->
            <!-- Logo -->
            <!-- ============================================================== -->
            <a class="navbar-brand" href="index.html"<?php echo "href='" . BASE_URL . "admin/index.php'"; ?>>
              <!-- Logo icon -->
              <b class="logo-icon ps-2">
                <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                <!-- Dark Logo icon -->
                <img
                  src="../assets/images/logo-icon.png"
                  alt="homepage"
                  class="light-logo"
                  width="25"
                />
              </b>
              <!--End Logo icon -->
              <!-- Logo text -->
              <span class="logo-text ms-2">
                <!-- dark Logo text -->
                <img
                  src="../assets/images/logo-text.png"
                  alt="homepage"
                  class="light-logo"
                />
              </span>
              <!-- Logo icon -->
              <!-- <b class="logo-icon"> -->
              <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
              <!-- Dark Logo icon -->
              <!-- <img src="../assets/images/logo-text.png" alt="homepage" class="light-logo" /> -->

              <!-- </b> -->
              <!--End Logo icon -->
            </a>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Toggle which is visible on mobile only -->
            <!-- ============================================================== -->
            <a
              class="nav-toggler waves-effect waves-light d-block d-md-none"
              href="javascript:void(0)"
              ><i class="ti-menu ti-close"></i
            ></a>
          </div>
          <!-- ============================================================== -->
          <!-- End Logo -->
          <!-- ============================================================== -->
          <div
            class="navbar-collapse collapse"
            id="navbarSupportedContent"
            data-navbarbg="skin5"
          >
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav float-start me-auto">
              <li class="nav-item d-none d-lg-block">
                <a
                  class="nav-link sidebartoggler waves-effect waves-light"
                  href="javascript:void(0)"
                  data-sidebartype="mini-sidebar"
                  ><i class="mdi mdi-menu font-24"></i
                ></a>
              </li>
              
              <!-- ============================================================== -->
              <!-- Search -->
              <!-- ============================================================== -->
              <li class="nav-item search-box">
                <a
                  class="nav-link waves-effect waves-dark"
                  href="javascript:void(0)"
                  ><i class="mdi mdi-magnify fs-4"></i
                ></a>
                <form class="app-search position-absolute">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Procurar"
                  />
                  <a class="srh-btn"><i class="mdi mdi-window-close"></i></a>
                </form>
              </li>
            </ul>
            <!-- ============================================================== -->
            <!-- Right side toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav float-end">
              
              
              <!-- ============================================================== -->
              <!-- User profile and search -->
              <!-- ============================================================== -->
              <li class="nav-item dropdown">
                <a
                  class="
                    nav-link
                    dropdown-toggle
                    text-muted
                    waves-effect waves-dark
                    pro-pic
                  "
                  href="#"
                  id="navbarDropdown"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <img
                    src="../assets/images/users/1.jpg"
                    alt="user"
                    class="rounded-circle"
                    width="31"
                  />
                </a>
                <ul
                  class="dropdown-menu dropdown-menu-end user-dd animated"
                  aria-labelledby="navbarDropdown"
                >
                  <a class="dropdown-item" href="javascript:void(0)"
                    ><i class="mdi mdi-account me-1 ms-1"></i> Meu Perfil</a
                  >
                  
                  <a class="dropdown-item" href="javascript:void(0)"
                    ><i class="mdi mdi-settings me-1 ms-1"></i> Configurações da Conta</a
                  >
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" <?php echo "<a href='" . BASE_URL . "logout.php'>"; ?>
                    <i class="fa fa-power-off me-1 ms-1"></i> Logout</a
                  >
                  </ul>
              </li>
              <!-- ============================================================== -->
              <!-- User profile and search -->
              <!-- ============================================================== -->
            </ul>
          </div>
        </nav>
      </header>
      <!-- ============================================================== -->
      <!-- End Topbar header -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Left Sidebar - style you can find in sidebar.scss  -->
      <!-- ============================================================== -->
      <aside class="left-sidebar" data-sidebarbg="skin5">
        <!-- Sidebar scroll-->
        <div class="scroll-sidebar">
          <!-- Sidebar navigation-->
          <nav class="sidebar-nav">
            <ul id="sidebarnav" class="pt-4">
              <li class="sidebar-item">
                <a
                  class="sidebar-link waves-effect waves-dark sidebar-link"
                  <?php echo "href='" . BASE_URL . "admin/index.php'"; ?>
                  aria-expanded="false"
                  ><span class="hide-menu">Dashboard</span></a
                >
              </li>
              <li class="sidebar-item">
                <a
                  class="sidebar-link waves-effect waves-dark sidebar-link"
                  <?php echo "href='" . BASE_URL . "admin/cursos.php'"; ?>
                  aria-expanded="false"
                  ><span class="hide-menu">Cursos</span></a
                >
              </li>
              <li class="sidebar-item">
                <a
                  class="sidebar-link waves-effect waves-dark sidebar-link"
                  <?php echo "href='" . BASE_URL . "admin/disciplinas.php'"; ?>
                  aria-expanded="false"
                  ><span class="hide-menu">Disciplinas</span></a
                >
              </li>
              <li class="sidebar-item">
                <a
                  class="sidebar-link waves-effect waves-dark sidebar-link"
                  <?php echo "href='" . BASE_URL . "admin/professores.php'"; ?>
                  aria-expanded="false"
                  ><span class="hide-menu">Docentes</span></a
                >
              </li>
              <li class="sidebar-item">
                <a
                  class="sidebar-link waves-effect waves-dark sidebar-link"
                  <?php echo "href='" . BASE_URL . "admin/alunos.php'"; ?>
                  aria-expanded="false"
                  ><span class="hide-menu">Discentes</span></a
                >
              </li>
                  <li class="sidebar-item">
                    <a <?php echo "href='" . BASE_URL . "admin/calendario.php'"; ?> class="sidebar-link"
                      ><span class="hide-menu"> Calendário </span></a
                    >
                  </li>
                </ul>
              </li>
            </ul>
          </nav>
          <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
      </aside>
      <!-- ============================================================== -->
      <!-- End Left Sidebar - style you can find in sidebar.scss  -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Page wrapper  -->
      <!-- ============================================================== -->
      <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h4 class="page-title">Admin Dashboard / Adicionar Discente</h4>
            </div>
          </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
          <!-- ============================================================== -->
          <!-- Sales Cards  -->
          <!-- ============================================================== -->
          <div class="row">
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-cyan text-center">
                  <a <?php echo "href='" . BASE_URL . "admin/index.php'"; ?>>
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-view-dashboard"></i>
                  </h1>
                  <h6 class="text-white">Dashboard</h6>
                </a>
                </div>
              </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-danger text-center">
                <a <?php echo "href='" . BASE_URL . "admin/cursos.php'"; ?>>
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-border-outside"></i>
                  </h1>
                  <h6 class="text-white">Cursos</h6>
                </a>
                </div>
              </div>
            </div>
             <!-- Column -->
             <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-info text-center">
                <a <?php echo "href='" . BASE_URL . "admin/disciplinas.php'"; ?>>
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-border-outside"></i>
                  </h1>
                  <h6 class="text-white">Disciplinas</h6>
                </a>
                </div>
              </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-success text-center">
                <a <?php echo "href='" . BASE_URL . "admin/professores.php'"; ?>>
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-border-outside"></i>
                  </h1>
                  <h6 class="text-white">Docentes</h6>
                </a>
                </div>
              </div>
            </div>
             <!-- Column -->
             <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-warning text-center">
                <a <?php echo "href='" . BASE_URL . "admin/alunos.php'"; ?>>
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-border-outside"></i>
                  </h1>
                  <h6 class="text-white">Discentes</h6>
                </a>
                </div>
              </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-danger text-center">
                <a <?php echo "href='" . BASE_URL . "admin/registrar_admin.php'"; ?>>
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-border-outside"></i>
                  </h1>
                  <h6 class="text-white">Registrar Novo Admin</h6>
                </a>
                </div>
              </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-success text-center">
                <a <?php echo "href='" . BASE_URL . "admin/calendario.php'"; ?>>
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-calendar-check"></i>
                  </h1>
                  <h6 class="text-white">Calendário</h6>
                </a>
                </div>
              </div>
            </div>
          </div>
        <!-- ============================================================== -->
        <!-- Inicio formulario Adicionar Aluno    .                         -->
        <!-- ============================================================== -->
              
        <div class="card">
          <form class="form-horizontal" method="post" <?php echo "action='" . BASE_URL . "admin/adicionar_aluno.php'"; ?> onsubmit="return validatePassword()">
              <div class="card-body">
                  <h4 class="card-title">Adicionar Discente</h4>
                  <!-- Full Name -->
                  <div class="form-group row">
                      <label for="name" class="col-sm-3 control-label col-form-label">Nome</label>
                      <div class="col-sm-9">
                          <input type="text" class="form-control" id="name" name="name" placeholder="Nome Completo" required />
                      </div>
                  </div>

                  <!-- Email -->
                  <div class="form-group row">
                      <label for="email" class="col-sm-3 control-label col-form-label">Email</label>
                      <div class="col-sm-9">
                          <input type="email" class="form-control" id="email" name="email" placeholder="Email" required />
                      </div>
                  </div>

                  <!-- Gender -->
                  <div class="form-group row">
                      <label for="gender" class="col-sm-3 control-label col-form-label">Genero</label>
                      <div class="col-sm-9">
                          <select class="form-control" id="gender" name="gender" required>
                              <option value="">Selecione</option>
                              <option value="masculino">Masculino</option>
                              <option value="feminino">Feminino</option>
                              <option value="outro">Outro</option>
                          </select>
                      </div>
                  </div>

                  <!-- Identity -->
                  <div class="form-group row">
                      <label for="identity" class="col-sm-3 control-label col-form-label">Identidade</label>
                      <div class="col-sm-9">
                          <input type="text" class="form-control" id="identity" name="identity" placeholder="Número de Identidade" required />
                      </div>
                  </div>

                  <!-- CPF -->
                  <div class="form-group row">
                      <label for="cpf" class="col-sm-3 control-label col-form-label">CPF</label>
                      <div class="col-sm-9">
                          <input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF" required />
                      </div>
                  </div>

                  <!-- Nationality -->
                  <div class="form-group row">
                      <label for="nationality" class="col-sm-3 control-label col-form-label">Nacionalidade</label>
                      <div class="col-sm-9">
                          <input type="text" class="form-control" id="nationality" name="nationality" placeholder="Nacionalidade" required />
                      </div>
                  </div>

                  <!-- Birthplace -->
                  <div class="form-group row">
                      <label for="birthplace" class="col-sm-3 control-label col-form-label">Naturalidade</label>
                      <div class="col-sm-9">
                          <input type="text" class="form-control" id="birthplace" name="birthplace" placeholder="Naturalidade" required />
                      </div>
                  </div>

                  <!-- Birth Date -->
                  <div class="form-group row">
                      <label for="birth_date" class="col-sm-3 control-label col-form-label">Data de Nascimento</label>
                      <div class="col-sm-9">
                          <input type="date" class="form-control" id="birth_date" name="birth_date" required />
                      </div>
                  </div>

                  <!-- Father's Name -->
                  <div class="form-group row">
                      <label for="father_name" class="col-sm-3 control-label col-form-label">Nome do Pai</label>
                      <div class="col-sm-9">
                          <input type="text" class="form-control" id="father_name" name="father_name" placeholder="Nome do Pai" required />
                      </div>
                  </div>

                  <!-- Mother's Name -->
                  <div class="form-group row">
                      <label for="mother_name" class="col-sm-3 control-label col-form-label">Nome da Mãe</label>
                      <div class="col-sm-9">
                          <input type="text" class="form-control" id="mother_name" name="mother_name" placeholder="Nome da Mãe" required />
                      </div>
                  </div>

                  <!-- Enrollment Number -->
                  <div class="form-group row">
                      <label for="enrollment_number" class="col-sm-3 control-label col-form-label">Número da matrícula</label>
                      <div class="col-sm-9">
                          <input type="text" class="form-control" id="enrollment_number" name="enrollment_number" placeholder="Número da matrícula" required />
                      </div>
                  </div>
                   <!-- Exibe os cursos disponiveis -->          
                   <div class="form-group row">
                      <label for="curso" class="col-sm-3 control-label col-form-label">Curso</label>
                      <div class="col-sm-9">
                          <select class="form-control" id="course" name="course" required>
                          <option value="">Selecione</option>
                          <?php 
                              $curso = new Curso();
                              $cursos = $curso->getAllCourses();
                              foreach ($cursos as $c) {
                                  echo "<option value='{$c['id']}'>{$c['name']} - {$c['code']}</option>";
                              }
                          ?>
                          </select>
                      </div>
                  </div>

                  <!-- Status -->
                  <div class="form-group row">
                      <label for="status" class="col-sm-3 control-label col-form-label">Status</label>
                      <div class="col-sm-9">
                          <select class="form-control" id="status" name="status" required>
                              <option value="">Selecione</option>
                              <option value="cursando">Cursando</option>
                              <option value="formado">Formado</option>
                              <option value="jubilado">Jubilado</option>
                          </select>
                      </div>
                  </div>

                  <!-- Password -->
                  <div class="form-group row">
                      <label for="password" class="col-sm-3 control-label col-form-label">Senha</label>
                      <div class="col-sm-9">
                          <div class="input-group">
                              <input type="password" class="form-control" id="password" name="password" placeholder="Senha" required />
                              <div class="input-group-append">
                                  <span class="input-group-text" onclick="togglePassword('password', 'togglePasswordIcon1')" style="cursor: pointer;">
                                      <i id="togglePasswordIcon1" class="fas fa-eye small-icon"></i>
                                  </span>
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- Confirm Password -->
                  <div class="form-group row">
                      <label for="confirmPassword" class="col-sm-3 control-label col-form-label">Confirmar Senha</label>
                      <div class="col-sm-9">
                          <div class="input-group">
                              <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirmar Senha" required />
                              <div class="input-group-append">
                                  <span class="input-group-text" onclick="togglePassword('confirmPassword', 'togglePasswordIcon2')" style="cursor: pointer;">
                                      <i id="togglePasswordIcon2" class="fas fa-eye small-icon"></i>
                                  </span>
                              </div>
                          </div>
                          <small id="passwordError" class="text-danger"></small>
                      </div>
                  </div>
              </div>

              <!-- Submit Button -->
              <div class="border-top">
                  <div class="card-body">
                      <button type="submit" class="btn btn-primary">Salvar</button>
                  </div>
              </div>
          </form>
      </div>

        <!-- ============================================================== -->
        <!-- Final formulario Adicionar Aluno.                              -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer                                                         -->
        <!-- ============================================================== -->
        </br></br></br></br></br></br></br></br></br></br></br>
        <footer class="footer text-center">
          All Rights Reserved
          <a href="https://github.com/pedpersil/Sistema-Gerenciamento-Educacional">Pedro Silva</a> 2025.
        </footer>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
      </div>
      <!-- ============================================================== -->
      <!-- End Page wrapper  -->
      <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../assets/extra-libs/sparkline/sparkline.js"></script>
    <!--Wave Effects -->
    <script src="../dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../dist/js/custom.min.js"></script>
    <!--This page JavaScript -->
    <!-- <script src="../dist/js/pages/dashboards/dashboard1.js"></script> -->
    <!-- Charts js Files -->
    <script src="../assets/libs/flot/excanvas.js"></script>
    <script src="../assets/libs/flot/jquery.flot.js"></script>
    <script src="../assets/libs/flot/jquery.flot.pie.js"></script>
    <script src="../assets/libs/flot/jquery.flot.time.js"></script>
    <script src="../assets/libs/flot/jquery.flot.stack.js"></script>
    <script src="../assets/libs/flot/jquery.flot.crosshair.js"></script>
    <script src="../assets/libs/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>
    <script src="../dist/js/pages/chart/chart-page-init.js"></script>
    <!-- This Page JS -->
    <script src="../assets/libs/inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
    <script src="../dist/js/pages/mask/mask.init.js"></script>
    <script src="../assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="../assets/libs/select2/dist/js/select2.min.js"></script>
    <script src="../assets/libs/jquery-asColor/dist/jquery-asColor.min.js"></script>
    <script src="../assets/libs/jquery-asGradient/dist/jquery-asGradient.js"></script>
    <script src="../assets/libs/jquery-asColorPicker/dist/jquery-asColorPicker.min.js"></script>
    <script src="../assets/libs/jquery-minicolors/jquery.minicolors.min.js"></script>
    <script src="../assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="../assets/libs/quill/dist/quill.min.js"></script>
    <script>
  </body>
</html>
