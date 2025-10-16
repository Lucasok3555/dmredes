<?php
session_start();

// Senha correta
$senha_correta = "Lucas 555";

// Se enviou a senha via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['senha'])) {
    if ($_POST['senha'] === $senha_correta) {
        $_SESSION['logado'] = true;
    } else {
        $erro = "Senha incorreta! Tenta de novo ";
    }
}

// Se já está logado, mostra o painel
if (isset($_SESSION['logado']) && $_SESSION['logado'] === true):
?>

<!DOCTYPE html>
<html>
<head>
    <title>Painel de Notificações</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        textarea { width: 100%; height: 100px; margin-bottom: 10px; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
        #status { margin-top: 20px; padding: 10px; background: #d4edda; color: #155724; }
    </style>
</head>
<body>
    <h2>Enviar Notificação para Todos 📢</h2>
    <form id="formNotificacao">
        <textarea name="mensagem" id="mensagem" placeholder="Digite a notificação hilária aqui..." required></textarea><br>
        <button type="submit">Enviar e zoar geral </button>
    </form>

    <div id="status"></div>

    <script>
        document.getElementById('formNotificacao').addEventListener('submit', async function(e) {
            e.preventDefault();
            const mensagem = document.getElementById('mensagem').value;

            const res = await fetch('enviar_notificacao.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'mensagem=' + encodeURIComponent(mensagem)
            });

            const result = await res.json();
            const statusDiv = document.getElementById('status');
            if (result.status === 'sucesso') {
                statusDiv.innerText = 'Notificação enviada com sucesso! Corre que todo mundo vai ver! 🚀';
                statusDiv.style.background = '#d4edda';
                document.getElementById('mensagem').value = '';
            } else {
                statusDiv.innerText = 'Erro: ' + result.status;
                statusDiv.style.background = '#f8d7da';
            }
        });
    </script>
</body>
</html>

<?php
// Se NÃO está logado, mostra o formulário de senha
else:
?>

<!DOCTYPE html>
<html>
<head>
    <title>Acesso ao Painel Secreto </title>
    <style>
        body { font-family: Arial; text-align: center; padding-top: 100px; background: #f8f9fa; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); display: inline-block; }
        input { padding: 10px; width: 200px; margin: 10px 0; }
        button { padding: 10px 20px; background: #dc3545; color: white; border: none; cursor: pointer; }
        button:hover { background: #c82333; }
        .erro { color: red; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔑 Só os escolhidos entram aqui</h2>
        <p>Qual é a senha mestre?")</p>

        <?php if (isset($erro)): ?>
            <p class="erro"><?= htmlspecialchars($erro) ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="password" name="senha" placeholder="Digite a senha..." required>
            <br>
            <button type="submit">🔓 Entrar e zoar</button>
        </form>
    </div>
</body>
</html>

<?php endif; ?>