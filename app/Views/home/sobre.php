<?php
// app/Views/home/index.php
// Este é o conteúdo que será inserido em $contentForLayout no layouts/main.php
?>
<h2><?php echo isset($contentTitle) ? htmlspecialchars($contentTitle) : 'Danilo Fernandes da Silva'; ?></h2>
<p>Esta é a página principal do meu site pessoal, agora com um layout incrível!</p>
<p>Meus Hobbies:</p>
<ul>
    <li>Ler sobre tecnologia</li>
    <li>Programar</li>
    <li>Viajar (quando possível!)</li>
</ul>
