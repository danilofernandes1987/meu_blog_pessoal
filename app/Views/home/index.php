<?php
// app/Views/home/index.php
// Este é o conteúdo que será inserido em $contentForLayout no layouts/main.php
?>

<h2><?php echo isset($contentTitle) ? htmlspecialchars($contentTitle) : 'Bem-vindo(a)'; ?></h2>
<hr class="mb-4">

<div class="row">
    <div class="col-md-4 order-md-2">
        <div class="text-center">

            <?php
            // Define o caminho da foto. Se não houver uma no banco, usa um placeholder.
            $photoSrc = (!empty($publicProfilePhoto))
                ? '/uploads/images/' . htmlspecialchars($publicProfilePhoto)
                : '/images/placeholder-profile.png';
            ?>
            <img src="<?php echo $photoSrc; ?>"
                alt="Foto de Danilo Fernandes da Silva"
                class="img-fluid rounded-circle mb-3"
                style="width: 250px; height: 250px; object-fit: cover; border: 5px solid #eee;">

            <!-- O nome usa a variável global $myName, que vem do seu config/app.php -->
            <h4><?php echo htmlspecialchars($myName ?? 'Danilo F. Silva'); ?></h4>
            <p class="text-muted">Técnico de TI | Entusiasta em Segurança</p>

            <a href="https://www.linkedin.com/in/danilofernandessilva/" class="btn btn-primary btn-sm" aria-label="LinkedIn" target="_blank">
                <i class="bi bi-linkedin"></i> <span class="visually-hidden">LinkedIn</span>
            </a>
            <a href="https://github.com/danilofernandes1987" class="btn btn-dark btn-sm" aria-label="GitHub" target="_blank">
                <i class="bi bi-github"></i> <span class="visually-hidden">GitHub</span>
            </a>
        </div>
    </div>
    <div class="col-md-8 order-md-1">
        <?php
        // Exibe o texto de introdução diretamente do banco de dados.
        echo $introductionText ?? '<p>Texto de apresentação não encontrado.</p>';
        ?>

        <h2 class="mb-4">Soft Skills</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

            <?php if (!empty($softSkills)): ?>
                <?php foreach ($softSkills as $skill): ?>
                    <div class="col">
                        <div class="d-flex align-items-start">
                            <i class="<?php echo htmlspecialchars($skill['icon_class']); ?> fs-3 me-3"></i>
                            <div>
                                <h5 class="mb-1"><?php echo htmlspecialchars($skill['title']); ?></h5>
                                <p class="mb-0"><?php echo htmlspecialchars($skill['description']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhuma habilidade para exibir no momento.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="row mt-5">
    <div class="col-12">
        <h2 class="mb-4">Últimos Posts do Blog</h2>
        <hr class="mb-4">
    </div>

    <div class="col-12">
        <?php if (!empty($recentPosts)): ?>
            <ul class="list-group list-group-flush">
                <?php foreach ($recentPosts as $post): ?>
                    <li class="list-group-item px-0">
                        <a href="/posts/show/<?php echo htmlspecialchars($post['slug']); ?>" class="text-decoration-none h5 text-body">
                            <?php echo htmlspecialchars($post['title']); ?>
                        </a>
                        <p class="text-muted small mb-0">
                            <?php 
                                $publicationDate = $post['published_at'] ?? $post['created_at'];
                                echo 'Publicado em ' . date('d/m/Y', strtotime($publicationDate)); 
                            ?>
                        </p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nenhum post publicado ainda. Volte em breve!</p>
        <?php endif; ?>
    </div>

</div>