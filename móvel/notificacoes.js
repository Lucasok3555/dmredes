// notificacoes.js

let ultimaNotificacaoId = null;

function exibirNotificacao(mensagem, data) {
    // Cria um toast/balão de notificação
    const notif = document.createElement('div');
    notif.style.position = 'fixed';
    notif.style.top = '20px';
    notif.style.right = '20px';
    notif.style.padding = '15px';
    notif.style.background = '#007bff';
    notif.style.color = 'white';
    notif.style.borderRadius = '8px';
    notif.style.boxShadow = '0 4px 8px rgba(0,0,0,0.2)';
    notif.style.zIndex = '9999';
    notif.style.maxWidth = '300px';
    notif.innerHTML = `
        <strong>🔔 Notificação:</strong><br>
        ${mensagem}<br>
        <small>${data}</small>
        <button onclick="this.parentElement.remove()" style="float:right; background:none; border:none; color:white; cursor:pointer;">✕</button>
    `;

    document.body.appendChild(notif);

    // Remove automaticamente após 5 segundos
    setTimeout(() => {
        if (notif && notif.parentNode) {
            notif.remove();
        }
    }, 5000);
}

async function verificarNotificacoes() {
    try {
        const res = await fetch('notificacoes.json?' + Date.now()); // evitar cache
        if (!res.ok) return;

        const data = await res.json();
        if (data.id && data.id !== ultimaNotificacaoId) {
            ultimaNotificacaoId = data.id;
            exibirNotificacao(data.mensagem, data.data);
        }
    } catch (e) {
        console.log("Erro ao verificar notificações:", e);
    }
}

// Verifica a cada 10 segundos
setInterval(verificarNotificacoes, 10000);

// Verifica ao carregar a página
document.addEventListener('DOMContentLoaded', verificarNotificacoes);