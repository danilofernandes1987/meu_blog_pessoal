// public/js/admin_scripts.js

// Espera o DOM estar completamente carregado para garantir que os elementos existam
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');

    if (titleInput && slugInput) {
        titleInput.addEventListener('keyup', function() {
            let slug = this.value.toLowerCase()
                .trim()
                .replace(/\s+/g, '-')           // Substitui espaços por -
                .normalize("NFD").replace(/[\u0300-\u036f]/g, "") // Remove acentos
                .replace(/[^\w-]+/g, '')       // Remove caracteres não alfanuméricos exceto - e _
                .replace(/--+/g, '-');          // Substitui múltiplos - por um único -

            slugInput.value = slug;
        });
    }

    // Você pode adicionar mais scripts relacionados a formulários de admin aqui no futuro
});