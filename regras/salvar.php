<?php
// Proteção simples: só aceita requisições POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método não permitido.');
}

$texto = $_POST['texto'] ?? '';

// Salva no arquivo
file_put_contents('conteudo.txt', $texto);

// Resposta de sucesso
http_response_code(200);
echo 'OK';
?>