<?php // app/Views/admin/dashboard/index.php ?>
<h2><?php echo htmlspecialchars($contentTitle ?? 'Painel Administrativo'); ?></h2>
<p>Este é o seu painel de controle. A partir daqui você poderá gerenciar o conteúdo do site.</p>
<p>Seu nome de usuário é: <strong><?php echo htmlspecialchars($adminUsername ?? ''); ?></strong></p>
<p><a href="/posts" target="_blank">Ver site público (seção de posts)</a></p>