<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gerenciamento Educacional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* Configuração do vídeo em fullscreen */
        body, html {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }
        .video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }
        .content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
        }
        .title {
            font-size: 3rem;
            font-weight: bold;
        }
        .subtitle {
            font-size: 1.5rem;
        }
        a {
            color: #00BFFF; /* Azul neon */
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s, text-shadow 0.3s;
        }
        a:hover {
            color: #FF6347; /* Cor de destaque no hover (Tom laranja) */
            text-shadow: 0 0 10px #FF6347, 0 0 20px #FF6347; /* Efeito de brilho no hover */
        }
    </style>
</head> 
<body>
    <!-- Vídeo de fundo -->
    <video class="video-background" autoplay loop muted>
        <source src="assets/videos/bg.mp4" type="video/mp4">
        Seu navegador não suporta vídeos.
    </video>

    <!-- Conteúdo centralizado -->
    <div class="content">
        <div class="title">Sistema de Gerenciamento Educacional</div>
        <div class="subtitle">Solução para administração de Cursos, Disciplinas, Alunos e Notas.<br><a href="login.php">Login</a></div>
    </div>
</body>
</html>
