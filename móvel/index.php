 <script src="/ID.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>DMREDE Móvel</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-DQGCR653QQ"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-DQGCR653QQ');
</script>

<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#000000">

  <script src="/sistema/cookie.js"></script>
</body>
</html>

  <script src="t888888888.php"></script>
</body>
</html>

  <script src="https://lucasok3555.github.io/Lucasweb/cookie.js"></script>
</body>
</html>

 <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
</body>
</html>

 <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/src/core.min.js"></script>
</body>
</html>

 <script src="https://cdn.jsdelivr.net/npm/jquery@3/dist/jquery.min.js"></script>
</body>
</html>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>
</html>

 <script src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
</body>
</html>

 <script src="https://unpkg.com/react@18/umd/react.development.js"></script>
</body>
</html>

 <script src="https://cdn.d3js.org/d3.v7.min.js"></script>
</body>
</html>

 <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</body>
</html>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/basil.js/0.4.11/basil.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>DMREDE Móvel</title>
    <!-- ... outros scripts/styles ... -->
</head>
<body>
    <h1></h1>
    <!-- conteúdo da página -->

    <!-- Inclua o script de notificações ANTES do fechamento do body -->
    <script src="notificacoes.js"></script>
</body>
</html>

<?php
// =============================
// PART 1: LÓGICA PHP - RECEBER E SALVAR DADOS
// =============================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['session_data'])) {
    header('Content-Type: application/json');

    $events = $_POST['session_data'];
    $decoded = json_decode($events, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['status' => 'error', 'message' => 'JSON inválido']);
        exit;
    }

    // Cria pasta sessions se não existir
    $dir = 'sessions';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    // Gera nome único do arquivo
    $filename = $dir . '/session_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.json';

    if (file_put_contents($filename, json_encode($decoded, JSON_PRETTY_PRINT))) {
        echo json_encode(['status' => 'success', 'file' => $filename]);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Falha ao salvar']);
    }
    exit;
}
?>

    <script src="https://unpkg.com/rrweb@latest/dist/rrweb.min.js"></script>

<script>
// Variáveis globais
let events = [];
let recording = false;

// Função para iniciar a gravação
function startRecording() {
    if (recording) return;

    rrweb.record({
        emit(event) {
            events.push(event);
        },
        inlineStylesheet: true,
        recordCanvas: true,
        collectFonts: true,
    });

    recording = true;
    document.getElementById('status').innerText = "✅ Gravação iniciada! Saia da página para salvar.";
    document.getElementById('status').className = "success";
}

// Função para enviar os dados para o próprio PHP (este arquivo)
function sendToServer() {
    if (events.length === 0) {
        console.log("Nenhum evento para enviar.");
        return;
    }

    const formData = new FormData();
    formData.append('session_data', JSON.stringify(events));

    fetch('', { // Vazio = mesmo arquivo
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log("Resposta do servidor:", data);
        document.getElementById('status').innerText = "💾 Dados salvos com sucesso: " + (data.file || '');
        document.getElementById('status').className = "success";
    })
    .catch(error => {
        console.error("Erro ao enviar:", error);
        document.getElementById('status').innerText = "❌ Erro ao salvar: " + error.message;
        document.getElementById('status').className = "warning";
    });
}

// Iniciar gravação assim que a página carregar
document.addEventListener('DOMContentLoaded', startRecording);

// Enviar ao sair da página
window.addEventListener('beforeunload', sendToServer);
window.addEventListener('pagehide', sendToServer);

// Opcional: também envia se o usuário clicar em um link externo
document.addEventListener('click', function(e) {
    const link = e.target.closest('a');
    if (link && link.origin !== window.location.origin) {
        // É um link externo — envia antes de navegar
        sendToServer();
    }
});
</script>

</body>
</html>

    <style>
        /* Estilo do Pop-up */
        #popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.8);
            background: #333;
            color: white;
            padding: 24px 40px;
            border-radius: 12px;
            font-size: 1.3rem;
            font-weight: bold;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: all 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        #popup.show {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }

        #popup.hide {
            opacity: 0;
            transform: translate(-50%, -50%) scale(0.9);
        }
    </style>
</head>
<body>

    <!-- Pop-up -->
    <div id="popup">
        <span id="popup-message"></span>
    </div>

    <!-- Áudios -->
    <audio id="audioOnline" src="https://assets.mixkit.co/sfx/preview/mixkit-positive-interface-beep-221.mp3"></audio>
    <audio id="audioOffline" src="WhatsApp Audio 2025-09-22 at 19.13.25 (online-audio-converter.com).mp3"></audio>

    <script>
        const popup = document.getElementById('popup');
        const popupMessage = document.getElementById('popup-message');
        const audioOnline = document.getElementById('audioOnline');
        const audioOffline = document.getElementById('audioOffline');

        // Função para tocar áudio
        function playSound(type) {
            const sound = type === 'online' ? audioOnline : audioOffline;
            sound.currentTime = 0;
            sound.play().catch(e => console.log("Áudio bloqueado até interação do usuário."));
        }

        // Função para mostrar pop-up
        function showPopup(message, type) {
            popupMessage.textContent = message;
            popup.className = 'show';

            // Cor de fundo conforme status
            popup.style.background = type === 'offline' ? '#e74c3c' : '#27ae60';

            // Toca som
            playSound(type);

            // Some após 5 segundos
            setTimeout(() => {
                popup.classList.add('hide');
                setTimeout(() => {
                    popup.classList.remove('show', 'hide');
                }, 350);
            }, 5000);
        }

        // Verifica status inicial
        window.addEventListener('load', () => {
            if (navigator.onLine) {
                showPopup("✅ Conexão restabelecida!", 'online');
            } else {
                showPopup("❌ Você está offline.", 'offline');
            }
        });

        // Eventos de conexão
        window.addEventListener('online', () => {
            showPopup("✅ Conexão restabelecida!", 'online');
        });

        window.addEventListener('offline', () => {
            showPopup("❌ Você está offline. Verifique sua conexão.", 'offline');
        });

        // Libera áudio após primeiro clique
        document.body.addEventListener('click', () => {
            audioOnline.load();
            audioOffline.load();
        }, { once: true });
    </script>

</body>
</html>

    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
        }

        .background-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('WhatsApp Image 2025-09-21 at 10.19.57.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -1;
        }

        .content {
            position: relative;
            color: white;
            font-family: Arial, sans-serif;
            padding: 20px;
            text-align: center;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
        }

        @media (max-width: 768px) {
            .background-container {
                background-image: url('WhatsApp Image 2025-09-21 at 10.19.57.jpeg');
            }
        }
    </style>
</head>
<body>
    <div class="background-container"></div>
    <div class="content">












































































































<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#000000">

<script src="https://lucasok3555.github.io/Lucasweb/cookie.js"></script>
</body>
</html>

<p><a href="/servi%C3%A7os/">Servi&ccedil;o</a> <a href="/sites/"/>sites</a> <a href="/login/">/login</a> <a href="pesquisa/">pesquisa</a></p>

<p style="text-align: center;">Bem-vindo a nossa rede&nbsp;</p>
<p>&nbsp;</p>
<p>Vamos mostrar voc&ecirc;s tudo que a gente tem 😉<br />Aqui voc&ecirc; tem muito mais servi&ccedil;os 🛠️<br />Temos v&aacute;rios servi&ccedil;os online <br />Servi&ccedil;o de v&iacute;deos <br />Servi&ccedil;o de tradu&ccedil;&atilde;o <br />Servi&ccedil;o do IA<br />Servi&ccedil;o do amazamente nuvem <br />Servi&ccedil;o do jogos em nuvem <br />Servi&ccedil;o de pesquisa<br />Servi&ccedil;o de chat<br />Servi&ccedil;o do e-mail<br />Servi&ccedil;o do R&aacute;dio<br />Servi&ccedil;o do cilma<br />Servi&ccedil;o do f&oacute;rum<br />Voc&ecirc; n&atilde;o precisa baixar nada☁️<br />Tudo da nuvem voc&ecirc; n&atilde;o precisa baixar nada <br />Todos os nossos servi&ccedil;os est&atilde;o dentro do nosso site <br />Os nossos servi&ccedil;os os na nuvem s&atilde;o f&aacute;ceis de ser usada<br />O servi&ccedil;o &eacute; 100% na nuvem <br />O site &eacute; totalmente brasileiro 🇧🇷</p>

<p style="text-align: center;">DMREDE CC 2025 <a href="/regras/">regras</a></p>








