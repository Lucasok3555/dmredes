<?php
$diretorio = "fotos/";

if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $nomeTmp = $_FILES['foto']['tmp_name'];
    $nomeOriginal = basename($_FILES['foto']['name']);
    $ext = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));

    // Verifica se é .webp
    if ($ext == "webp") {
        $novoNome = uniqid() . ".webp";
        if (move_uploaded_file($nomeTmp, $diretorio . $novoNome)) {
            echo "Upload feito com sucesso! <a href='index.php'>Voltar</a>";
        } else {
            echo "Erro ao mover o arquivo.";
        }
    } else {
        echo "Somente arquivos .webp são permitidos.";
    }
} else {
    echo "Nenhuma foto enviada.";
}
?>