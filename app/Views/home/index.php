<?php
// app/Views/home/index.php
// Este é o conteúdo que será inserido em $contentForLayout no layouts/main.php
?>

<h2><?php echo isset($contentTitle) ? htmlspecialchars($contentTitle) : 'Bem-vindo(a)'; ?></h2>
<hr class="mb-4">

<div class="row">
    <div class="col-md-4 order-md-2">
        <div class="text-center">
            <!-- A imagem agora aponta para um caminho estático novamente -->
            <img src="/images/danilo.png" alt="Foto de Danilo Fernandes da Silva" class="img-fluid rounded-circle mb-3" style="width: 250px; height: 250px; object-fit: cover; border: 5px solid #eee;">

            <!-- O nome usa a variável global $myName, que vem do seu config/app.php -->
            <h4><?php echo htmlspecialchars($myName ?? 'Danilo F. Silva'); ?></h4>
            <p class="text-muted">Técnico de TI | Entusiasta PHP</p>

            <a href="https://www.linkedin.com/in/danilofernandessilva/" class="btn btn-primary btn-sm" aria-label="LinkedIn" target="_blank">
                <i class="bi bi-linkedin"></i> <span class="visually-hidden">LinkedIn</span>
            </a>
            <a href="https://github.com/danilofernandes1987" class="btn btn-dark btn-sm" aria-label="GitHub" target="_blank">
                <i class="bi bi-github"></i> <span class="visually-hidden">GitHub</span>
            </a>
        </div>
    </div>
    <div class="col-md-8 order-md-1">
        <!--<p class="lead">
            <?php echo isset($welcomeMessage) ? htmlspecialchars($welcomeMessage) : 'Uma mensagem de boas-vindas simpática aqui!'; ?>
        </p>-->
        <p>Seja bem-vindo(a) ao meu espaço online! Atuo na área de Tecnologia da Informação desde 2009, sempre com grande entusiasmo por aprender, ensinar e desenvolver soluções.
            Sou técnico em Informática e licenciado em Computação pelo IFSULDEMINAS, onde também atuo como servidor público desde 2015, lotado no setor de Tecnologia da Informação
            no campus de Pouso Alegre.</p>

        <p>Tenho pós-graduação em Desenvolvimento Web pela PUC Minas e, atualmente, sou mestrando em Ciência da Computação na Universidade Federal de Lavras (UFLA).
            Minha pesquisa busca compreender a aceitação de robôs quadrúpedes por produtores rurais no manejo da lavoura, unindo tecnologia e agricultura de forma inovadora.</p>

        <p>Apesar da minha atuação profissional estar mais voltada para a área técnica, sou apaixonado por desenvolvimento e estou sempre explorando novas ferramentas e
            linguagens. Sinta-se à vontade para conhecer meus projetos, acompanhar minhas publicações e entrar em contato!</p>

        <h2 class="mb-4">Soft Skills</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

            <div class="col">
                <div class="d-flex align-items-start">
                    <i class="bi bi-people-fill fs-3 me-3 text-primary"></i>
                    <div>
                        <h5 class="mb-1">Trabalho em equipe</h5>
                        <p class="mb-0">Colaboração eficaz com colegas em ambientes técnicos e acadêmicos.</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="d-flex align-items-start">
                    <i class="bi bi-chat-left-dots-fill fs-3 me-3 text-success"></i>
                    <div>
                        <h5 class="mb-1">Comunicação clara</h5>
                        <p class="mb-0">Facilidade para transmitir ideias técnicas e orientar usuários com clareza.</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="d-flex align-items-start">
                    <i class="bi bi-lightbulb-fill fs-3 me-3 text-warning"></i>
                    <div>
                        <h5 class="mb-1">Proatividade</h5>
                        <p class="mb-0">Busca constante por soluções e melhorias nos processos e sistemas.</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="d-flex align-items-start">
                    <i class="bi bi-clock-fill fs-3 me-3 text-danger"></i>
                    <div>
                        <h5 class="mb-1">Comprometimento com prazos</h5>
                        <p class="mb-0">Cumprimento rigoroso de cronogramas em projetos e entregas.</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="d-flex align-items-start">
                    <i class="bi bi-person-check-fill fs-3 me-3 text-info"></i>
                    <div>
                        <h5 class="mb-1">Organização</h5>
                        <p class="mb-0">Capacidade de estruturar tarefas, documentações e rotinas de forma eficiente.</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="d-flex align-items-start">
                    <i class="bi bi-cpu-fill fs-3 me-3 text-secondary"></i>
                    <div>
                        <h5 class="mb-1">Pensamento crítico</h5>
                        <p class="mb-0">Análise cuidadosa de problemas para tomada de decisões bem fundamentadas.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>