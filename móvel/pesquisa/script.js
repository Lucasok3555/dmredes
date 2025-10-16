const searchInput = document.getElementById('searchInput');
const searchBtn = document.getElementById('searchBtn');
const voiceBtn = document.getElementById('voiceBtn');
const resultsWindow = document.getElementById('resultsWindow');
const resultsList = document.getElementById('resultsList');
const resultSearchInput = document.getElementById('resultSearchInput');
const newSearchBtn = document.getElementById('newSearchBtn');

// Função para buscar na API do DuckDuckGo
async function searchDuckDuckGo(query) {
  if (!query.trim()) return;

  const url = `https://api.duckduckgo.com/?q=${encodeURIComponent(query)}&format=json&pretty=1`;

  try {
    const response = await fetch(url);
    const data = await response.json();

    // Limpa resultados anteriores
    resultsList.innerHTML = '';

    if (!data.RelatedTopics || data.RelatedTopics.length === 0) {
      resultsList.innerHTML = '<p style="text-align:center; color:#666;">Nenhum resultado encontrado.</p>';
      return;
    }

    // Processar cada resultado
    data.RelatedTopics.slice(0, 10).forEach(item => {
      let title = item.Text || item.Title || "Sem título";
      let url = item.FirstURL || '';
      let description = item.Text || '';

      // Extrair domínio para favicon
      let domain = '';
      if (url) {
        const match = url.match(/^(?:https?:\/\/)?([^\/]+)/i);
        domain = match ? match[1] : '';
      }

      // Criar elemento de resultado
      const resultItem = document.createElement('div');
      resultItem.className = 'result-item';

      // Favicon (fallback para Google)
      const faviconUrl = domain ? `https://www.google.com/s2/favicons?domain=${domain}&sz=64` : '';

      resultItem.innerHTML = `
        <img src="${faviconUrl}" alt="Favicon" onerror="this.src='https://via.placeholder.com/60?text=??'" />
        <div class="details">
          <h3>${escapeHtml(title)}</h3>
          <p>${escapeHtml(description.length > 150 ? description.substring(0, 150) + '...' : description)}</p>
          <button class="open-btn" onclick="window.open('${url}', '_blank')">Abrir</button>
        </div>
      `;

      resultsList.appendChild(resultItem);
    });

    // Mostrar janela de resultados
    resultsWindow.classList.remove('hidden');
  } catch (error) {
    console.error("Erro ao buscar:", error);
    resultsList.innerHTML = '<p style="text-align:center; color:red;">Erro ao buscar. Tente novamente.</p>';
  }
}

// Função auxiliar para escapar HTML (evitar XSS)
function escapeHtml(text) {
  const map = {
    '&': '&amp;',
    '<': '<',
    '>': '>',
    '"': '&quot;',
    "'": '&#039;'
  };
  return text.replace(/[&<>"']/g, m => map[m]);
}

// Evento de busca por clique ou Enter
searchBtn.addEventListener('click', () => {
  const query = searchInput.value.trim();
  searchDuckDuckGo(query);
});

searchInput.addEventListener('keypress', (e) => {
  if (e.key === 'Enter') {
    const query = searchInput.value.trim();
    searchDuckDuckGo(query);
  }
});

// Pesquisa por voz
if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
  const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
  const recognition = new SpeechRecognition();
  recognition.lang = 'pt-BR';
  recognition.interimResults = false;
  recognition.maxAlternatives = 1;

  voiceBtn.addEventListener('click', () => {
    recognition.start();
    voiceBtn.textContent = '🎙️ Ouvindo...';
    voiceBtn.disabled = true;
  });

  recognition.onresult = (event) => {
    const transcript = event.results[0][0].transcript;
    searchInput.value = transcript;
    voiceBtn.textContent = '🎤';
    voiceBtn.disabled = false;
    searchDuckDuckGo(transcript);
  };

  recognition.onerror = () => {
    voiceBtn.textContent = '🎤';
    voiceBtn.disabled = false;
    alert('Erro ao reconhecer voz. Tente novamente.');
  };

  recognition.onend = () => {
    voiceBtn.textContent = '🎤';
    voiceBtn.disabled = false;
  };
} else {
  voiceBtn.style.display = 'none'; // Oculta se não suportado
  voiceBtn.title = 'Reconhecimento de voz não suportado neste navegador';
}

// Nova busca dentro da janela de resultados
newSearchBtn.addEventListener('click', () => {
  const query = resultSearchInput.value.trim();
  searchDuckDuckGo(query);
});

resultSearchInput.addEventListener('keypress', (e) => {
  if (e.key === 'Enter') {
    const query = resultSearchInput.value.trim();
    searchDuckDuckGo(query);
  }
});