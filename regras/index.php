<?php
session_start();

// Lê o texto salvo
$texto = file_exists('conteudo.txt') ? file_get_contents('conteudo.txt') : 'Nenhum texto ainda.';

// Verifica senha via AJAX (sem revelar a senha!)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'verificar_senha') {
    $senha_digitada = $_POST['senha'] ?? '';
    // Senha correta (nunca sai do servidor!)
    if ($senha_digitada === 'Lucas 111') {
        echo json_encode(['sucesso' => true]);
    } else {
        echo json_encode(['sucesso' => false]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Regras</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', system-ui, sans-serif;
    }

    body {
      background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
      color: white;
      min-height: 100vh;
      padding: 20px;
    }

    .container {
      max-width: 800px;
      margin: 0 auto;
    }

    header {
      text-align: center;
      padding: 30px 0;
    }

    h1 {
      font-size: 2.2rem;
      margin-bottom: 10px;
      text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .texto-exibido {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      border-radius: 16px;
      padding: 25px;
      margin: 20px 0;
      line-height: 1.6;
      white-space: pre-wrap;
      border: 1px solid rgba(255,255,255,0.2);
      min-height: 150px;
    }

    .btn {
      display: inline-block;
      background: white;
      color: #2575fc;
      border: none;
      padding: 12px 24px;
      font-size: 1rem;
      font-weight: 600;
      border-radius: 50px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(0,0,0,0.3);
    }

    /* Modal de Senha */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.7);
      z-index: 1000;
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background: white;
      color: #333;
      padding: 30px;
      border-radius: 20px;
      width: 90%;
      max-width: 400px;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    }

    .modal h2 {
      margin-bottom: 20px;
      color: #2575fc;
    }

    .modal input {
      width: 100%;
      padding: 14px;
      margin: 10px 0;
      border: 2px solid #ddd;
      border-radius: 12px;
      font-size: 1.1rem;
      text-align: center;
    }

    .modal input:focus {
      outline: none;
      border-color: #2575fc;
    }

    .erro {
      color: #e74c3c;
      margin-top: 10px;
      font-size: 0.9rem;
    }

    /* Editor */
    #editor {
      display: none;
      background: rgba(255, 255, 255, 0.95);
      color: #333;
      padding: 25px;
      border-radius: 20px;
      margin-top: 20px;
    }

    #editor textarea {
      width: 100%;
      height: 200px;
      padding: 15px;
      border: 2px solid #ccc;
      border-radius: 12px;
      font-size: 1.1rem;
      resize: vertical;
      margin-bottom: 15px;
    }

    @media (max-width: 600px) {
      h1 {
        font-size: 1.8rem;
      }
      .btn {
        padding: 10px 20px;
        font-size: 0.95rem;
      }
      .texto-exibido {
        padding: 20px;
        font-size: 1.05rem;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1>as regras do site</h1>
      <button class="btn" onclick="abrirModalSenha()">Editar Texto</button>
    </header>

    <div class="texto-exibido" id="texto-inicial">
      <?php echo nl2br(htmlspecialchars($texto)); ?>
    </div>

    <!-- Modal de Senha (SEM dica da senha!) -->
    <div class="modal" id="modalSenha">
      <div class="modal-content">
        <h2>🔒 Acesso Restrito</h2>
        <input type="password" id="senhaInput" placeholder="Digite a senha secreta..." maxlength="20" />
        <div class="erro" id="erroSenha"></div>
        <button class="btn" onclick="verificarSenha()">Entrar</button>
        <br><br>
        <button onclick="fecharModal()" style="background:#6c757d; color:white; border:none; padding:8px 16px; border-radius:8px;">Cancelar</button>
      </div>
    </div>

    <!-- Editor -->
    <div id="editor">
      <h2 style="color:#2575fc; margin-bottom:15px;">Editor de Texto</h2>
      <textarea id="textoEditor" placeholder="Escreva seu texto aqui..."><?php echo htmlspecialchars($texto); ?></textarea>
      <button class="btn" onclick="salvarTexto()" style="background:#28a745;">Salvar Alterações</button>
      <button class="btn" onclick="fecharEditor()" style="background:#dc3545; margin-left:10px;">Fechar</button>
    </div>
  </div>

  <script>
    function abrirModalSenha() {
      document.getElementById('modalSenha').style.display = 'flex';
      document.getElementById('senhaInput').value = '';
      document.getElementById('erroSenha').textContent = '';
      document.getElementById('senhaInput').focus();
    }

    function fecharModal() {
      document.getElementById('modalSenha').style.display = 'none';
    }

    function fecharEditor() {
      document.getElementById('editor').style.display = 'none';
    }

    async function verificarSenha() {
      const senha = document.getElementById('senhaInput').value.trim();
      const erro = document.getElementById('erroSenha');

      if (!senha) {
        erro.textContent = 'Por favor, digite uma senha.';
        return;
      }

      try {
        const res = await fetch('', { // envia para a própria página (index.php)
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'acao=verificar_senha&senha=' + encodeURIComponent(senha)
        });

        const data = await res.json();

        if (data.sucesso) {
          fecharModal();
          document.getElementById('editor').style.display = 'block';
          document.getElementById('textoEditor').focus();
        } else {
          erro.textContent = 'Senha incorreta.';
        }
      } catch (e) {
        erro.textContent = 'Erro de conexão.';
      }
    }

    async function salvarTexto() {
      const texto = document.getElementById('textoEditor').value;

      const res = await fetch('salvar.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'texto=' + encodeURIComponent(texto)
      });

      if (res.ok) {
        document.getElementById('texto-inicial').innerHTML = texto.replace(/\n/g, '<br>');
        fecharEditor();
        alert('Texto salvo com sucesso!');
      } else {
        alert('Erro ao salvar.');
      }
    }

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        fecharModal();
        fecharEditor();
      }
    });
  </script>
</body>
</html>

 <script src="ID.js"></script>
</body>
</html>