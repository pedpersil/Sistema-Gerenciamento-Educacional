<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../classes/Curso.php';

// Verifique se o ID foi passado e se ele é um número válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  // Se o ID não for válido, redireciona para index.php
  header('Location: ' . BASE_URL . 'admin/index.php');
  exit();
}

$id = $_GET['id'];

$curso = new Curso();

if (!$curso->getCourseById($id)) {
  // Se o curso não existe, redireciona para index.php
  header('Location: ' . BASE_URL . 'admin/index.php');
  exit();
}
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="noindex,nofollow" />
    <title>Sistema de Gerenciamento Educacional</title>
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
  </head>

  <body>

  <?php

// Se o curso existe, chamar o método deleteCourse para excluí-lo
if ($curso->deleteCourse($id)) {
    // Exclusão bem-sucedida, redireciona com uma mensagem de sucesso via SweetAlert2
    echo "<script>
            Swal.fire({
                  title: 'Sucesso!',
                  text: 'Curso excluído com sucesso!',
                  icon: 'success',
                  confirmButtonText: 'OK'  
              }).then((result) => {
                  if (result.isConfirmed) {
                      window.location.href = '" . BASE_URL . "admin/cursos.php';// Redireciona para a página de listagem
                  }
            });
          </script>";
} else {
    // Caso ocorra algum erro na exclusão, redireciona com uma mensagem de erro
    echo "<script>
            Swal.fire({
                title: 'Erro!',
                text: 'Não foi possível excluir o curso. Tente novamente.',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) { 
                    window.location.href = '" . BASE_URL . "admin/cursos.php';// Redireciona para a página de listagem
                }
            });
          </script>";
}

?>

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
  </body>
</html>
