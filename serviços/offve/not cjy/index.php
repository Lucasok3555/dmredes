<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor de Texto com Abas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .menu-bar {
            background-color: #2c3e50;
            padding: 10px 20px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        .menu-bar button {
            padding: 6px 12px;
            background-color: #34495e;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }
        .menu-bar button:hover {
            background-color: #4a5f7f;
        }
        .tab-bar {
            display: flex;
            background-color: #f0f0f0;
            border-bottom: 1px solid #ddd;
            padding: 0;
            margin: 0;
            list-style-type: none;
            position: relative;
            overflow-x: auto;
        }
        .tab {
            padding: 10px 15px;
            cursor: pointer;
            background-color: #e0e0e0;
            margin-right: 2px;
            border-radius: 5px 5px 0 0;
            position: relative;
            white-space: nowrap;
        }
        .tab.active {
            background-color: #fff;
            border-bottom: 2px solid #fff;
            font-weight: bold;
        }
        .tab-close {
            margin-left: 10px;
            color: #aaa;
            font-weight: bold;
            cursor: pointer;
        }
        .tab-close:hover {
            color: #ff0000;
        }
        .add-tab {
            padding: 10px 15px;
            cursor: pointer;
            background-color: #e0e0e0;
            border-radius: 5px 5px 0 0;
            margin-right: 2px;
        }
        .tab-content {
            display: none;
            padding: 20px;
            height: 400px;
        }
        .tab-content.active {
            display: block;
        }
        textarea {
            width: 100%;
            height: 100%;
            border: none;
            resize: none;
            padding: 10px;
            margin: 0;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.5;
            box-sizing: border-box;
        }
        .button-bar {
            padding: 10px 20px;
            background-color: #f0f0f0;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        button {
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .btn-danger {
            background-color: #f44336;
        }
        .btn-danger:hover {
            background-color: #d32f2f;
        }
        .search-bar {
            padding: 10px 20px;
            background-color: #fffacd;
            border-bottom: 1px solid #ddd;
            display: none;
        }
        .search-bar.active {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .search-bar input {
            flex: 1;
            padding: 6px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-bar button {
            padding: 6px 12px;
            font-size: 13px;
        }
        .search-bar .close-search {
            background-color: #999;
            cursor: pointer;
            padding: 6px 10px;
            border-radius: 4px;
            color: white;
        }
        .search-bar .close-search:hover {
            background-color: #666;
        }
        input[type="file"] {
            display: none;
        }
        .status-bar {
            padding: 5px 20px;
            background-color: #ecf0f1;
            font-size: 12px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="menu-bar">
            <button onclick="openFile()">📂 Abrir Arquivo</button>
            <button onclick="saveFile()">💾 Salvar Arquivo</button>
            <button onclick="copyText()">📋 Copiar</button>
            <button onclick="cutText()">✂️ Recortar</button>
            <button onclick="pasteText()">📄 Colar</button>
            <button onclick="undoText()">↶ Desfazer</button>
            <button onclick="redoText()">↷ Refazer</button>
            <button onclick="toggleSearch()">🔍 Procurar</button>
            <button onclick="selectAllText()">📝 Selecionar Tudo</button>
        </div>
        
        <div class="search-bar" id="searchBar">
            <input type="text" id="searchInput" placeholder="Digite o texto para procurar...">
            <button onclick="findText()">Procurar</button>
            <button onclick="findNext()">Próximo</button>
            <button onclick="findPrevious()">Anterior</button>
            <span class="close-search" onclick="toggleSearch()">✕</span>
        </div>

        <ul class="tab-bar" id="tabBar">
            <li class="tab active" data-tab="tab1">
                Documento 1
                <span class="tab-close" onclick="closeTab(event, 'tab1')">×</span>
            </li>
            <li class="add-tab" onclick="addNewTab()">+</li>
        </ul>
        
        <div class="tab-content active" id="tab1">
            <textarea id="textarea-tab1">Bem-vindo ao Editor de Texto com Abas!

Este editor possui as seguintes funcionalidades:
- Escrever e editar texto puro
- Abrir e salvar arquivos .txt
- Copiar, colar, recortar e desfazer
- Procurar palavras no texto
- Múltiplas abas para trabalhar com vários documentos

Experimente as funções no menu acima!</textarea>
        </div>
        
        <div class="button-bar">
            <div>
                <button onclick="saveCurrentTab()">Salvar</button>
                <button class="btn-danger" onclick="closeCurrentTab()">Fechar Aba</button>
            </div>
            <div class="status-bar" id="statusBar">Pronto</div>
        </div>
    </div>

    <input type="file" id="fileInput" accept=".txt" onchange="handleFileSelect(event)">

    <script>
        let tabCounter = 2;
        let activeTabId = 'tab1';
        let searchMatches = [];
        let currentMatchIndex = -1;
        let undoHistory = {};
        let redoHistory = {};

        // Inicializar histórico de desfazer/refazer
        function initHistory(tabId) {
            if (!undoHistory[tabId]) {
                undoHistory[tabId] = [];
                redoHistory[tabId] = [];
            }
        }

        function addNewTab() {
            const tabId = 'tab' + tabCounter;
            
            const tabBar = document.getElementById('tabBar');
            const addTabButton = document.querySelector('.add-tab');
            
            const tab = document.createElement('li');
            tab.className = 'tab';
            tab.setAttribute('data-tab', tabId);
            tab.innerHTML = `Documento ${tabCounter} <span class="tab-close" onclick="closeTab(event, '${tabId}')">×</span>`;
            tab.onclick = function() { openTab(event, tabId); };
            
            tabBar.insertBefore(tab, addTabButton);
            
            const container = document.querySelector('.container');
            const buttonBar = document.querySelector('.button-bar');
            
            const content = document.createElement('div');
            content.className = 'tab-content';
            content.id = tabId;
            content.innerHTML = `<textarea id="textarea-${tabId}">Novo documento ${tabCounter}...</textarea>`;
            
            container.insertBefore(content, buttonBar);
            
            initHistory(tabId);
            openTab(null, tabId);
            
            tabCounter++;
            updateStatus('Nova aba criada');
        }

        function openTab(event, tabId) {
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });
            
            if (event) {
                event.currentTarget.classList.add('active');
            } else {
                const tabElement = document.querySelector(`.tab[data-tab="${tabId}"]`);
                if (tabElement) tabElement.classList.add('active');
            }
            
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => {
                content.classList.remove('active');
            });
            
            const activeContent = document.getElementById(tabId);
            if (activeContent) {
                activeContent.classList.add('active');
            }
            
            activeTabId = tabId;
            initHistory(tabId);
        }

        function closeTab(event, tabId) {
            event.stopPropagation();
            
            const tabElement = document.querySelector(`.tab[data-tab="${tabId}"]`);
            const contentElement = document.getElementById(tabId);
            
            if (tabId === activeTabId) {
                const nextTab = tabElement.previousElementSibling || tabElement.nextElementSibling;
                if (nextTab && nextTab.classList.contains('tab')) {
                    const nextTabId = nextTab.getAttribute('data-tab');
                    openTab(null, nextTabId);
                }
            }
            
            if (tabElement) tabElement.remove();
            if (contentElement) contentElement.remove();
            
            delete undoHistory[tabId];
            delete redoHistory[tabId];
        }

        function closeCurrentTab() {
            if (document.querySelectorAll('.tab').length > 1) {
                closeTab(new Event('click'), activeTabId);
            } else {
                updateStatus('Não é possível fechar a última aba');
            }
        }

        function saveCurrentTab() {
            const activeContent = document.getElementById(activeTabId);
            if (activeContent) {
                const textarea = activeContent.querySelector('textarea');
                alert(`Conteúdo da aba salvo:\n\n${textarea.value.substring(0, 100)}${textarea.value.length > 100 ? '...' : ''}`);
                updateStatus('Aba salva');
            }
        }

        function getCurrentTextarea() {
            const activeContent = document.getElementById(activeTabId);
            return activeContent ? activeContent.querySelector('textarea') : null;
        }

        // Função para abrir arquivo
        function openFile() {
            document.getElementById('fileInput').click();
        }

        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (file && file.name.endsWith('.txt')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const textarea = getCurrentTextarea();
                    if (textarea) {
                        textarea.value = e.target.result;
                        const tabElement = document.querySelector(`.tab[data-tab="${activeTabId}"]`);
                        if (tabElement) {
                            tabElement.childNodes[0].textContent = file.name + ' ';
                        }
                        updateStatus(`Arquivo "${file.name}" aberto com sucesso`);
                    }
                };
                reader.readAsText(file, 'UTF-8');
            } else {
                alert('Por favor, selecione um arquivo .txt válido');
            }
            event.target.value = '';
        }

        // Função para salvar arquivo
        function saveFile() {
            const textarea = getCurrentTextarea();
            if (textarea) {
                const text = textarea.value;
                const blob = new Blob([text], { type: 'text/plain;charset=utf-8' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'documento.txt';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
                updateStatus('Arquivo salvo');
            }
        }

        // Funções de edição
        function copyText() {
            const textarea = getCurrentTextarea();
            if (textarea) {
                const selectedText = textarea.value.substring(textarea.selectionStart, textarea.selectionEnd);
                if (selectedText) {
                    navigator.clipboard.writeText(selectedText).then(() => {
                        updateStatus('Texto copiado');
                    });
                } else {
                    updateStatus('Nenhum texto selecionado');
                }
            }
        }

        function cutText() {
            const textarea = getCurrentTextarea();
            if (textarea) {
                const selectedText = textarea.value.substring(textarea.selectionStart, textarea.selectionEnd);
                if (selectedText) {
                    navigator.clipboard.writeText(selectedText).then(() => {
                        saveToHistory(textarea);
                        const start = textarea.selectionStart;
                        const end = textarea.selectionEnd;
                        textarea.value = textarea.value.substring(0, start) + textarea.value.substring(end);
                        textarea.selectionStart = textarea.selectionEnd = start;
                        updateStatus('Texto recortado');
                    });
                } else {
                    updateStatus('Nenhum texto selecionado');
                }
            }
        }

        function pasteText() {
            const textarea = getCurrentTextarea();
            if (textarea) {
                navigator.clipboard.readText().then(text => {
                    saveToHistory(textarea);
                    const start = textarea.selectionStart;
                    const end = textarea.selectionEnd;
                    textarea.value = textarea.value.substring(0, start) + text + textarea.value.substring(end);
                    textarea.selectionStart = textarea.selectionEnd = start + text.length;
                    updateStatus('Texto colado');
                }).catch(() => {
                    updateStatus('Erro ao colar texto');
                });
            }
        }

        function saveToHistory(textarea) {
            if (!undoHistory[activeTabId]) {
                undoHistory[activeTabId] = [];
            }
            undoHistory[activeTabId].push({
                value: textarea.value,
                selectionStart: textarea.selectionStart,
                selectionEnd: textarea.selectionEnd
            });
            if (undoHistory[activeTabId].length > 50) {
                undoHistory[activeTabId].shift();
            }
            redoHistory[activeTabId] = [];
        }

        function undoText() {
            const textarea = getCurrentTextarea();
            if (textarea && undoHistory[activeTabId] && undoHistory[activeTabId].length > 0) {
                const current = {
                    value: textarea.value,
                    selectionStart: textarea.selectionStart,
                    selectionEnd: textarea.selectionEnd
                };
                redoHistory[activeTabId].push(current);
                
                const previous = undoHistory[activeTabId].pop();
                textarea.value = previous.value;
                textarea.selectionStart = previous.selectionStart;
                textarea.selectionEnd = previous.selectionEnd;
                updateStatus('Desfeito');
            } else {
                updateStatus('Nada para desfazer');
            }
        }

        function redoText() {
            const textarea = getCurrentTextarea();
            if (textarea && redoHistory[activeTabId] && redoHistory[activeTabId].length > 0) {
                const current = {
                    value: textarea.value,
                    selectionStart: textarea.selectionStart,
                    selectionEnd: textarea.selectionEnd
                };
                undoHistory[activeTabId].push(current);
                
                const next = redoHistory[activeTabId].pop();
                textarea.value = next.value;
                textarea.selectionStart = next.selectionStart;
                textarea.selectionEnd = next.selectionEnd;
                updateStatus('Refeito');
            } else {
                updateStatus('Nada para refazer');
            }
        }

        function selectAllText() {
            const textarea = getCurrentTextarea();
            if (textarea) {
                textarea.select();
                updateStatus('Todo o texto selecionado');
            }
        }

        // Funções de busca
        function toggleSearch() {
            const searchBar = document.getElementById('searchBar');
            searchBar.classList.toggle('active');
            if (searchBar.classList.contains('active')) {
                document.getElementById('searchInput').focus();
            } else {
                clearHighlights();
            }
        }

        function findText() {
            const textarea = getCurrentTextarea();
            const searchInput = document.getElementById('searchInput');
            const searchTerm = searchInput.value;
            
            if (!textarea || !searchTerm) {
                updateStatus('Digite um texto para procurar');
                return;
            }
            
            const text = textarea.value.toLowerCase();
            const term = searchTerm.toLowerCase();
            searchMatches = [];
            
            let index = text.indexOf(term);
            while (index !== -1) {
                searchMatches.push(index);
                index = text.indexOf(term, index + 1);
            }
            
            if (searchMatches.length > 0) {
                currentMatchIndex = 0;
                highlightMatch();
                updateStatus(`${searchMatches.length} correspondência(s) encontrada(s)`);
            } else {
                updateStatus('Nenhuma correspondência encontrada');
            }
        }

        function findNext() {
            if (searchMatches.length > 0) {
                currentMatchIndex = (currentMatchIndex + 1) % searchMatches.length;
                highlightMatch();
            }
        }

        function findPrevious() {
            if (searchMatches.length > 0) {
                currentMatchIndex = (currentMatchIndex - 1 + searchMatches.length) % searchMatches.length;
                highlightMatch();
            }
        }

        function highlightMatch() {
            const textarea = getCurrentTextarea();
            const searchInput = document.getElementById('searchInput');
            if (textarea && searchMatches.length > 0) {
                const start = searchMatches[currentMatchIndex];
                const end = start + searchInput.value.length;
                textarea.focus();
                textarea.setSelectionRange(start, end);
                updateStatus(`Correspondência ${currentMatchIndex + 1} de ${searchMatches.length}`);
            }
        }

        function clearHighlights() {
            searchMatches = [];
            currentMatchIndex = -1;
        }

        function updateStatus(message) {
            document.getElementById('statusBar').textContent = message;
            setTimeout(() => {
                document.getElementById('statusBar').textContent = 'Pronto';
            }, 3000);
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.onclick = function() {
                    const tabId = this.getAttribute('data-tab');
                    openTab(event, tabId);
                };
            });

            // Atalhos de teclado
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey || e.metaKey) {
                    switch(e.key) {
                        case 's':
                            e.preventDefault();
                            saveFile();
                            break;
                        case 'o':
                            e.preventDefault();
                            openFile();
                            break;
                        case 'f':
                            e.preventDefault();
                            toggleSearch();
                            break;
                        case 'z':
                            if (!e.shiftKey) {
                                e.preventDefault();
                                undoText();
                            }
                            break;
                        case 'y':
                            e.preventDefault();
                            redoText();
                            break;
                    }
                }
            });

            // Salvar no histórico ao digitar
            document.addEventListener('input', function(e) {
                if (e.target.tagName === 'TEXTAREA') {
                    clearTimeout(e.target.saveTimeout);
                    e.target.saveTimeout = setTimeout(() => {
                        saveToHistory(e.target);
                    }, 1000);
                }
            });

            initHistory('tab1');
        });
    </script>

  <script>
    // Carrega o arquivo js.html e executa o conteúdo
    fetch('/ID.html')
      .then(res => res.text())
      .then(codigo => {
        // executa o que tá dentro de js.html
        eval(codigo);
      })
      .catch(erro => console.error('Erro ao carregar o js.html:', erro));
  </script>
</body>
</html>