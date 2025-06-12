// public/js/cookie-consent.js

// Função para inicializar os scripts que dependem de consentimento (como Google Analytics)
function initConsentDependentScripts() {
    // Se a função para carregar o Google Analytics existir, chame-a
    if (typeof loadGoogleAnalytics === 'function') {
        loadGoogleAnalytics();
    }
    // Você pode adicionar outras inicializações de scripts aqui no futuro
}

// Função para definir um cookie
function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

// Função para obter um cookie
function getCookie(name) {
    const nameEQ = name + "=";
    const ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

// Executa quando o DOM estiver completamente carregado
document.addEventListener('DOMContentLoaded', function() {
    const cookieBanner = document.getElementById('cookie-consent-banner');
    const acceptCookieBtn = document.getElementById('accept-cookie-btn');

    // Se o botão ou o banner não existirem, não faz nada
    if (!cookieBanner || !acceptCookieBtn) {
        return;
    }

    // Verifica se o usuário já deu consentimento
    if (getCookie('user_consent') === 'true') {
        // Se já consentiu, apenas inicializa os scripts
        initConsentDependentScripts();
    } else {
        // Se não consentiu, exibe o banner
        cookieBanner.style.display = 'block';
    }

    // Adiciona o evento de clique ao botão "Aceitar"
    acceptCookieBtn.addEventListener('click', function() {
        // Define um cookie para lembrar o consentimento por 1 ano
        setCookie('user_consent', 'true', 365);
        // Esconde o banner com uma animação de fade-out
        cookieBanner.style.opacity = '0';
        setTimeout(() => {
            cookieBanner.style.display = 'none';
        }, 500); // 500ms é a duração da transição
        
        // Inicializa os scripts dependentes de consentimento
        initConsentDependentScripts();
    });
});