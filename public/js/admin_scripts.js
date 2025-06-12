// public/js/admin_scripts.js

// Espera o DOM estar completamente carregado para garantir que os elementos existam
document.addEventListener('DOMContentLoaded', function () {
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');

    if (titleInput && slugInput) {
        titleInput.addEventListener('keyup', function () {
            let slug = this.value.toLowerCase()
                .trim()
                .replace(/\s+/g, '-')           // Substitui espaços por -
                .normalize("NFD").replace(/[\u0300-\u036f]/g, "") // Remove acentos
                .replace(/[^\w-]+/g, '')       // Remove caracteres não alfanuméricos exceto - e _
                .replace(/--+/g, '-');          // Substitui múltiplos - por um único -

            slugInput.value = slug;
        });
    }

     // Verifica se existe uma textarea com o seletor #content na página atual
     if (document.querySelector('textarea#content')) {
        tinymce.init({
            selector: 'textarea#content',
            
            plugins: 'code codesample table lists link image media help wordcount autosave preview searchreplace visualblocks',
            
  
            toolbar: 'undo redo | restoredraft | formatselect | bold italic underline | ' +
                     'alignleft aligncenter alignright | ' +
                     'bullist numlist outdent indent | link image media | codesample | code preview',
            
            menubar: 'edit view insert format tools table help',
            height: 500,
            
            image_title: true,
            automatic_uploads: true,
            file_picker_types: 'image',
            images_upload_url: '/admin/upload/image',
            document_base_url: window.location.origin + '/',
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,
            autosave_ask_before_unload: true,
            autosave_interval: '30s',
            autosave_restore_when_empty: true,
            autosave_retention: '20m',
        });
    }
});