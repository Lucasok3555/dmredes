<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Horário do Servidor em Tempo Real</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
            background-color: #f4f4f4;
        }
        .time {
            font-size: 2.5em;
            color: #333;
            margin: 20px 0;
        }
        h1 {
            color: #555;
        }
    </style>
</head>
<body>
    <h1>🕒 Horário do Servidor</h1>
    <p class="time" id="server-time">Carregando...</p>

    <script>
        // Função para atualizar o horário
        function atualizarHorario() {
            fetch('get-time.php')
                .then(response => response.text())
                .then(horario => {
                    document.getElementById('server-time').textContent = horario;
                })
                .catch(err => {
                    document.getElementById('server-time').textContent = 'Erro ao carregar horário.';
                    console.error('Erro:', err);
                });
        }

        // Atualiza imediatamente e depois a cada 1 segundo
        atualizarHorario();
        setInterval(atualizarHorario, 1000); // 1000ms = 1 segundo
    </script>
</body>
</html>