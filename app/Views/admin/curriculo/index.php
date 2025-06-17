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
                            <td><?php echo htmlspecialchars($course['institution']); ?></td>
                            <td><?php echo $course['completion_year']; ?></td>
                            <td class="text-end">
                                <button type="button" class="btn btn-primary btn-sm edit-course-btn"
                                    data-bs-toggle="modal" data-bs-target="#editCourseModal"
                                    data-id="<?php echo $course['id']; ?>"
                                    data-course-name="<?php echo htmlspecialchars($course['course_name']); ?>"
                                    data-institution="<?php echo htmlspecialchars($course['institution']); ?>"
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
                        <input type="text" class="form-control" id="job_title" name="job_title" required>
                    </div>
                    <div class="mb-3">
                        <label for="company" class="form-label">Empresa / Instituição</label>
                        <input type="text" class="form-control" id="company" name="company" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Data de Início</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">Data de Término</label>
                            <input type="date" class="form-control" id="end_date" name="end_date">
                            <div class="form-text">Deixe em branco se for o emprego atual.</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição das Atividades</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
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
                        <input type="text" class="form-control" id="degree" name="degree" placeholder="Ex: Mestrado em Ciência da Computação" required>
                    </div>
                    <div class="mb-3">
                        <label for="institution" class="form-label">Instituição</label>
                        <input type="text" class="form-control" id="institution" name="institution" placeholder="Ex: Universidade Federal de Lavras (UFLA)" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_year" class="form-label">Ano de Início</label>
                            <input type="number" class="form-control" id="start_year" name="start_year" placeholder="Ex: 2023" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_year" class="form-label">Ano de Término</label>
                            <input type="number" class="form-control" id="end_year" name="end_year" placeholder="Ex: 2025">
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
                        <input type="text" class="form-control" id="course_name" name="course_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="course_institution" class="form-label">Instituição</label>
                        <input type="text" class="form-control" id="course_institution" name="institution" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="completion_year" class="form-label">Ano de Conclusão</label>
                            <input type="number" class="form-control" id="completion_year" name="completion_year" placeholder="Ex: 2021" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="workload_hours" class="form-label">Carga Horária (opcional)</label>
                            <input type="number" class="form-control" id="workload_hours" name="workload_hours" placeholder="Ex: 40">
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

<!-- Modal para Editar Experiência -->
<div class="modal fade" id="editExperienceModal" tabindex="-1" aria-labelledby="editExperienceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editExperienceModalLabel">Editar Experiência Profissional</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editExperienceForm" method="POST" action=""> <!-- Action será definida via JS -->
                <div class="modal-body">
                    <?php echo csrfInput(); ?>
                    <div class="mb-3">
                        <label for="edit_job_title" class="form-label">Cargo</label>
                        <input type="text" class="form-control" id="edit_job_title" name="job_title" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_company" class="form-label">Empresa / Instituição</label>
                        <input type="text" class="form-control" id="edit_company" name="company" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_start_date" class="form-label">Data de Início</label>
                            <input type="date" class="form-control" id="edit_start_date" name="start_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_end_date" class="form-label">Data de Término</label>
                            <input type="date" class="form-control" id="edit_end_date" name="end_date">
                            <div class="form-text">Deixe em branco se for o emprego atual.</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Descrição das Atividades</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="5" required></textarea>
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

<!-- Modal para Editar Formação -->
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
                        <input type="text" class="form-control" id="edit_degree" name="degree" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_institution" class="form-label">Instituição</label>
                        <input type="text" class="form-control" id="edit_institution" name="institution" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_start_year" class="form-label">Ano de Início</label>
                            <input type="number" class="form-control" id="edit_start_year" name="start_year" required>
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

<!-- Modal para Editar Curso -->
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
                        <input type="text" class="form-control" id="edit_course_name" name="course_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_course_institution" class="form-label">Instituição</label>
                        <input type="text" class="form-control" id="edit_course_institution" name="institution" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_completion_year" class="form-label">Ano de Conclusão</label>
                            <input type="number" class="form-control" id="edit_completion_year" name="completion_year" required>
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


<!-- Script para Modais de Edição -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // JS para Modal de Edição de Experiência
        const editExperienceModal = document.getElementById('editExperienceModal');
        if (editExperienceModal) {
            editExperienceModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const jobTitle = button.getAttribute('data-job-title');
                const company = button.getAttribute('data-company');
                const startDate = button.getAttribute('data-start-date');
                const endDate = button.getAttribute('data-end-date');
                const description = button.getAttribute('data-description');

                const form = editExperienceModal.querySelector('#editExperienceForm');
                form.action = '/admin/curriculo/updateExperience/' + id;

                form.querySelector('#edit_job_title').value = jobTitle;
                form.querySelector('#edit_company').value = company;
                form.querySelector('#edit_start_date').value = startDate;
                form.querySelector('#edit_end_date').value = endDate;
                form.querySelector('#edit_description').value = description;
            });
        }

        // JS para Modal de Edição de Formação
        const editEducationModal = document.getElementById('editEducationModal');
        if (editEducationModal) {
            editEducationModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const degree = button.getAttribute('data-degree');
                const institution = button.getAttribute('data-institution');
                const startYear = button.getAttribute('data-start-year');
                const endYear = button.getAttribute('data-end-year');

                const form = editEducationModal.querySelector('#editEducationForm');
                form.action = '/admin/curriculo/updateEducation/' + id;

                form.querySelector('#edit_degree').value = degree;
                form.querySelector('#edit_institution').value = institution;
                form.querySelector('#edit_start_year').value = startYear;
                form.querySelector('#edit_end_year').value = endYear;
            });
        }

        // JS para Modal de Edição de Curso
        const editCourseModal = document.getElementById('editCourseModal');
        if (editCourseModal) {
            editCourseModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const courseName = button.getAttribute('data-course-name');
                const institution = button.getAttribute('data-institution');
                const completionYear = button.getAttribute('data-completion-year');
                const workloadHours = button.getAttribute('data-workload-hours');

                const form = editCourseModal.querySelector('#editCourseForm');
                form.action = '/admin/curriculo/updateCourse/' + id;

                form.querySelector('#edit_course_name').value = courseName;
                form.querySelector('#edit_course_institution').value = institution;
                form.querySelector('#edit_completion_year').value = completionYear;
                form.querySelector('#edit_workload_hours').value = workloadHours;
            });
        }
    });
</script>