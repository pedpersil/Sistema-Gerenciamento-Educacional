<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../classes/Professor.php';
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="noindex,nofollow" />
    <title>Sistema de Gerenciamento Educacional - Docentes</title>
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
    <script type="text/javascript">
      function confirmDelete(event) {
          event.preventDefault();  // Previne o redirecionamento imediato

          Swal.fire({
              title: 'Você tem certeza?',
              text: "Essa ação não pode ser desfeita!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Sim, excluir',
              cancelButtonText: 'Cancelar',
              confirmButtonColor: '#B22222',
          }).then((result) => {
              if (result.isConfirmed) {
                  // Se o usuário confirmar, redireciona para o link de exclusão
                  window.location.href = event.target.href;
              }
          });
      }
    </script>
  </head>

  <body>
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
            <a class="navbar-brand" <?php echo "href='" . BASE_URL . "admin/index.php'"; ?>>
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
              <h4 class="page-title">Admin Dashboard / Docentes</h4>
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
        <!-- Inicio Datatable  -->
        <!-- ============================================================== -->


        
          <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Lista de docentes</h5>
                  <a class='btn btn-success btn-sm text-white' <?php echo "href='" . BASE_URL . "admin/adicionar_professor.php'"; ?>>Adicionar docente</a>
                  <div class="table-responsive">
                    <table
                      id="professores_table"
                      class="table table-striped table-bordered"
                    >
                      <thead>
                        <tr>
                          <th>Id</th>
                          <th>Nome do docente</th>
                          <th>Email</th>
                          <th>CPF</th>
                          <th>Identidade</th>
                          <th>Ação</th>
                        </tr>
                      </thead>
                      <tbody>
<?php

$professor = new Professor();
$professores = $professor->getAllTeachers();

// Exibe a lista contendo os cursos cadastrados.
foreach ($professores as $p) {
  echo "<tr><td>{$p['id']}</td> 
        <td><a href='" . BASE_URL . "admin/mostrar_professor.php?id={$p['id']}'>{$p['name']}</a></td> 
        <td>{$p['email']}</td> 
        <td>{$p['cpf']}</td>
        <td>{$p['identity']}</td>  
        <td>
        <a class='fas fa-address-card' href='" . BASE_URL . "admin/mostrar_professor.php?id={$p['id']}'></a>
        <a class='far fa-edit' href='" . BASE_URL . "admin/editar_professor.php?id={$p['id']}'></a>
        <a class='far fa-trash-alt' href='" . BASE_URL . "admin/excluir_professor.php?id={$p['id']}' onclick='return confirmDelete(event)'></a>
        </td></tr>";
}
?>
                          </tbody>
                          <tfoot>
                          <tr>
                          <th>Id</th>
                          <th>Nome do docente</th>
                          <th>Email</th>
                          <th>CPF</th>
                          <th>Identidade</th>
                          <th>Ação</th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <!-- ============================================================== -->
        <!-- Fim Datatable  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
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
    <!-- this page js -->
    <script src="../assets/extra-libs/multicheck/datatable-checkbox-init.js"></script>
    <script src="../assets/extra-libs/multicheck/jquery.multicheck.js"></script>
    <script src="../assets/extra-libs/DataTables/datatables.min.js"></script>
    <script>
      /****************************************
       *       Basic Table                   *
       ****************************************/
      $("#professores_table").DataTable();
    </script>
  </body>
</html>
