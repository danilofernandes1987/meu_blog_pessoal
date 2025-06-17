<?php // app/Views/admin/homepage/index.php 
?>

<h2><?php echo htmlspecialchars($contentTitle ?? 'Editar Página Inicial'); ?></h2>
<hr>

<?php displayFlashMessage('homepage_feedback'); ?>

<!-- Card para a Foto de Destaque Pública (NOVO) -->
<div class="card mb-4">
    <div class="card-header">
        Foto de Destaque (Página Inicial)
    </div>
    <div class="card-body">
        <form method="POST" action="/admin/homepage/updatePublicPhoto" enctype="multipart/form-data">
            <?php echo csrfInput(); ?>
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <p>Foto Atual:</p>
                    <?php $photoSrc = (!empty($publicProfilePhoto)) ? '/uploads/images/' . htmlspecialchars($publicProfilePhoto) : '/images/placeholder-profile.png'; ?>
                    <img src="<?php echo $photoSrc; ?>" alt="Foto de destaque pública atual" class="img-thumbnail rounded-circle mb-2" style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                <div class="col-md-9">
                    <div class="mb-3">
                        <label for="public_photo" class="form-label">Alterar Foto</label>
                        <input class="form-control" type="file" id="public_photo" name="public_photo">
                        <div class="form-text">Envie uma nova imagem para substituir a foto de destaque na sua página inicial.</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar Foto</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Formulário para editar o texto de apresentação -->
<div class="card mb-4">
    <div class="card-header">
        Texto de Apresentação
    </div>
    <div class="card-body">
        <form method="POST" action="/admin/homepage/updateIntroduction">
            <?php echo csrfInput(); ?>
            <div class="mb-3">
                <label for="introduction_text" class="form-label">Edite o texto de introdução que aparece na sua página inicial.</label>
                <textarea class="form-control" id="content" name="introduction_text" rows="10"><?php echo htmlspecialchars($introductionText ?? ''); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Salvar Texto</button>
        </form>
    </div>
</div>


<!-- Seção para gerenciar as Soft Skills -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        Gerenciar Soft Skills
        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addSkillModal">
            Adicionar Nova Skill
        </button>
    </div>
    <div class="card-body">
        <?php if (!empty($softSkills)): ?>
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th style="width: 10%;">Ordem</th>
                        <th>Ícone</th>
                        <th>Título</th>
                        <th>Descrição</th>
                        <th style="width: 20%;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($softSkills as $index => $skill): ?>
                        <tr>
                            <td>
                                <!-- Botão para Subir -->
                                <a href="/admin/homepage/moveSkill/<?php echo $skill['id']; ?>/up"
                                    class="btn btn-outline-secondary btn-sm <?php echo ($index === 0) ? 'disabled' : ''; ?>"
                                    title="Mover para Cima">
                                    <i class="bi bi-arrow-up"></i>
                                </a>
                                <!-- Botão para Descer -->
                                <a href="/admin/homepage/moveSkill/<?php echo $skill['id']; ?>/down"
                                    class="btn btn-outline-secondary btn-sm <?php echo ($index === count($softSkills) - 1) ? 'disabled' : ''; ?>"
                                    title="Mover para Baixo">
                                    <i class="bi bi-arrow-down"></i>
                                </a>
                            </td>
                            <td><i class="<?php echo htmlspecialchars($skill['icon_class']); ?> fs-4"></i></td>
                            <td><?php echo htmlspecialchars($skill['title']); ?></td>
                            <td><?php echo htmlspecialchars($skill['description']); ?></td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm"
                                    data-bs-toggle="modal" data-bs-target="#editSkillModal"
                                    data-id="<?php echo $skill['id']; ?>"
                                    data-icon="<?php echo htmlspecialchars($skill['icon_class']); ?>"
                                    data-title="<?php echo htmlspecialchars($skill['title']); ?>"
                                    data-description="<?php echo htmlspecialchars($skill['description']); ?>">
                                    Editar
                                </button>
                                <a href="/admin/homepage/deleteSkill/<?php echo $skill['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta skill?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">Nenhuma "soft skill" encontrada.</div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal para Adicionar Nova Skill -->
<div class="modal fade" id="addSkillModal" tabindex="-1" aria-labelledby="addSkillModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addSkillModalLabel">Adicionar Nova Soft Skill</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="/admin/homepage/storeSkill">
        <div class="modal-body">
            <?php echo csrfInput(); ?>
            <div class="mb-3">
                <label for="icon_class" class="form-label">Classe do Ícone Bootstrap</label>
                <input type="text" class="form-control" id="icon_class" name="icon_class" placeholder="ex: bi bi-people-fill text-primary" required>
                <div class="form-text">Encontre ícones em <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a>.</div>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Título</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Descrição</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Salvar Skill</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal para Editar Skill (NOVO) -->
<div class="modal fade" id="editSkillModal" tabindex="-1" aria-labelledby="editSkillModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSkillModalLabel">Editar Soft Skill</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSkillForm" method="POST" action=""> <!-- A action será definida via JS -->
                <div class="modal-body">
                    <?php echo csrfInput(); ?>
                    <input type="hidden" id="edit_skill_id" name="id"> <!-- Campo escondido para o ID -->

                    <div class="mb-3">
                        <label for="edit_icon_class" class="form-label">Classe do Ícone</label>
                        <input type="text" class="form-control" id="edit_icon_class" name="icon_class" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Título</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Descrição</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
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

<!-- Adicionamos um script no final da view -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var editSkillModal = document.getElementById('editSkillModal');
        editSkillModal.addEventListener('show.bs.modal', function(event) {
            // Botão que acionou o modal
            var button = event.relatedTarget;

            // Extrai as informações dos atributos data-*
            var id = button.getAttribute('data-id');
            var icon = button.getAttribute('data-icon');
            var title = button.getAttribute('data-title');
            var description = button.getAttribute('data-description');

            // Atualiza a action do formulário
            var form = editSkillModal.querySelector('#editSkillForm');
            form.action = '/admin/homepage/updateSkill/' + id;

            // Atualiza os valores dos campos do modal
            var modalIconInput = editSkillModal.querySelector('#edit_icon_class');
            var modalTitleInput = editSkillModal.querySelector('#edit_title');
            var modalDescriptionInput = editSkillModal.querySelector('#edit_description');

            modalIconInput.value = icon;
            modalTitleInput.value = title;
            modalDescriptionInput.value = description;
        });
    });
</script>