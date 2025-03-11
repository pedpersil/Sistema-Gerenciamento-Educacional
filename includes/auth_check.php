<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../includes/config.php';

session_start();

$auth = new Auth();

// Verificar se o usuário está autenticado
if (!isset($_SESSION[SESSION_NAME]) || empty($_SESSION[SESSION_NAME]['type'])) {
    header("Location: " . BASE_URL . "login.php?error=unauthorized");
    exit();
}

// Obtém o tipo de usuário autenticado
$userType = $_SESSION[SESSION_NAME]['type'];



$currentFolder = basename(dirname($_SERVER['SCRIPT_FILENAME']));

// Definir permissões de acesso com base no tipo de usuário
$accessRules = [
    'admin' => ['admin'],
    'professor' => ['professor'],
    'aluno' => ['aluno']
];

// Se o usuário não tiver permissão para acessar a pasta atual, redireciona
if (!in_array($currentFolder, $accessRules[$userType])) {
   header("Location: " . BASE_URL . "".$accessRules[$userType][0]."" . "/index.php?error=forbidden");
   exit();
}

?>
