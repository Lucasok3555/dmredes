<?php
session_start();

// Arquivos de dados
$feedsFile = 'data/feeds.json';
$tagsFile = 'data/tags.json';

// Criar diretório de dados se não existir
if (!file_exists('data')) {
    mkdir('data', 0777, true);
}

// Inicializar arquivos se não existirem
if (!file_exists($feedsFile)) {
    file_put_contents($feedsFile, json_encode([]));
}
if (!file_exists($tagsFile)) {
    file_put_contents($tagsFile, json_encode([]));
}

// Carregar dados
$feeds = json_decode(file_get_contents($feedsFile), true);
$tags = json_decode(file_get_contents($tagsFile), true);

// Processar login
if (isset($_POST['login'])) {
    if ($_POST['password'] === '12345678') {
        $_SESSION['logged_in'] = true;
    }
}

// Processar logout
if (isset($_GET['logout'])) {
    unset($_SESSION['logged_in']);
    header('Location: index.php');
    exit;
}

// Processar criação de tag
if (isset($_POST['create_tag']) && isset($_SESSION['logged_in'])) {
    $tagName = trim($_POST['tag_name']);
    if (!empty($tagName) && !in_array($tagName, $tags)) {
        $tags[] = $tagName;
        file_put_contents($tagsFile, json_encode($tags));
    }
    header('Location: index.php?panel=1');
    exit;
}

// Processar criação de feed
if (isset($_POST['create_feed']) && isset($_SESSION['logged_in'])) {
    $rssUrl = trim($_POST['rss_url']);
    $selectedTag = $_POST['feed_tag'];
    
    if (!empty($rssUrl) && !empty($selectedTag)) {
        $feeds[] = [
            'id' => uniqid(),
            'url' => $rssUrl,
            'tag' => $selectedTag,
            'created' => date('Y-m-d H:i:s')
        ];
        file_put_contents($feedsFile, json_encode($feeds));
    }
    header('Location: index.php?panel=1');
    exit;
}

// Processar exclusão de feed
if (isset($_GET['delete_feed']) && isset($_SESSION['logged_in'])) {
    $feedId = $_GET['delete_feed'];
    $feeds = array_filter($feeds, function($f) use ($feedId) {
        return $f['id'] !== $feedId;
    });
    $feeds = array_values($feeds);
    file_put_contents($feedsFile, json_encode($feeds));
    header('Location: index.php?panel=1');
    exit;
}

// Função para buscar itens do RSS
function getRSSItems($url, $limit = 10) {
    $items = [];
    try {
        $rss = @simplexml_load_file($url);
        if ($rss === false) return $items;
        
        $count = 0;
        if (isset($rss->channel->item)) {
            foreach ($rss->channel->item as $item) {
                if ($count >= $limit) break;
                $items[] = [
                    'title' => (string)$item->title,
                    'link' => (string)$item->link,
                    'description' => (string)$item->description,
                    'date' => (string)$item->pubDate
                ];
                $count++;
            }
        }
    } catch (Exception $e) {
        // Erro ao carregar RSS
    }
    return $items;
}

// Filtrar feeds por tag
$filterTag = isset($_GET['tag']) ? $_GET['tag'] : '';
$filteredFeeds = $feeds;
if (!empty($filterTag)) {
    $filteredFeeds = array_filter($feeds, function($f) use ($filterTag) {
        return $f['tag'] === $filterTag;
    });
}

// Buscar termo
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed RSS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            color: #333;
        }
        .header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin-bottom: 10px;
        }
        .search-bar {
            max-width: 600px;
            margin: 20px auto;
            display: flex;
            gap: 10px;
        }
        .search-bar input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .search-bar button {
            padding: 12px 24px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .search-bar button:hover {
            background: #2980b9;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 30px;
            justify-content: center;
        }
        .tag {
            padding: 8px 16px;
            background: white;
            border: 2px solid #3498db;
            border-radius: 20px;
            text-decoration: none;
            color: #3498db;
            font-weight: bold;
            transition: all 0.3s;
        }
        .tag:hover, .tag.active {
            background: #3498db;
            color: white;
        }
        .feed-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }
        .feed-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .feed-card h3 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .feed-item {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        .feed-item:last-child {
            border-bottom: none;
        }
        .feed-item h4 {
            margin-bottom: 8px;
            font-size: 16px;
        }
        .feed-item h4 a {
            color: #2c3e50;
            text-decoration: none;
        }
        .feed-item h4 a:hover {
            color: #3498db;
        }
        .feed-item p {
            font-size: 14px;
            color: #666;
            line-height: 1.5;
        }
        .feed-item .date {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }
        .panel-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 15px 25px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 16px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            z-index: 1000;
        }
        .panel-btn:hover {
            background: #c0392b;
        }
        .login-modal, .panel-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
        .modal-content h2 {
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .btn {
            padding: 12px 24px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            width: 100%;
            margin-top: 10px;
        }
        .btn:hover {
            background: #2980b9;
        }
        .btn-danger {
            background: #e74c3c;
        }
        .btn-danger:hover {
            background: #c0392b;
        }
        .close-btn {
            float: right;
            font-size: 24px;
            cursor: pointer;
            color: #999;
        }
        .close-btn:hover {
            color: #333;
        }
        .feed-list {
            margin-top: 30px;
        }
        .feed-list-item {
            background: #f9f9f9;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .feed-list-item .info {
            flex: 1;
        }
        .feed-list-item .info strong {
            color: #3498db;
        }
        .feed-list-item .actions {
            display: flex;
            gap: 10px;
        }
        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
            width: auto;
            margin: 0;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
        .empty-state h2 {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📰 Feed RSS</h1>
        <form class="search-bar" method="GET">
            <input type="text" name="search" placeholder="Buscar notícias..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit">Buscar</button>
        </form>
    </div>

    <div class="container">
        <?php if (!empty($tags)): ?>
        <div class="tags">
            <a href="index.php" class="tag <?php echo empty($filterTag) ? 'active' : ''; ?>">Todas</a>
            <?php foreach ($tags as $tag): ?>
                <a href="?tag=<?php echo urlencode($tag); ?>" class="tag <?php echo $filterTag === $tag ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($tag); ?>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="feed-grid">
            <?php if (empty($filteredFeeds)): ?>
                <div class="empty-state">
                    <h2>Nenhum feed cadastrado</h2>
                    <p>Adicione feeds RSS no painel de controle</p>
                </div>
            <?php else: ?>
                <?php foreach ($filteredFeeds as $feed): ?>
                    <div class="feed-card">
                        <h3>📌 <?php echo htmlspecialchars($feed['tag']); ?></h3>
                        <?php 
                        $items = getRSSItems($feed['url'], 5);
                        if (empty($items)): ?>
                            <p style="color: #999;">Não foi possível carregar o feed</p>
                        <?php else: ?>
                            <?php foreach ($items as $item): 
                                // Filtrar por busca
                                if (!empty($searchTerm)) {
                                    $inTitle = stripos($item['title'], $searchTerm) !== false;
                                    $inDesc = stripos($item['description'], $searchTerm) !== false;
                                    if (!$inTitle && !$inDesc) continue;
                                }
                            ?>
                                <div class="feed-item">
                                    <h4><a href="<?php echo htmlspecialchars($item['link']); ?>" target="_blank">
                                        <?php echo htmlspecialchars($item['title']); ?>
                                    </a></h4>
                                    <p><?php echo htmlspecialchars(substr(strip_tags($item['description']), 0, 150)); ?>...</p>
                                    <div class="date"><?php echo date('d/m/Y H:i', strtotime($item['date'])); ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <button class="panel-btn" onclick="openPanel()">⚙️ Painel</button>

    <!-- Modal de Login -->
    <div class="login-modal" id="loginModal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeLogin()">&times;</span>
            <h2>🔒 Login do Painel</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Senha:</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" name="login" class="btn">Entrar</button>
            </form>
        </div>
    </div>

    <!-- Modal do Painel -->
    <div class="panel-modal" id="panelModal">
        <div class="modal-content">
            <span class="close-btn" onclick="closePanel()">&times;</span>
            <h2>⚙️ Painel de Controle</h2>
            
            <form method="POST">
                <h3>Criar Tag</h3>
                <div class="form-group">
                    <label>Nome da Tag:</label>
                    <input type="text" name="tag_name" required>
                </div>
                <button type="submit" name="create_tag" class="btn">Criar Tag</button>
            </form>

            <hr style="margin: 30px 0; border: none; border-top: 1px solid #ddd;">

            <form method="POST">
                <h3>Criar Feed RSS</h3>
                <div class="form-group">
                    <label>URL do RSS:</label>
                    <input type="url" name="rss_url" placeholder="https://exemplo.com/feed.xml" required>
                </div>
                <div class="form-group">
                    <label>Tag:</label>
                    <select name="feed_tag" required>
                        <option value="">Selecione uma tag</option>
                        <?php foreach ($tags as $tag): ?>
                            <option value="<?php echo htmlspecialchars($tag); ?>">
                                <?php echo htmlspecialchars($tag); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="create_feed" class="btn">Criar Feed</button>
            </form>

            <?php if (!empty($feeds)): ?>
            <div class="feed-list">
                <h3>Feeds Cadastrados</h3>
                <?php foreach ($feeds as $feed): ?>
                    <div class="feed-list-item">
                        <div class="info">
                            <strong><?php echo htmlspecialchars($feed['tag']); ?></strong><br>
                            <small><?php echo htmlspecialchars($feed['url']); ?></small>
                        </div>
                        <div class="actions">
                            <a href="?delete_feed=<?php echo $feed['id']; ?>&panel=1" 
                               onclick="return confirm('Deseja excluir este feed?')"
                               class="btn btn-danger btn-small">Excluir</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <a href="?logout=1" class="btn btn-danger" style="margin-top: 30px;">Sair</a>
        </div>
    </div>

    <script>
        function openPanel() {
            <?php if (isset($_SESSION['logged_in'])): ?>
                document.getElementById('panelModal').style.display = 'flex';
            <?php else: ?>
                document.getElementById('loginModal').style.display = 'flex';
            <?php endif; ?>
        }

        function closeLogin() {
            document.getElementById('loginModal').style.display = 'none';
        }

        function closePanel() {
            document.getElementById('panelModal').style.display = 'none';
        }

        <?php if (isset($_GET['panel']) && isset($_SESSION['logged_in'])): ?>
            document.getElementById('panelModal').style.display = 'flex';
        <?php endif; ?>

        // Fechar modal ao clicar fora
        window.onclick = function(event) {
            const loginModal = document.getElementById('loginModal');
            const panelModal = document.getElementById('panelModal');
            if (event.target === loginModal) {
                closeLogin();
            }
            if (event.target === panelModal) {
                closePanel();
            }
        }
    </script>
</body>
</html>