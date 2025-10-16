<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guardador de Senhas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .add-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .add-button:hover {
            background-color: #45a049;
        }
        .password-item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #eee;
            align-items: center;
        }
        .password-item:last-child {
            border-bottom: none;
        }
        .password-details {
            flex-grow: 1;
        }
        .site-name {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .credentials {
            font-size: 14px;
            color: #666;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .edit-btn, .delete-btn, .show-btn {
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .edit-btn {
            background-color: #2196F3;
            color: white;
        }
        .delete-btn {
            background-color: #f44336;
            color: white;
        }
        .show-btn {
            background-color: #ff9800;
            color: white;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 500px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .close {
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #45a049;
        }
        .search-box {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
            box-sizing: border-box;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Guardador de Senhas</h1>
        
        <input type="text" id="search" class="search-box" placeholder="Pesquisar por site ou usuário...">
        
        <button id="addButton" class="add-button">+ Adicionar Nova Senha</button>
        
        <div id="passwordList">
            <!-- As senhas serão adicionadas aqui dinamicamente -->
        </div>
    </div>

    <!-- Modal para adicionar/editar -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalTitle">Adicionar Nova Senha</h2>
            <form id="passwordForm">
                <input type="hidden" id="editIndex">
                <div class="form-group">
                    <label for="site">Site/Serviço:</label>
                    <input type="text" id="site" required>
                </div>
                <div class="form-group">
                    <label for="username">Nome de Usuário/E-mail:</label>
                    <input type="text" id="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Senha:</label>
                    <input type="password" id="password" required>
                </div>
                <button type="submit" class="submit-btn">Salvar</button>
            </form>
        </div>
    </div>

    <script>
        // Elementos do DOM
        const passwordList = document.getElementById('passwordList');
        const addButton = document.getElementById('addButton');
        const passwordModal = document.getElementById('passwordModal');
        const closeModal = document.querySelector('.close');
        const passwordForm = document.getElementById('passwordForm');
        const modalTitle = document.getElementById('modalTitle');
        const searchBox = document.getElementById('search');
        
        // Carregar senhas do localStorage
        let passwords = JSON.parse(localStorage.getItem('passwords')) || [];
        
        // Função para salvar no localStorage
        function savePasswords() {
            localStorage.setItem('passwords', JSON.stringify(passwords));
        }
        
        // Função para exibir as senhas
        function displayPasswords(list = passwords) {
            passwordList.innerHTML = '';
            
            if (list.length === 0) {
                passwordList.innerHTML = '<p>Nenhuma senha cadastrada. Clique no botão "+" para adicionar.</p>';
                return;
            }
            
            list.forEach((item, index) => {
                const passwordItem = document.createElement('div');
                passwordItem.className = 'password-item';
                
                const showPassword = document.createElement('button');
                showPassword.className = 'show-btn';
                showPassword.textContent = 'Mostrar';
                showPassword.addEventListener('click', () => {
                    const isPasswordVisible = item.visible || false;
                    if (isPasswordVisible) {
                        item.visible = false;
                        showPassword.textContent = 'Mostrar';
                        passwordSpan.textContent = '••••••••';
                    } else {
                        item.visible = true;
                        showPassword.textContent = 'Ocultar';
                        passwordSpan.textContent = item.password;
                    }
                });
                
                const editButton = document.createElement('button');
                editButton.className = 'edit-btn';
                editButton.textContent = 'Editar';
                editButton.addEventListener('click', () => openEditModal(index));
                
                const deleteButton = document.createElement('button');
                deleteButton.className = 'delete-btn';
                deleteButton.textContent = 'Excluir';
                deleteButton.addEventListener('click', () => deletePassword(index));
                
                const detailsDiv = document.createElement('div');
                detailsDiv.className = 'password-details';
                
                const siteName = document.createElement('div');
                siteName.className = 'site-name';
                siteName.textContent = item.site;
                
                const credentials = document.createElement('div');
                credentials.className = 'credentials';
                
                const usernameSpan = document.createElement('span');
                usernameSpan.textContent = `Usuário: ${item.username}`;
                
                const passwordSpan = document.createElement('span');
                passwordSpan.textContent = '••••••••';
                if (item.visible) {
                    passwordSpan.textContent = item.password;
                    showPassword.textContent = 'Ocultar';
                }
                
                credentials.appendChild(usernameSpan);
                credentials.appendChild(document.createTextNode(' | Senha: '));
                credentials.appendChild(passwordSpan);
                
                detailsDiv.appendChild(siteName);
                detailsDiv.appendChild(credentials);
                
                const actionsDiv = document.createElement('div');
                actionsDiv.className = 'actions';
                actionsDiv.appendChild(showPassword);
                actionsDiv.appendChild(editButton);
                actionsDiv.appendChild(deleteButton);
                
                passwordItem.appendChild(detailsDiv);
                passwordItem.appendChild(actionsDiv);
                
                passwordList.appendChild(passwordItem);
            });
        }
        
        // Função para adicionar uma nova senha
        function addPassword(site, username, password) {
            passwords.push({
                site,
                username,
                password,
                visible: false
            });
            savePasswords();
            displayPasswords();
        }
        
        // Função para editar uma senha
        function editPassword(index, site, username, password) {
            passwords[index] = {
                site,
                username,
                password,
                visible: passwords[index].visible || false
            };
            savePasswords();
            displayPasswords();
        }
        
        // Função para excluir uma senha
        function deletePassword(index) {
            if (confirm('Tem certeza que deseja excluir esta senha?')) {
                passwords.splice(index, 1);
                savePasswords();
                displayPasswords();
            }
        }
        
        // Função para abrir o modal de edição
        function openEditModal(index) {
            modalTitle.textContent = 'Editar Senha';
            document.getElementById('editIndex').value = index;
            document.getElementById('site').value = passwords[index].site;
            document.getElementById('username').value = passwords[index].username;
            document.getElementById('password').value = passwords[index].password;
            passwordModal.style.display = 'block';
        }
        
        // Evento para abrir o modal de adição
        addButton.addEventListener('click', () => {
            modalTitle.textContent = 'Adicionar Nova Senha';
            passwordForm.reset();
            document.getElementById('editIndex').value = '';
            passwordModal.style.display = 'block';
        });
        
        // Evento para fechar o modal
        closeModal.addEventListener('click', () => {
            passwordModal.style.display = 'none';
        });
        
        // Fechar modal ao clicar fora
        window.addEventListener('click', (event) => {
            if (event.target === passwordModal) {
                passwordModal.style.display = 'none';
            }
        });
        
        // Evento de submissão do formulário
        passwordForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const site = document.getElementById('site').value;
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const editIndex = document.getElementById('editIndex').value;
            
            if (editIndex === '') {
                addPassword(site, username, password);
            } else {
                editPassword(editIndex, site, username, password);
            }
            
            passwordModal.style.display = 'none';
        });
        
        // Função de busca
        searchBox.addEventListener('input', () => {
            const searchTerm = searchBox.value.toLowerCase();
            if (searchTerm === '') {
                displayPasswords();
                return;
            }
            
            const filtered = passwords.filter(item => 
                item.site.toLowerCase().includes(searchTerm) || 
                item.username.toLowerCase().includes(searchTerm)
            );
            
            displayPasswords(filtered);
        });
        
        // Inicializar a lista
        displayPasswords();
    </script>
</body>
</html>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-DQGCR653QQ"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-DQGCR653QQ');
</script>