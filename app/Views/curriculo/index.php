<?php // app/Views/curriculo/index.php ?>

<h2><?php echo isset($contentTitle) ? htmlspecialchars($contentTitle) : 'Currículo'; ?></h2>
<hr class="mb-5">

<?php if (!empty($professionalSummary)): ?>
    <div class="lead mb-5">
        <?php echo nl2br(htmlspecialchars($professionalSummary)); ?>
    </div>
<?php endif; ?>


<!-- Seção de Experiência Profissional -->
<div class="cv-section mb-5">
    <h3><i class="bi bi-briefcase-fill me-2"></i>Experiência Profissional</h3>
    <div class="list-group list-group-flush">
        <?php if (!empty($experiences)): ?>
            <?php foreach ($experiences as $exp): ?>
                <div class="list-group-item px-0 py-3">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?php echo htmlspecialchars($exp['job_title']); ?></h5>
                        <small class="text-muted"><?php echo date('M Y', strtotime($exp['start_date'])); ?> - <?php echo $exp['end_date'] ? date('M Y', strtotime($exp['end_date'])) : 'Presente'; ?></small>
                    </div>
                    <p class="mb-1 fw-bold"><?php echo htmlspecialchars($exp['company']); ?></p>
                    <div class="small"><?php echo nl2br(htmlspecialchars($exp['description'])); ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhuma experiência profissional para exibir.</p>
        <?php endif; ?>
    </div>
</div>


<!-- Seção de Formação Acadêmica -->
<div class="cv-section mb-5">
    <h3><i class="bi bi-mortarboard-fill me-2"></i>Formação Acadêmica</h3>
    <div class="list-group list-group-flush">
        <?php if (!empty($educations)): ?>
            <?php foreach ($educations as $edu): ?>
                <div class="list-group-item px-0 py-3">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?php echo htmlspecialchars($edu['degree']); ?></h5>
                        <small class="text-muted"><?php echo $edu['start_year']; ?> - <?php echo $edu['end_year'] ?? 'Presente'; ?></small>
                    </div>
                    <p class="mb-1 fw-bold"><?php echo htmlspecialchars($edu['institution']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhuma formação acadêmica para exibir.</p>
        <?php endif; ?>
    </div>
</div>


<!-- Seção de Cursos e Certificações -->
<div class="cv-section">
    <h3><i class="bi bi-patch-check-fill me-2"></i>Cursos e Certificações</h3>
    <div class="list-group list-group-flush">
        <?php if (!empty($courses)): ?>
            <?php foreach ($courses as $course): ?>
                <div class="list-group-item px-0 py-3">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?php echo htmlspecialchars($course['course_name']); ?></h5>
                        <small class="text-muted"><?php echo $course['completion_year']; ?></small>
                    </div>
                    <p class="mb-1 fw-bold"><?php echo htmlspecialchars($course['institution']); ?></p>
                    <?php if (!empty($course['workload_hours'])): ?>
                        <small class="text-muted">Carga horária: <?php echo $course['workload_hours']; ?> horas</small>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum curso para exibir.</p>
        <?php endif; ?>
    </div>
</div>
