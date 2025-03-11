<?php
require_once __DIR__ . '/classes/Auth.php';


session_start();

$auth = new Auth();
$error = null;

// Se o usuário já está logado, redireciona para a área correspondente
if (isset($_SESSION[SESSION_NAME]) && !empty($_SESSION[SESSION_NAME]['type'])) {
    header("Location: " . BASE_URL . $auth->getRedirectPath($_SESSION[SESSION_NAME]['type']));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($auth->login($email, $password)) {
        // Redirecionamento com base no tipo de usuário
        header("Location: " . BASE_URL . $auth->getRedirectPath($_SESSION[SESSION_NAME]['type']));
        exit();
    } else {
        $error = "E-mail ou senha inválidos!";
    }
}


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: url('assets/images/bg.jpeg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            background: rgba(255, 255, 255, 0.9); /* Leve transparência */
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="card p-4">
        <h2 class="text-center">Login</h2>
        <?php if ($error) : ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="<?php echo '' . BASE_URL . 'login.php'; ?>">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" id="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" id="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
    </div>
</body>
</html>
