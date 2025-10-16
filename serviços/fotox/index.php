<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Fotos</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
        }

        header h1 {
            color: #2c3e50;
            font-size: 2.2rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        header p {
            color: #7f8c8d;
            font-size: 1rem;
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr;
            gap: 25px;
        }

        @media (min-width: 992px) {
            .main-content {
                grid-template-columns: 1fr 1fr;
            }
        }

        .section {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .section:hover {
            transform: translateY(-5px);
        }

        .section-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 16px 16px 0 0;
        }

        .section-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-body {
            padding: 20px;
        }

        /* Upload Styles */
        .upload-container {
            text-align: center;
            border: 2px dashed #e0e0e0;
            border-radius: 12px;
            padding: 40px 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }

        .upload-container:hover {
            border-color: #667eea;
            background-color: #f8f9ff;
        }

        .upload-container i {
            font-size: 48px;
            color: #667eea;
            margin-bottom: 15px;
        }

        .upload-container p {
            color: #666;
            margin-bottom: 10px;
        }

        .upload-container small {
            color: #999;
        }

        #photoUpload {
            display: none;
        }

        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-reset {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            margin-top: 10px;
            width: 100%;
            padding: 10px;
            font-size: 0.9rem;
        }

        /* Categories */
        .categories {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
            justify-content: center;
        }

        .category-btn {
            background: #f1f3f5;
            color: #495057;
            border: none;
            padding: 8px 16px;
            border-radius: 50px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .category-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: scale(1.05);
        }

        /* Photo Grid */
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 12px;
            margin-top: 15px;
        }

        .photo-item {
            position: relative;
            height: 140px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .photo-item:hover {
            transform: scale(1.03);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .photo-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-item .delete-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            background-color: rgba(231, 76, 60, 0.9);
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .photo-item:hover .delete-btn {
            opacity: 1;
        }

        /* Trash */
        .trash-section {
            text-align: center;
            padding: 30px 20px;
            border: 3px dashed #e74c3c;
            border-radius: 16px;
            margin-top: 20px;
            background-color: #fdf2f2;
            transition: all 0.3s ease;
        }

        .trash-section.drag-over {
            background-color: #fadbd8;
            border-color: #c0392b;
        }

        .trash-icon {
            font-size: 64px;
            color: #e74c3c;
            margin-bottom: 15px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .trash-section p {
            color: #e74c3c;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .trash-section small {
            color: #999;
            font-size: 0.9rem;
        }

        /* Modal Full Screen */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .modal-content {
            position: relative;
            max-width: 90%;
            max-height: 90%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .modal-img {
            max-width: 100%;
            max-height: 70vh;
            border-radius: 8px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
            cursor: zoom-in;
        }

        .modal-controls {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }

        .modal-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .modal-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .modal-btn.delete {
            background: rgba(231, 76, 60, 0.8);
        }

        .modal-btn.delete:hover {
            background: rgba(231, 76, 60, 1);
        }

        .modal-info {
            color: white;
            margin-top: 15px;
            text-align: center;
            font-size: 0.95rem;
            width: 100%;
            background: rgba(0, 0, 0, 0.5);
            padding: 15px;
            border-radius: 8px;
            backdrop-filter: blur(5px);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: 500;
            margin-right: 10px;
        }

        .info-value {
            opacity: 0.9;
        }

        .modal-close {
            position: absolute;
            top: -50px;
            right: 0;
            color: white;
            font-size: 30px;
            cursor: pointer;
            background: none;
            border: none;
            transition: transform 0.3s ease;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        }

        .modal-close:hover {
            transform: rotate(90deg);
        }

        /* Responsive Adjustments */
        @media (max-width: 576px) {
            body {
                padding: 15px;
            }

            header h1 {
                font-size: 1.8rem;
            }

            .upload-container {
                padding: 30px 15px;
            }

            .photo-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
                gap: 8px;
            }

            .photo-item {
                height: 120px;
            }

            .category-btn {
                padding: 6px 12px;
                font-size: 0.8rem;
            }

            .modal-controls {
                flex-direction: column;
                align-items: center;
            }

            .modal-btn {
                width: 100%;
                max-width: 200px;
            }

            .modal-close {
                top: -40px;
            }
        }

        /* Icons using emojis as fallback */
        .icon-upload::before {
            content: "📤 ";
        }

        .icon-image::before {
            content: "🖼️ ";
        }

        .icon-trash::before {
            content: "🗑️ ";
        }

        .icon-delete::before {
            content: "❌";
        }

        .icon-close::before {
            content: "✖️ ";
        }

        .icon-info::before {
            content: "ℹ️ ";
        }

        .icon-sync::before {
            content: "🔄 ";
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Minhas Fotos</h1>
            <p>Organize suas memórias de forma simples e bonita</p>
        </header>

        <div class="main-content">
            <div class="section">
                <div class="section-header">
                    <h2><span class="icon-upload"></span> Upload de Fotos</h2>
                </div>
                <div class="section-body">
                    <label for="photoUpload" class="upload-container">
                        <div>
                            <i>📤</i>
                            <p>Clique para selecionar fotos</p>
                            <small>Ou arraste e solte aqui</small>
                        </div>
                    </label>
                    <input type="file" id="photoUpload" accept="image/*" multiple>
                    
                    <div class="categories" id="categories">
                        <button class="category-btn active" data-category="todas">Todas</button>
                        <button class="category-btn" data-category="pessoas">Pessoas</button>
                        <button class="category-btn" data-category="natureza">Natureza</button>
                        <button class="category-btn" data-category="viagens">Viagens</button>
                        <button class="category-btn" data-category="eventos">Eventos</button>
                    </div>

                    <div class="photo-grid" id="photoGrid">
                        <!-- As fotos serão adicionadas aqui dinamicamente -->
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-header">
                    <h2><span class="icon-trash"></span> Lixeira</h2>
                </div>
                <div class="section-body">
                    <div class="trash-section" id="trashSection">
                        <div class="trash-icon">🗑️</div>
                        <p>Arraste fotos para apagar</p>
                        <small>Clique no ícone de exclusão nas fotos</small>
                    </div>
                    
                    <button class="btn btn-reset" id="resetData">
                        <span class="icon-sync"></span> Resetar Dados
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Tela Cheia -->
    <div class="modal" id="photoModal">
        <button class="modal-close" id="closeModal"><span class="icon-close"></span></button>
        <div class="modal-content">
            <img class="modal-img" id="modalImage" src="" alt="Foto em tela cheia">
            
            <div class="modal-controls">
                <button class="modal-btn delete" id="deleteModalBtn">
                    <span class="icon-delete"></span> Apagar Foto
                </button>
            </div>
            
            <div class="modal-info">
                <div class="info-row">
                    <span class="info-label"><span class="icon-info"></span> Data:</span>
                    <span class="info-value" id="modalDate">-</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Categoria:</span>
                    <span class="info-value" id="modalCategory">-</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nome do Arquivo:</span>
                    <span class="info-value" id="modalFileName">-</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Dimensões:</span>
                    <span class="info-value" id="modalDimensions">-</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tamanho:</span>
                    <span class="info-value" id="modalSize">-</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variáveis para armazenar dados
        let photos = [];
        let currentCategory = 'todas';

        // Elementos do DOM
        const photoUpload = document.getElementById('photoUpload');
        const photoGrid = document.getElementById('photoGrid');
        const categoryButtons = document.querySelectorAll('.category-btn');
        const trashSection = document.getElementById('trashSection');
        const resetDataBtn = document.getElementById('resetData');
        
        // Modal elementos
        const photoModal = document.getElementById('photoModal');
        const modalImage = document.getElementById('modalImage');
        const closeModal = document.getElementById('closeModal');
        const deleteModalBtn = document.getElementById('deleteModalBtn');
        const modalDate = document.getElementById('modalDate');
        const modalCategory = document.getElementById('modalCategory');
        const modalFileName = document.getElementById('modalFileName');
        const modalDimensions = document.getElementById('modalDimensions');
        const modalSize = document.getElementById('modalSize');

        // Foto atualmente exibida no modal
        let currentPhotoId = null;

        // Nome da chave para armazenamento local
        const STORAGE_KEY = 'photoGalleryData';

        // Função para carregar dados do localStorage
        function loadFromLocalStorage() {
            const savedData = localStorage.getItem(STORAGE_KEY);
            if (savedData) {
                try {
                    const data = JSON.parse(savedData);
                    photos = data.photos || [];
                    currentCategory = data.currentCategory || 'todas';
                } catch (e) {
                    console.error('Erro ao carregar dados:', e);
                    photos = [];
                }
            } else {
                // Dados iniciais de exemplo
                photos = [
                    {
                        id: 1,
                        src: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZTFlOHVmIiBzdHJva2U9IiM5OWMzZjciLz48dGV4dCB4PSI1MCUiCB5PSI1MCUiIGZvbnQtc2l6ZT0iMTJweCIgZm9udC1mYW1pbHk9IkFyaWFsIiBmaWxsPSIjMzMzIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5Ob3ZhIEZvdG88L3RleHQ+PC9zdmc+',
                        category: 'pessoas',
                        date: new Date().toLocaleDateString(),
                        fileName: 'foto_exemplo_1.jpg',
                        dimensions: '800 × 600',
                        size: '156 KB',
                        width: 800,
                        height: 600
                    }
                ];
            }
            updateCategoryButton();
            displayPhotos();
        }

        // Função para salvar dados no localStorage
        function saveToLocalStorage() {
            const data = {
                photos: photos,
                currentCategory: currentCategory,
                lastUpdated: new Date().toISOString()
            };
            localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
        }

        // Função para resetar dados
        function resetData() {
            const confirmReset = confirm('Tem certeza que deseja resetar todos os dados? Esta ação não pode ser desfeita.');
            if (confirmReset) {
                localStorage.removeItem(STORAGE_KEY);
                photos = [];
                // Adicionar foto de exemplo novamente
                photos = [
                    {
                        id: 1,
                        src: 'image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZTFlOHVmIiBzdHJva2U9IiM5OWMzZjciLz48dGV4dCB4PSI1MCUiCB5PSI1MCUiIGZvbnQtc2l6ZT0iMTJweCIgZm9udC1mYW1pbHk9IkFyaWFsIiBmaWxsPSIjMzMzIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5Ob3ZhIEZvdG88L3RleHQ+PC9zdmc+',
                        category: 'pessoas',
                        date: new Date().toLocaleDateString(),
                        fileName: 'foto_exemplo_1.jpg',
                        dimensions: '800 × 600',
                        size: '156 KB',
                        width: 800,
                        height: 600
                    }
                ];
                currentCategory = 'todas';
                updateCategoryButton();
                displayPhotos();
            }
        }

        // Upload de fotos
        photoUpload.addEventListener('change', function(event) {
            const files = event.target.files;
            if (files.length > 0) {
                Array.from(files).forEach(file => {
                    if (file.type.match('image.*')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            // Criar imagem temporária para obter dimensões
                            const img = new Image();
                            img.onload = function() {
                                const photo = {
                                    id: Date.now() + Math.random(),
                                    src: e.target.result,
                                    category: 'pessoas',
                                    date: new Date().toLocaleDateString(),
                                    fileName: file.name,
                                    dimensions: `${this.naturalWidth} × ${this.naturalHeight}`,
                                    size: formatFileSize(file.size),
                                    width: this.naturalWidth,
                                    height: this.naturalHeight
                                };
                                photos.push(photo);
                                saveToLocalStorage();
                                displayPhotos();
                            };
                            img.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });

        // Função para formatar tamanho do arquivo
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Função para exibir as fotos
        function displayPhotos() {
            photoGrid.innerHTML = '';
            const filteredPhotos = currentCategory === 'todas' 
                ? photos 
                : photos.filter(photo => photo.category === currentCategory);
            
            if (filteredPhotos.length === 0) {
                photoGrid.innerHTML = `
                    <div style="grid-column: 1/-1; text-align: center; padding: 20px; color: #7f8c8d;">
                        Nenhuma foto encontrada nesta categoria
                    </div>
                `;
                return;
            }
            
            filteredPhotos.forEach(photo => {
                const photoItem = document.createElement('div');
                photoItem.className = 'photo-item';
                photoItem.draggable = true;
                photoItem.dataset.id = photo.id;
                
                photoItem.innerHTML = `
                    <img src="${photo.src}" alt="Foto">
                    <button class="delete-btn" data-id="${photo.id}"><span class="icon-delete"></span></button>
                `;
                
                // Adicionar evento para o botão de exclusão
                photoItem.querySelector('.delete-btn').addEventListener('click', function(e) {
                    e.stopPropagation();
                    deletePhoto(photo.id);
                });
                
                // Eventos de drag
                photoItem.addEventListener('dragstart', function(e) {
                    e.dataTransfer.setData('text/plain', photo.id);
                    photoItem.style.opacity = '0.7';
                });
                
                photoItem.addEventListener('dragend', function() {
                    photoItem.style.opacity = '1';
                });
                
                // Abrir foto em tela cheia ao clicar
                photoItem.addEventListener('click', function(e) {
                    if (!e.target.classList.contains('delete-btn') && !e.target.closest('.delete-btn')) {
                        openFullScreen(photo);
                    }
                });
                
                photoGrid.appendChild(photoItem);
            });
        }

        // Função para abrir foto em tela cheia
        function openFullScreen(photo) {
            modalImage.src = photo.src;
            modalDate.textContent = photo.date;
            modalCategory.textContent = photo.category;
            modalFileName.textContent = photo.fileName;
            modalDimensions.textContent = photo.dimensions;
            modalSize.textContent = photo.size;
            
            currentPhotoId = photo.id;
            photoModal.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Evita rolagem ao abrir o modal
        }

        // Fechar modal
        closeModal.addEventListener('click', closeFullScreen);
        photoModal.addEventListener('click', function(e) {
            if (e.target === photoModal) {
                closeFullScreen();
            }
        });

        function closeFullScreen() {
            photoModal.style.display = 'none';
            document.body.style.overflow = 'auto'; // Restaura a rolagem
            currentPhotoId = null;
        }

        // Função para deletar foto
        function deletePhoto(id) {
            const confirmDelete = confirm('Tem certeza que deseja apagar esta foto?');
            if (confirmDelete) {
                photos = photos.filter(photo => photo.id !== id);
                saveToLocalStorage();
                displayPhotos();
                
                // Se a foto excluída estiver no modal, fechar o modal
                if (currentPhotoId === id) {
                    closeFullScreen();
                }
            }
        }

        // Evento para deletar foto pelo modal
        deleteModalBtn.addEventListener('click', function() {
            if (currentPhotoId) {
                deletePhoto(currentPhotoId);
            }
        });

        // Eventos para categorias
        categoryButtons.forEach(button => {
            button.addEventListener('click', function() {
                categoryButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                currentCategory = this.getAttribute('data-category');
                saveToLocalStorage();
                displayPhotos();
            });
        });

        // Função para atualizar o botão ativo de categoria
        function updateCategoryButton() {
            categoryButtons.forEach(btn => {
                if (btn.getAttribute('data-category') === currentCategory) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }

        // Eventos de drag and drop para a lixeira
        trashSection.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('drag-over');
        });

        trashSection.addEventListener('dragleave', function() {
            this.classList.remove('drag-over');
        });

        trashSection.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');
            
            const photoId = e.dataTransfer.getData('text/plain');
            if (photoId) {
                // Encontrar a foto pelo ID
                const photo = photos.find(p => p.id == photoId);
                if (photo) {
                    deletePhoto(photo.id);
                }
            }
        });

        // Evento para resetar dados
        resetDataBtn.addEventListener('click', resetData);

        // Carregar dados ao iniciar
        loadFromLocalStorage();
    </script>
</body>
</html>