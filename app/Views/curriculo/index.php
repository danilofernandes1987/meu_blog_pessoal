<?php // app/Views/curriculo/index.php 
?>

<h2 class="mb-3"><?php echo isset($contentTitle) ? htmlspecialchars($contentTitle) : 'Currículo'; ?></h2>

<?php if (!empty($professionalSummary)): ?>
    <p class="lead mb-5"><?php echo nl2br(htmlspecialchars($professionalSummary)); ?></p>
<?php endif; ?>

<!-- Seção de Experiência Profissional -->
<div class="cv-section mb-5">
    <h3 class="mb-4"><i class="bi bi-briefcase-fill me-2 text-primary"></i>Experiência Profissional</h3>
    <ul class="timeline">
        <?php if (!empty($experiences)): ?>
            <?php foreach ($experiences as $exp): ?>
                <li class="timeline-item">
                    <div class="timeline-icon"><i class="bi bi-circle-fill"></i></div>
                    <p class="text-muted mb-1 text-mobile"><?php echo format_date_pt_br($exp['start_date']); ?> - <?php echo $exp['end_date'] ? format_date_pt_br($exp['end_date']) : 'Presente'; ?></p>
                    <h5 class="fw-bold mb-1 text-mobile"><?php echo htmlspecialchars($exp['job_title']); ?></h5>
                    <p class="text-primary mb-2 text-mobile"><?php echo htmlspecialchars($exp['company']); ?></p>
                    <div class="text-secondary text-mobile"><?php echo nl2br(htmlspecialchars($exp['description'])); ?></div>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhuma experiência profissional para exibir.</p>
        <?php endif; ?>
    </ul>
</div>

<!-- Seção de Formação Acadêmica -->
<div class="cv-section mb-5">
    <h3 class="mb-4"><i class="bi bi-mortarboard-fill me-2 text-success"></i>Formação Acadêmica</h3>
    <ul class="timeline">
        <?php if (!empty($educations)): ?>
            <?php foreach ($educations as $edu): ?>
                <li class="timeline-item">
                    <div class="timeline-icon"><i class="bi bi-circle-fill"></i></div>
                    <p class="text-muted mb-1  text-mobile"><?php echo $edu['start_year']; ?> - <?php echo $edu['end_year'] ?? 'Presente'; ?></p>
                    <h5 class="fw-bold mb-1 text-mobile"><?php echo htmlspecialchars($edu['degree']); ?></h5>
                    <p class="text-primary mb-2 text-mobile"><?php echo htmlspecialchars($edu['institution']); ?></p>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhuma formação acadêmica para exibir.</p>
        <?php endif; ?>
    </ul>
</div>

<!-- Seção de Cursos e Certificações -->
<div class="cv-section">
    <h3 class="mb-4"><i class="bi bi-patch-check-fill me-2 text-info"></i>Cursos e Certificações</h3>
    <ul class="timeline">
        <?php if (!empty($courses)): ?>
            <?php foreach ($courses as $course): ?>
                <li class="timeline-item">
                    <div class="timeline-icon"><i class="bi bi-circle-fill"></i></div>
                    <p class="text-muted mb-1 text-mobile"><?php echo $course['completion_year']; ?></p>
                    <h5 class="fw-bold mb-1 text-mobile"><?php echo htmlspecialchars($course['course_name']); ?></h5>
                    <p class="text-primary mb-2 text-mobile"><?php echo htmlspecialchars($course['course_institution']); ?></p>
                    <?php if (!empty($course['workload_hours'])): ?>
                        <small class="text-secondary d-block">Carga horária: <?php echo $course['workload_hours']; ?> horas</small>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum curso para exibir.</p>
        <?php endif; ?>
    </ul>
</div>