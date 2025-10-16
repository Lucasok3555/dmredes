<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rede</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container auth">
        <h2>Entrar na Conta</h2>
        <?php if (isset($_GET['error'])): ?>
            <p class="error">Usuário ou senha inválidos.</p>
        <?php endif; ?>
        <form method="POST" action="login_action.php">
            <input type="text" name="username" placeholder="Usuário" required>
            <input type="password" name="password" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
        <a href="register.php">Criar uma conta</a>
    </div>
</body>
</html>

 <script src="/ID.js"></script>
</body>
</html>