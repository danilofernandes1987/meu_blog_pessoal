<?php
// app/Controllers/Admin/ProfileController.php
namespace App\Controllers\Admin;

use App\Core\BaseAdminController;
use App\Models\AdminUserModel;

class ProfileController extends BaseAdminController
{
    private AdminUserModel $adminUserModel;

    public function __construct()
    {
        parent::__construct(); // Isso já verifica se o usuário está logado
        $this->adminUserModel = new AdminUserModel();
    }

    /**
     * Exibe o formulário de edição de perfil.
     */
    public function index(): void
    {
        $userId = $_SESSION['admin_user_id'];
        $user = $this->adminUserModel->findById($userId);

        $data = [
            'pageTitle'    => 'Editar Meu Perfil',
            'contentTitle' => 'Meu Perfil',
            'user'         => $user,
            'errors'       => $_SESSION['errors'] ?? [],
            'old_input'    => $_SESSION['old_input'] ?? []
        ];
        unset($_SESSION['errors'], $_SESSION['old_input']);

        $this->view('admin.profile.edit', $data, 'layouts.admin');
    }

    /**
     * Processa a atualização do perfil.
     */
    public function update(): void
    {
        $this->validatePostRequest(); // Valida CSRF e se é POST

        $userId = $_SESSION['admin_user_id'];
        $currentUser = $this->adminUserModel->findById($userId);
        $errors = [];
        $dataToUpdate = [];

        // 1. Atualizar Nome
        $name = trim($_POST['name'] ?? '');
        if (empty($name)) {
            $errors['name'] = 'O nome é obrigatório.';
        } elseif ($name !== $currentUser['name']) {
            $dataToUpdate['name'] = $name;
        }

        // 2. Atualizar Foto
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
            $newPhoto = $this->handleImageUpload($_FILES['photo'], $currentUser['photo']);
            if ($newPhoto === false) {
                // Erros de upload são adicionados à sessão pelo handleImageUpload
                $errors = array_merge($errors, $_SESSION['errors'] ?? []);
            } else {
                $dataToUpdate['photo'] = $newPhoto;
            }
        }

        // 3. Atualizar Senha (apenas se os campos de senha forem preenchidos)
        $oldPassword = $_POST['old_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $newPasswordConfirm = $_POST['new_password_confirm'] ?? '';

        if (!empty($oldPassword) || !empty($newPassword) || !empty($newPasswordConfirm)) {
            // Busca o usuário novamente para pegar o hash da senha
            $userWithPassword = (new AdminUserModel())->findByIdWithPassword($userId); // Precisaremos criar este método

            if (!password_verify($oldPassword, $userWithPassword['password_hash'])) {
                $errors['old_password'] = 'A senha antiga está incorreta.';
            }
            if (strlen($newPassword) < 8) {
                $errors['new_password'] = 'A nova senha deve ter pelo menos 8 caracteres.';
            }
            if ($newPassword !== $newPasswordConfirm) {
                $errors['new_password_confirm'] = 'A confirmação da nova senha não corresponde.';
            }

            if (empty($errors['old_password']) && empty($errors['new_password']) && empty($errors['new_password_confirm'])) {
                $dataToUpdate['password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
            }
        }

        // Se houver erros, redireciona de volta com os erros
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            header('Location: /admin/profile');
            exit;
        }

        // Se não houver nada para atualizar, apenas redireciona com sucesso
        if (empty($dataToUpdate)) {
            setFlashMessage('profile_feedback', 'Nenhuma alteração para salvar.', 'info');
            header('Location: /admin/profile');
            exit;
        }

        // Se houver dados para atualizar, executa o update
        if ($this->adminUserModel->update($userId, $dataToUpdate)) {
            setFlashMessage('profile_feedback', 'Perfil atualizado com sucesso!', 'success');
            // Atualiza o nome na sessão, se foi alterado
            if (isset($dataToUpdate['name'])) {
                $_SESSION['admin_name'] = $dataToUpdate['name'];
            }
        } else {
            setFlashMessage('profile_feedback', 'Erro ao atualizar o perfil.', 'danger');
        }

        header('Location: /admin/profile');
        exit;
    }
}
