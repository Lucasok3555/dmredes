<?php
session_start();
if (!isset($_SESSION['usuario']) && !isset($_GET['user'])) {
    header('Location: index.php');
    exit;
}

$perfilUser = $_GET['user'] ?? $_SESSION['usuario'];
$usuariosFile = 'data/usuarios.json';
$postersFile = 'data/posters.json';

$usuarios = file_exists($usuariosFile) ? json_decode(file_get_contents($usuariosFile), true) : [];
$posters = file_exists($postersFile) ? json_decode(file_get_contents($postersFile), true) : [];

if (!isset($usuarios[$perfilUser])) {
    die("Usuário não encontrado.");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Perfil de <?= htmlspecialchars($perfilUser) ?></title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <div class="container">
        <h1>Perfil: <?= htmlspecialchars($perfilUser) ?></h1>
        <p><strong>Nome:</strong> <?= htmlspecialchars($perfilUser) ?></p>
        <p><strong>Conta criada em:</strong> <?= $usuarios[$perfilUser]['criado_em'] ?></p>
        <p><a href="feed.php">← Voltar ao feed</a></p>

        <h2>Pôsteres de <?= htmlspecialchars($perfilUser) ?></h2>
        <div class="feed">
            <?php 
            $postersUser = array_filter($posters, fn($p) => $p['usuario'] == $perfilUser);
            if (empty($postersUser)): 
            ?>
                <p>Nenhum pôster publicado ainda.</p>
            <?php else: ?>
                <?php foreach ($postersUser as $p): ?>
                    <div class="poster">
                        <div class="poster-header">
                            <span class="username"><?= htmlspecialchars($p['usuario']) ?></span>
                            <span class="data"><?= $p['data'] ?></span>
                        </div>
                        <div class="poster-texto">
                            <?= nl2br($p['texto']) ?>
                        </div>
                        <div class="poster-actions">
                            ❤️ <?= $p['curtidas'] ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>