<?php // app/Views/admin/curriculo/index.php 
?>

<h2><?php echo htmlspecialchars($contentTitle ?? 'Gerenciar Currículo'); ?></h2>
<p class="text-muted">Nesta página você pode gerenciar as seções do seu currículo público.</p>
<hr>

<?php displayFlashMessage('curriculo_feedback'); ?>

<!-- Seção de Experiência Profissional -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="bi bi-briefcase-fill me-2"></i>Experiência Profissional</h5>
        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addExperienceModal">
            Adicionar Experiência
        </button>
    </div>
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Cargo</th>
                    <th>Empresa</th>
                    <th>Período</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($experiences)): ?>
                    <?php foreach ($experiences as $exp): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($exp['job_title']); ?></td>
                            <td><?php echo htmlspecialchars($exp['company']); ?></td>
                            <td><?php echo date('M Y', strtotime($exp['start_date'])); ?> - <?php echo $exp['end_date'] ? date('M Y', strtotime($exp['end_date'])) : 'Presente'; ?></td>
                            <td class="text-end">
                                <button type="button" class="btn btn-primary btn-sm edit-experience-btn"
                                    data-bs-toggle="modal" data-bs-target="#editExperienceModal"
                                    data-id="<?php echo $exp['id']; ?>"
                                    data-job-title="<?php echo htmlspecialchars($exp['job_title']); ?>"
                                    data-company="<?php echo htmlspecialchars($exp['company']); ?>"
                                    data-start-date="<?php echo $exp['start_date']; ?>"
                                    data-end-date="<?php echo $exp['end_date']; ?>"
                                    data-description="<?php echo htmlspecialchars($exp['description']); ?>">
                                    Editar
                                </button>
                                <a href="/admin/curriculo/deleteExperience/<?php echo $exp['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta experiência?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">Nenhuma experiência profissional cadastrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Seção de Formação Acadêmica -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="bi bi-mortarboard-fill me-2"></i>Formação Acadêmica</h5>
        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addEducationModal">
            Adicionar Formação
        </button>
    </div>
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Grau</th>
                    <th>Instituição</th>
                    <th>Período</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($educations)): ?>
                    <?php foreach ($educations as $edu): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($edu['degree']); ?></td>
                            <td><?php echo htmlspecialchars($edu['institution']); ?></td>
                            <td><?php echo $edu['start_year']; ?> - <?php echo $edu['end_year'] ?? 'Presente'; ?></td>
                            <td class="text-end">
                                <button type="button" class="btn btn-primary btn-sm edit-education-btn"
                                    data-bs-toggle="modal" data-bs-target="#editEducationModal"
                                    data-id="<?php echo $edu['id']; ?>"
                                    data-degree="<?php echo htmlspecialchars($edu['degree']); ?>"
                                    data-institution="<?php echo htmlspecialchars($edu['institution']); ?>"
                                    data-start-year="<?php echo $edu['start_year']; ?>"
                                    data-end-year="<?php echo $edu['end_year']; ?>">
                                    Editar
                                </button>
                                <a href="/admin/curriculo/deleteEducation/<?php echo $edu['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta formação?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">Nenhuma formação acadêmica cadastrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Seção de Cursos e Certificações -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="bi bi-patch-check-fill me-2"></i>Cursos e Certificações</h5>
        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addCourseModal">
            Adicionar Curso
        </button>
    </div>
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Curso</th>
                    <th>Instituição</th>
                    <th>Ano</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($courses)): ?>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                            <td><?php echo htmlspecialchars($course['course_institution']); ?></td>
                            <td><?php echo $course['completion_year']; ?></td>
                            <td class="text-end">
                                <button type="button" class="btn btn-primary btn-sm edit-course-btn"
                                    data-bs-toggle="modal" data-bs-target="#editCourseModal"
                                    data-id="<?php echo $course['id']; ?>"
                                    data-course-name="<?php echo htmlspecialchars($course['course_name']); ?>"
                                    data-course-institution="<?php echo htmlspecialchars($course['course_institution']); ?>"
                                    data-completion-year="<?php echo $course['completion_year']; ?>"
                                    data-workload-hours="<?php echo $course['workload_hours']; ?>">
                                    Editar
                                </button>
                                <a href="/admin/curriculo/deleteCourse/<?php echo $course['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este curso?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">Nenhum curso cadastrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<!-- Modal para Adicionar Experiência -->
<div class="modal fade" id="addExperienceModal" tabindex="-1" aria-labelledby="addExperienceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addExperienceModalLabel">Adicionar Nova Experiência Profissional</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="/admin/curriculo/storeExperience">
                <div class="modal-body">
                    <?php echo csrfInput(); ?>
                    <div class="mb-3">
                        <label for="job_title" class="form-label">Cargo</label>
                        <input type="text" class="form-control <?php echo isset($errors['job_title']) ? 'is-invalid' : ''; ?>" id="job_title" name="job_title" value="<?php echo htmlspecialchars($old_input['job_title'] ?? ''); ?>" required>
                        <?php if (isset($errors['job_title'])): ?>
                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['job_title']); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="company" class="form-label">Empresa / Instituição</label>
                        <input type="text" class="form-control <?php echo isset($errors['company']) ? 'is-invalid' : ''; ?>" id="company" name="company" value="<?php echo htmlspecialchars($old_input['company'] ?? ''); ?>" required>
                        <?php if (isset($errors['company'])): ?>
                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['company']); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Data de Início</label>
                            <input type="date" class="form-control <?php echo isset($errors['start_date']) ? 'is-invalid' : ''; ?>" id="start_date" name="start_date" value="<?php echo htmlspecialchars($old_input['start_date'] ?? ''); ?>" required>
                            <?php if (isset($errors['start_date'])): ?>
                                <div class="invalid-feedback"><?php echo htmlspecialchars($errors['start_date']); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">Data de Término</label>
                            <input type="date" class="form-control <?php echo isset($errors['end_date']) ? 'is-invalid' : ''; ?>" id="end_date" name="end_date" value="<?php echo htmlspecialchars($old_input['end_date'] ?? ''); ?>">
                            <?php if (isset($errors['end_date'])): ?>
                                <div class="invalid-feedback"><?php echo htmlspecialchars($errors['end_date']); ?></div>
                            <?php endif; ?>
                            <div class="form-text">Deixe em branco se for o emprego atual.</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição das Atividades</label>
                        <textarea class="form-control <?php echo isset($errors['description']) ? 'is-invalid' : ''; ?>" id="description" name="description" rows="5"><?php echo htmlspecialchars($old_input['description'] ?? ''); ?></textarea>
                        <?php if (isset($errors['description'])): ?>
                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['description']); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Experiência</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Adicionar Formação -->
<div class="modal fade" id="addEducationModal" tabindex="-1" aria-labelledby="addEducationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEducationModalLabel">Adicionar Nova Formação Acadêmica</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="/admin/curriculo/storeEducation">
                <div class="modal-body">
                    <?php echo csrfInput(); ?>
                    <div class="mb-3">
                        <label for="degree" class="form-label">Grau / Título</label>
                        <input type="text" class="form-control <?php echo isset($errors['degree']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($old_input['degree'] ?? ''); ?>" id="degree" name="degree" placeholder="Ex: Mestrado em Ciência da Computação" required>
                        <?php if (isset($errors['degree'])): ?>
                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['degree']); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="institution" class="form-label">Instituição</label>
                        <input type="text" class="form-control <?php echo isset($errors['institution']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($old_input['institution'] ?? ''); ?>" id="institution" name="institution" placeholder="Ex: Universidade Federal de Lavras (UFLA)" required>
                        <?php if (isset($errors['institution'])): ?>
                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['institution']); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_year" class="form-label">Ano de Início</label>
                            <input type="number" class="form-control <?php echo isset($errors['start_year']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($old_input['start_year'] ?? ''); ?>" id="start_year" name="start_year" placeholder="Ex: 2023" required>
                            <?php if (isset($errors['start_year'])): ?>
                                <div class="invalid-feedback"><?php echo htmlspecialchars($errors['start_year']); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_year" class="form-label">Ano de Término</label>
                            <input type="number" class="form-control <?php echo isset($errors['end_year']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($old_input['end_year'] ?? ''); ?>" id="end_year" name="end_year" placeholder="Ex: 2025">
                            <?php if (isset($errors['end_year'])): ?>
                                <div class="invalid-feedback"><?php echo htmlspecialchars($errors['end_year']); ?></div>
                            <?php endif; ?>
                            <div class="form-text">Deixe em branco se estiver em andamento.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Formação</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Adicionar Curso -->
<div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCourseModalLabel">Adicionar Novo Curso / Certificação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="/admin/curriculo/storeCourse">
                <div class="modal-body">
                    <?php echo csrfInput(); ?>
                    <div class="mb-3">
                        <label for="course_name" class="form-label">Nome do Curso</label>
                        <input type="text" class="form-control <?php echo isset($errors['course_name']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($old_input['course_name'] ?? ''); ?>" id="course_name" name="course_name" required>
                        <?php if (isset($errors['course_name'])): ?>
                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['course_name']); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="course_institution" class="form-label">Instituição</label>
                        <input type="text" class="form-control <?php echo isset($errors['course_institution']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($old_input['course_institution'] ?? ''); ?>" id="course_institution" name="course_institution" required>
                        <?php if (isset($errors['course_institution'])): ?>
                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['course_institution']); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="completion_year" class="form-label">Ano de Conclusão</label>
                            <input type="number" class="form-control <?php echo isset($errors['completion_year']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($old_input['completion_year'] ?? ''); ?>" id="completion_year" name="completion_year" placeholder="Ex: 2021" required>
                            <?php if (isset($errors['completion_year'])): ?>
                                <div class="invalid-feedback"><?php echo htmlspecialchars($errors['completion_year']); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="workload_hours" class="form-label">Carga Horária (opcional)</label>
                            <input type="number" class="form-control <?php echo isset($errors['workload_hours']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($old_input['workload_hours'] ?? ''); ?>" id="workload_hours" name="workload_hours" placeholder="Ex: 40">
                            <?php if (isset($errors['workload_hours'])): ?>
                                <div class="invalid-feedback"><?php echo htmlspecialchars($errors['workload_hours']); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Curso</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Editar Experiência (Com lógica de erro) -->
<div class="modal fade" id="editExperienceModal" tabindex="-1" aria-labelledby="editExperienceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editExperienceModalLabel">Editar Experiência Profissional</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editExperienceForm" method="POST" action="">
                <div class="modal-body">
                    <?php echo csrfInput(); ?>
                    <div class="mb-3">
                        <label for="edit_job_title" class="form-label">Cargo</label>
                        <input type="text" class="form-control <?php echo (isset($errors['job_title']) && $openModal === '#editExperienceModal') ? 'is-invalid' : ''; ?>" id="edit_job_title" name="job_title" required>
                        <?php if (isset($errors['job_title']) && $openModal === '#editExperienceModal'): ?><div class="invalid-feedback"><?php echo htmlspecialchars($errors['job_title']); ?></div><?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="edit_company" class="form-label">Empresa</label>
                        <input type="text" class="form-control <?php echo (isset($errors['company']) && $openModal === '#editExperienceModal') ? 'is-invalid' : ''; ?>" id="edit_company" name="company" required>
                        <?php if (isset($errors['company']) && $openModal === '#editExperienceModal'): ?><div class="invalid-feedback"><?php echo htmlspecialchars($errors['company']); ?></div><?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_start_date" class="form-label">Data de Início</label>
                            <input type="date" class="form-control <?php echo (isset($errors['start_date']) && $openModal === '#editExperienceModal') ? 'is-invalid' : ''; ?>" id="edit_start_date" name="start_date" required>
                            <?php if (isset($errors['start_date']) && $openModal === '#editExperienceModal'): ?><div class="invalid-feedback"><?php echo htmlspecialchars($errors['start_date']); ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_end_date" class="form-label">Data de Término</label>
                            <input type="date" class="form-control" id="edit_end_date" name="end_date">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Descrição</label>
                        <textarea class="form-control <?php echo (isset($errors['description']) && $openModal === '#editExperienceModal') ? 'is-invalid' : ''; ?>" id="edit_description" name="description" rows="5" required></textarea>
                        <?php if (isset($errors['description']) && $openModal === '#editExperienceModal'): ?><div class="invalid-feedback"><?php echo htmlspecialchars($errors['description']); ?></div><?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Editar Formação (ATUALIZADO) -->
<div class="modal fade" id="editEducationModal" tabindex="-1" aria-labelledby="editEducationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEducationModalLabel">Editar Formação Acadêmica</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editEducationForm" method="POST" action="">
                <div class="modal-body">
                    <?php echo csrfInput(); ?>
                    <div class="mb-3">
                        <label for="edit_degree" class="form-label">Grau / Título</label>
                        <input type="text" class="form-control <?php echo (isset($errors['degree']) && $openModal === '#editEducationModal') ? 'is-invalid' : ''; ?>" id="edit_degree" name="degree" required>
                        <?php if (isset($errors['degree']) && $openModal === '#editEducationModal'): ?><div class="invalid-feedback"><?php echo htmlspecialchars($errors['degree']); ?></div><?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="edit_institution" class="form-label">Instituição</label>
                        <input type="text" class="form-control <?php echo (isset($errors['institution']) && $openModal === '#editEducationModal') ? 'is-invalid' : ''; ?>" id="edit_institution" name="institution" required>
                        <?php if (isset($errors['institution']) && $openModal === '#editEducationModal'): ?><div class="invalid-feedback"><?php echo htmlspecialchars($errors['institution']); ?></div><?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_start_year" class="form-label">Ano de Início</label>
                            <input type="number" class="form-control <?php echo (isset($errors['start_year']) && $openModal === '#editEducationModal') ? 'is-invalid' : ''; ?>" id="edit_start_year" name="start_year" required>
                            <?php if (isset($errors['start_year']) && $openModal === '#editEducationModal'): ?><div class="invalid-feedback"><?php echo htmlspecialchars($errors['start_year']); ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_end_year" class="form-label">Ano de Término</label>
                            <input type="number" class="form-control" id="edit_end_year" name="end_year">
                            <div class="form-text">Deixe em branco se estiver em andamento.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Editar Curso (ATUALIZADO) -->
<div class="modal fade" id="editCourseModal" tabindex="-1" aria-labelledby="editCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCourseModalLabel">Editar Curso / Certificação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCourseForm" method="POST" action="">
                <div class="modal-body">
                    <?php echo csrfInput(); ?>
                    <div class="mb-3">
                        <label for="edit_course_name" class="form-label">Nome do Curso</label>
                        <input type="text" class="form-control <?php echo (isset($errors['course_name']) && $openModal === '#editCourseModal') ? 'is-invalid' : ''; ?>" id="edit_course_name" name="course_name" required>
                        <?php if (isset($errors['course_name']) && $openModal === '#editCourseModal'): ?><div class="invalid-feedback"><?php echo htmlspecialchars($errors['course_name']); ?></div><?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="edit_course_institution" class="form-label">Instituição</label>
                        <input type="text" class="form-control <?php echo (isset($errors['course_institution']) && $openModal === '#editCourseModal') ? 'is-invalid' : ''; ?>" id="edit_course_institution" name="course_institution" required>
                        <?php if (isset($errors['course_institution']) && $openModal === '#editCourseModal'): ?><div class="invalid-feedback"><?php echo htmlspecialchars($errors['course_institution']); ?></div><?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_completion_year" class="form-label">Ano de Conclusão</label>
                            <input type="number" class="form-control <?php echo (isset($errors['completion_year']) && $openModal === '#editCourseModal') ? 'is-invalid' : ''; ?>" id="edit_completion_year" name="completion_year" required>
                            <?php if (isset($errors['completion_year']) && $openModal === '#editCourseModal'): ?><div class="invalid-feedback"><?php echo htmlspecialchars($errors['completion_year']); ?></div><?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_workload_hours" class="form-label">Carga Horária (opcional)</label>
                            <input type="number" class="form-control" id="edit_workload_hours" name="workload_hours">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    
    function setupEditModal(modalId, formId, dataAttributes) {
        const modalElement = document.getElementById(modalId);
        if (modalElement) {
            modalElement.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                if (!button) return;
                
                const id = button.getAttribute('data-id');
                const form = modalElement.querySelector('#' + formId);
                
                let actionBase = '';
                if (modalId === 'editExperienceModal') actionBase = '/admin/curriculo/updateExperience/';
                if (modalId === 'editEducationModal') actionBase = '/admin/curriculo/updateEducation/';
                if (modalId === 'editCourseModal') actionBase = '/admin/curriculo/updateCourse/';
                form.action = actionBase + id;
                
                dataAttributes.forEach(attr => {
                    const input = form.querySelector(`#edit_${attr}`);
                    if (input) {
                       input.value = button.getAttribute(`data-${attr.replace(/_/g, '-')}`) || '';
                    }
                });
            });
        }
    }

    setupEditModal('editExperienceModal', 'editExperienceForm', ['job_title', 'company', 'start_date', 'end_date', 'description']);
    setupEditModal('editEducationModal', 'editEducationForm', ['degree', 'institution', 'start_year', 'end_year']);
    setupEditModal('editCourseModal', 'editCourseForm', ['course_name', 'course_institution', 'completion_year', 'workload_hours']);


    const modalToOpenId = <?php echo json_encode($openModal ?? null); ?>;
    if (modalToOpenId) {
        const modalElement = document.querySelector(modalToOpenId);
        if (modalElement) {
            const modalInstance = new bootstrap.Modal(modalElement);
            const form = modalElement.querySelector('form');
            const oldInput = <?php echo json_encode($old_input ?? []); ?>;

            if (modalToOpenId.startsWith('#edit')) {
                const editId = <?php echo json_encode($edit_id ?? null); ?>;
                if (form && oldInput && editId) {
                    let actionBase = '';
                    if(modalToOpenId === '#editExperienceModal') actionBase = '/admin/curriculo/updateExperience/';
                    if(modalToOpenId === '#editEducationModal') actionBase = '/admin/curriculo/updateEducation/';
                    if(modalToOpenId === '#editCourseModal') actionBase = '/admin/curriculo/updateCourse/';
                    form.action = actionBase + editId;
                }
            }

            if(form && oldInput) {
                for (const key in oldInput) {
                    const inputField = form.querySelector(`[name="${key}"]`);
                    if (inputField) {
                        // Para os modais de edição, os IDs têm prefixo 'edit_'
                        // mas os nomes dos campos são os mesmos. O seletor por nome funciona para ambos.
                        inputField.value = oldInput[key];
                    }
                }
            }
            
            modalInstance.show();
        }
    }
});
</script>