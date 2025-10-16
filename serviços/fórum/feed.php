<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

$usuario_logado = $_SESSION['usuario'];
$postersFile = 'data/posters.json';
$usuariosFile = 'data/usuarios.json';

// Carregar pôsteres
$posters = file_exists($postersFile) ? json_decode(file_get_contents($postersFile), true) : [];
$usuarios = file_exists($usuariosFile) ? json_decode(file_get_contents($usuariosFile), true) : [];

// Publicar novo pôster
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['poster'])) {
    $texto = trim($_POST['poster']);
    if (!empty($texto)) {
        $novo = [
            'id' => time(), // timestamp como ID
            'usuario' => $usuario_logado,
            'texto' => htmlspecialchars($texto),
            'data' => date('d/m H:i'),
            'curtidas' => 0,
            'comentarios' => []
        ];
        array_unshift($posters, $novo); // adiciona no início
        file_put_contents($postersFile, json_encode($posters, JSON_PRETTY_PRINT));
        header('Location: feed.php');
        exit;
    }
}

// Curtir pôster
if (isset($_GET['curtir'])) {
    $id = (int)$_GET['curtir'];
    foreach ($posters as &$p) {
        if ($p['id'] == $id) {
            $p['curtidas']++;
            break;
        }
    }
    file_put_contents($postersFile, json_encode($posters, JSON_PRETTY_PRINT));
    header('Location: feed.php');
    exit;
}

// Excluir pôster
if (isset($_GET['excluir'])) {
    $id = (int)$_GET['excluir'];
    foreach ($posters as $i => $p) {
        if ($p['id'] == $id && $p['usuario'] == $usuario_logado) {
            unset($posters[$i]);
            break;
        }
    }
    $posters = array_values($posters); // reindexar
    file_put_contents($postersFile, json_encode($posters, JSON_PRETTY_PRINT));
    header('Location: feed.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Feed de Pôsteres</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <div class="container">
        <h1>Feed de Pôsteres</h1>
        <p>Olá, <strong><?= htmlspecialchars($usuario_logado) ?></strong> | 
           <a href="perfil.php?user=<?= urlencode($usuario_logado) ?>">Meu Perfil</a> | 
           <a href="logout.php">Sair</a>
        </p>

        <!-- Formulário de novo pôster -->
        <form method="POST" class="poster-form">
            <textarea name="poster" placeholder="Escreva seu pôster..." required></textarea>
            <button type="submit">Publicar</button>
        </form>

        <!-- Lista de pôsteres -->
        <div class="feed">
            <?php if (empty($posters)): ?>
                <p>Nenhum pôster ainda. Seja o primeiro!</p>
            <?php else: ?>
                <?php foreach ($posters as $p): ?>
                    <div class="poster">
                        <div class="poster-header">
                            <a href="perfil.php?user=<?= urlencode($p['usuario']) ?>" class="username">
                                <?= htmlspecialchars($p['usuario']) ?>
                            </a>
                            <span class="data"><?= $p['data'] ?></span>
                        </div>
                        <div class="poster-texto">
                            <?= nl2br($p['texto']) ?>
                        </div>
                        <div class="poster-actions">
                            <a href="feed.php?curtir=<?= $p['id'] ?>">❤️ <?= $p['curtidas'] ?></a>
                            <?php if ($p['usuario'] == $usuario_logado): ?>
                                <a href="feed.php?excluir=<?= $p['id'] ?>" onclick="return confirm('Excluir este pôster?')">🗑️</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>
</html>