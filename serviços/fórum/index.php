<?php
session_start();

$erro = '';
$usuariosFile = 'data/usuarios.json';

// Criar pasta data se não existir
if (!file_exists('data')) {
    mkdir('data');
}

// Carregar usuários
$usuarios = file_exists($usuariosFile) ? json_decode(file_get_contents($usuariosFile), true) : [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    if (empty($usuario) || empty($senha)) {
        $erro = "Preencha todos os campos.";
    } else {
        if (isset($usuarios[$usuario])) {
            // Login
            if ($usuarios[$usuario]['senha'] === $senha) {
                $_SESSION['usuario'] = $usuario;
                header('Location: feed.php');
                exit;
            } else {
                $erro = "Senha incorreta.";
            }
        } else {
            // Criar conta
            $usuarios[$usuario] = [
                'senha' => $senha,
                'criado_em' => date('Y-m-d H:i:s')
            ];
            file_put_contents($usuariosFile, json_encode($usuarios, JSON_PRETTY_PRINT));
            $_SESSION['usuario'] = $usuario;
            header('Location: feed.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login / Criar Conta</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <div class="container">
        <h1>Poster Social</h1>
        <form method="POST">
            <?php if ($erro): ?>
                <p class="erro"><?= $erro ?></p>
            <?php endif; ?>
            <input type="text" name="usuario" placeholder="Nome de usuário" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar ou Criar Conta</button>
        </form>
        <p>Se o nome de usuário não existir, uma conta será criada.</p>
    </div>
</body>
</html>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-DQGCR653QQ"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-DQGCR653QQ');
</script>