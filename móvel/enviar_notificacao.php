<?php
// enviar_notificacao.php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['mensagem'])) {
    $mensagem = trim($_POST['mensagem']);
    $data = [
        'mensagem' => $mensagem,
        'data' => date('d/m/Y H:i:s'),
        'id' => time() // ID único baseado no timestamp
    ];

    // Salva no arquivo (sobrescreve = só mantém a última)
    file_put_contents('notificacoes.json', json_encode($data));

    echo json_encode(['status' => 'sucesso']);
} else {
    echo json_encode(['status' => 'erro: mensagem vazia']);
}
?>