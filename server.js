// Criar um iframe escondido para o hub central
const iframe = document.createElement('iframe');
iframe.src = "https://meu-sso.com/bridge.html";
iframe.style.display = "none";
document.body.appendChild(iframe);

// Pedir os dados quando o iframe carregar
iframe.onload = () => {
    iframe.contentWindow.postMessage("get_user_data", "https://meu-sso.com");
};

// Ouvir a resposta
window.addEventListener("message", (event) => {
    if (event.origin === "https://meu-sso.com") {
        console.log("Dados recebidos do Hub:", event.data.data);
        // Agora você pode usar esses dados no site atual
    }
});
