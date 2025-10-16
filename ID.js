</head>
<body>

  <h1>Posição salva será restaurada</h1>

  <p>Role a página para baixo e recarregue. A posição será restaurada.</p>
  <p style="margin-top: 500px;">Você está aqui → Parte intermediária da página.</p>
  <p style="margin-top: 800px;">Fim da página. Role para cima e recarregue.</p>

  <script>
    // Nome da chave para armazenar no localStorage
    const scrollKey = 'pageScrollPosition';

    // Restaurar posição ao carregar a página
    window.addEventListener('load', () => {
      const savedPosition = localStorage.getItem(scrollKey);
      if (savedPosition) {
        // Restaura a posição de rolagem
        window.scrollTo(0, parseInt(savedPosition, 10));
      }
    });

    // Salvar posição antes de sair da página
    window.addEventListener('beforeunload', () => {
      localStorage.setItem(scrollKey, window.scrollY);
    });

  // Lista básica de identificadores comuns de Smart TVs
  const tvKeywords = ["SMART-TV", "SmartTV", "HbbTV", "NetCast", "Tizen", "Web0S", "PhilipsTV", "SonyTV", "SmartHub"];

  const isSmartTV = tvKeywords.some(keyword => navigator.userAgent.includes(keyword));

  if (isSmartTV) {
    window.location.href = "/tv.html";
  }

// ID.js - controle básico de tráfego suspeito
const maxRequests = 10; // limite de requisições
const users = {}; // armazena contagem de requisições por ID

function checkRequest(userID) {
    if(!users[userID]) {
        users[userID] = {count: 1, blocked: false};
    } else {
        users[userID].count++;
    }

    if(users[userID].count > maxRequests) {
        users[userID].blocked = true;
        console.warn(`Usuário ${userID} bloqueado (tráfego suspeito)`);
        // Coloca na "fila" simulada
        addToQueue(userID);
    }
}

const queue = [];

function addToQueue(userID) {
    queue.push(userID);
    // Aqui você pode criar lógica para liberar devagar ou monitorar
    console.log(`Usuário ${userID} adicionado à fila. Posição: ${queue.length}`);
}

// Exemplo de requisição
function simulateRequest(userID) {
    if(users[userID]?.blocked) {
        console.log(`Usuário ${userID} está bloqueado, aguardando na fila`);
        return;
    }
    checkRequest(userID);
    console.log(`Requisição do usuário ${userID} processada`);
}

// Teste
simulateRequest("tor_user_1");
simulateRequest("tor_user_1");
// repetir várias vezes para simular bloqueio

<script>
(function autoSavePageState() {
  // Configurações
  const DB_NAME = 'paginaAutoSave';
  const STORE_NAME = 'estados';
  const SAVE_DELAY = 300; // debounce (opcional, para não salvar a cada tecla)

  // Gera uma chave única para a página atual (ex: /contato, /dashboard)
  const paginaChave = window.location.pathname + window.location.search;

  let salvarTimeout;

  // Função para coletar o estado de TODOS os elementos editáveis
  function coletarEstado() {
    const estado = {};

    // 1. Campos de formulário
    document.querySelectorAll('input, textarea, select').forEach(el => {
      if (!el.name && !el.id) return; // opcional: só salva se tiver identificador
      const key = el.name || el.id;
      if (el.type === 'checkbox' || el.type === 'radio') {
        estado[key] = el.checked;
      } else {
        estado[key] = el.value;
      }
    });

    // 2. Elementos com contenteditable
    document.querySelectorAll('[contenteditable="true"], [contenteditable=""]').forEach((el, i) => {
      const key = el.id || contenteditable_${i};
      estado[ce_${key}] = el.innerHTML;
    });

    return estado;
  }

  // Salvar no IndexedDB
  function salvarNoIndexedDB(dados) {
    const req = indexedDB.open(DB_NAME, 1);

    req.onupgradeneeded = (e) => {
      const db = e.target.result;
      if (!db.objectStoreNames.contains(STORE_NAME)) {
        db.createObjectStore(STORE_NAME);
      }
    };

    req.onsuccess = () => {
      const db = req.result;
      const tx = db.transaction(STORE_NAME, 'readwrite');
      const store = tx.objectStore(STORE_NAME);
      store.put(dados, paginaChave);
      tx.oncomplete = () => db.close();
    };

    req.onerror = () => console.error('Erro IndexedDB:', req.error);
  }

  // Carregar e restaurar estado
  function carregarDoIndexedDB() {
    const req = indexedDB.open(DB_NAME, 1);

    req.onsuccess = () => {
      const db = req.result;
      const tx = db.transaction(STORE_NAME, 'readonly');
      const store = tx.objectStore(STORE_NAME);
      const getRequest = store.get(paginaChave);

      getRequest.onsuccess = () => {
        const dados = getRequest.result;
        if (!dados) return;

        // Restaurar campos de formulário
        Object.keys(dados).forEach(key => {
          let el;

          if (key.startsWith('ce_')) {
            // Restaurar contenteditable
            const cleanKey = key.replace('ce_', '');
            el = document.getElementById(cleanKey);
            if (!el) {
              // Tentar pelo índice (se não tiver ID)
              const index = cleanKey.replace('contenteditable_', '');
              el = document.querySelectorAll('[contenteditable="true"], [contenteditable=""]')[index];
            }
            if (el) el.innerHTML = dados[key];
          } else {
            // Restaurar input/textarea/select
            el = document.querySelector([name="${key}"], #[${key}]);
            if (el) {
              if (el.type === 'checkbox' || el.type === 'radio') {
                el.checked = dados[key];
              } else {
                el.value = dados[key];
              }
            }
          }
        });

        db.close();
      };
    };
  }

  // Função de salvamento com debounce (opcional)
  function agendarSalvamento() {
    clearTimeout(salvarTimeout);
    salvarTimeout = setTimeout(() => {
      const estado = coletarEstado();
      salvarNoIndexedDB(estado);
      console.log('✅ Estado salvo automaticamente');
    }, SAVE_DELAY);
  }

  // Iniciar quando a página carregar
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', iniciar);
  } else {
    iniciar();
  }

  function iniciar() {
    // Carregar estado salvo
    carregarDoIndexedDB();

    // Observar mudanças em tempo real
    const observar = ['input', 'change', 'blur', 'keyup', 'paste'];
    observar.forEach(evento => {
      document.addEventListener(evento, agendarSalvamento, true); // useCapture = true para pegar antes de outros handlers
    });

    // Também observar mudanças em contenteditable via input
    document.addEventListener('input', (e) => {
      if (e.target.isContentEditable) {
        agendarSalvamento();
      }
    }, true);
  }

})();

<script type="module">
  // Import the functions you need from the SDKs you need
  import { initializeApp } from "https://www.gstatic.com/firebasejs/12.3.0/firebase-app.js";
  import { getAnalytics } from "https://www.gstatic.com/firebasejs/12.3.0/firebase-analytics.js";
  // TODO: Add SDKs for Firebase products that you want to use
  // https://firebase.google.com/docs/web/setup#available-libraries

  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  const firebaseConfig = {
    apiKey: "AIzaSyCeSCr1rO_CHlM214Q2ZtiBXUmv7E9Ib90",
    authDomain: "jjkhjk-5dcad.firebaseapp.com",
    projectId: "jjkhjk-5dcad",
    storageBucket: "jjkhjk-5dcad.firebasestorage.app",
    messagingSenderId: "927579447565",
    appId: "1:927579447565:web:aaaa4d42733381150b5044",
    measurementId: "G-FEFBT5RCTJ"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig);
  const analytics = getAnalytics(app);
</script>

// arquivo: service-worker.js

const CACHE_NAME = 'meu-site-offline-v1';
const URLS_TO_CACHE = [
  '/',
  '/index.php',
  '/style.css',
  '/ID.js',
  '/logo.png'
];

// Instala o Service Worker e salva os arquivos no cache
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(URLS_TO_CACHE);
    })
  );
});

// Intercepta as requisições e serve do cache se estiver offline
self.addEventListener('fetch', (event) => {
  event.respondWith(
    fetch(event.request).catch(() => caches.match(event.request))
  );
});

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-DQGCR653QQ"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-DQGCR653QQ');

































