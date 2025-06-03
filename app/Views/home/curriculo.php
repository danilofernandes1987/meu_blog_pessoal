<?php
// app/Views/home/index.php
// Este é o conteúdo que será inserido em $contentForLayout no layouts/main.php
?>
<h2><?php echo isset($contentTitle) ? htmlspecialchars($contentTitle) : 'Danilo Fernandes da Silva'; ?></h2>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

    <!-- Formação Acadêmica -->
    <div class="col">
        <div class="d-flex align-items-start">
            <i class="bi bi-mortarboard-fill fs-3 me-3 text-primary"></i>
            <div>
                <h5 class="mb-1">Formação Acadêmica</h5>
                <ul class="mb-0">
                    <li>Técnico em Informática – IFSULDEMINAS</li>
                    <li>Licenciatura em Computação – IFSULDEMINAS</li>
                    <li>Pós em Desenvolvimento Web – PUC Minas</li>
                    <li>Mestrado em Ciência da Computação – UFLA (em andamento)</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Experiência Profissional -->
    <div class="col">
        <div class="d-flex align-items-start">
            <i class="bi bi-briefcase-fill fs-3 me-3 text-success"></i>
            <div>
                <h5 class="mb-1">Experiência Profissional</h5>
                <p class="mb-0">
                    Servidor público no IFSULDEMINAS – Campus Pouso Alegre desde 2015, atuando com suporte técnico, infraestrutura e desenvolvimento.
                </p>
            </div>
        </div>
    </div>

    <!-- Habilidades Técnicas -->
    <div class="col">
        <div class="d-flex align-items-start">
            <i class="bi bi-code-slash fs-3 me-3 text-warning"></i>
            <div>
                <h5 class="mb-1">Habilidades Técnicas</h5>
                <ul class="mb-0">
                    <li>PHP, CodeIgniter, Laravel</li>
                    <li>HTML, CSS, JavaScript, Bootstrap</li>
                    <li>MySQL, PostgreSQL, Git</li>
                    <li>Linux, Python para análise de dados</li>
                    <li>Infraestrutura de redes, Firewall pfSense</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Soft Skills -->
    <div class="col">
        <div class="d-flex align-items-start">
            <i class="bi bi-person-hearts fs-3 me-3 text-danger"></i>
            <div>
                <h5 class="mb-1">Soft Skills</h5>
                <ul class="mb-0">
                    <li>Trabalho em equipe</li>
                    <li>Comunicação clara</li>
                    <li>Proatividade</li>
                    <li>Comprometimento com prazos</li>
                    <li>Organização</li>
                    <li>Pensamento crítico</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Pesquisa atual -->
    <div class="col">
        <div class="d-flex align-items-start">
            <i class="bi bi-robot fs-3 me-3 text-info"></i>
            <div>
                <h5 class="mb-1">Linha de Pesquisa</h5>
                <p class="mb-0">
                    Pesquisa focada na aceitação de robôs quadrúpedes por produtores rurais, com aplicações em Agricultura 5.0 e Interação Humano-Robô.
                </p>
            </div>
        </div>
    </div>

    <!-- Download CV -->
    <!-- <div class="col">
        <div class="d-flex align-items-start">
            <i class="bi bi-file-earmark-arrow-down-fill fs-3 me-3 text-secondary"></i>
            <div>
                <h5 class="mb-1">Currículo em PDF</h5>
                <p class="mb-2">Baixe uma versão atualizada do currículo completo em PDF.</p>
                <a href="/curriculo/DaniloSilva_CV.pdf" class="btn btn-outline-dark btn-sm" target="_blank">
                    <i class="bi bi-download"></i> Baixar PDF
                </a>
            </div>
        </div>
    </div> -->

</div>