<?php
// app/Controllers/Admin/HomepageController.php
namespace App\Controllers\Admin;

use App\Core\BaseAdminController;
use App\Models\SiteSettingsModel;
use App\Models\SoftSkillModel;

class HomepageController extends BaseAdminController
{
    private $siteSettingsModel;
    private $softSkillModel;

    public function __construct()
    {
        parent::__construct();
        $this->siteSettingsModel = new SiteSettingsModel();
        $this->softSkillModel = new SoftSkillModel();
    }

    /**
     * Exibe o formulário de edição do conteúdo da página inicial.
     */
    public function index()
    {
        $data = [
            'pageTitle'         => 'Editar Página Inicial',
            'contentTitle'      => 'Configurações da Página Inicial',
            'introductionText'  => $this->siteSettingsModel->getSetting('homepage_introduction_text'),
            'publicProfilePhoto'  => $this->siteSettingsModel->getSetting('public_profile_photo'),
            'softSkills'        => $this->softSkillModel->findAllForAdmin(),
        ];

        $this->view('admin.homepage.index', $data, 'layouts.admin');
    }

    /**
     * Atualiza o texto de introdução da página inicial.
     */
    public function updateIntroduction()
    {
        $this->validatePostRequest(); // Valida CSRF e se é POST

        $introductionText = $_POST['introduction_text'] ?? '';

        // Usamos nosso model para salvar a configuração. A função já lida com INSERT ou UPDATE.
        if ($this->siteSettingsModel->updateSetting('homepage_introduction_text', $introductionText)) {
            setFlashMessage('homepage_feedback', 'Texto de apresentação atualizado com sucesso!', 'success');
        } else {
            setFlashMessage('homepage_feedback', 'Ocorreu um erro ao atualizar o texto.', 'danger');
        }

        header('Location: /admin/homepage');
        exit;
    }

     /**
     * Atualiza a foto de destaque da página inicial pública.
     */
    public function updatePublicPhoto()
    {
        $this->validatePostRequest(); // Valida CSRF e se é POST

        $currentPhoto = $this->siteSettingsModel->getSetting('public_profile_photo');
        
        $newPhotoName = $this->handleImageUpload($_FILES['public_photo'], $currentPhoto);

        if ($newPhotoName === false) {
            // Um erro de upload ocorreu e foi definido na sessão pelo handleImageUpload
            // A flash message será mais útil aqui.
            setFlashMessage('homepage_feedback', $_SESSION['errors']['photo'] ?? 'Erro no upload da foto.', 'danger');
        } elseif ($newPhotoName !== null) {
            // Se um novo arquivo foi enviado com sucesso, atualiza no banco
            if ($this->siteSettingsModel->updateSetting('public_profile_photo', $newPhotoName)) {
                setFlashMessage('homepage_feedback', 'Foto pública atualizada com sucesso!', 'success');
            } else {
                setFlashMessage('homepage_feedback', 'Erro ao salvar o nome da nova foto no banco de dados.', 'danger');
            }
        } else {
            // Nenhum arquivo novo foi enviado
            setFlashMessage('homepage_feedback', 'Nenhuma nova foto foi enviada.', 'info');
        }

        header('Location: /admin/homepage');
        exit;
    }

    /**
     * Armazena uma nova soft skill no banco de dados.
     */
    public function storeSkill()
    {
        $this->validatePostRequest(); // Valida CSRF e se é POST

        $data = [
            'icon_class' => $_POST['icon_class'] ?? '',
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];

        // Validação simples
        if (empty($data['icon_class']) || empty($data['title']) || empty($data['description'])) {
            setFlashMessage('homepage_feedback', 'Todos os campos são obrigatórios para adicionar uma nova skill.', 'danger');
        } else {
            if ($this->softSkillModel->create($data)) {
                setFlashMessage('homepage_feedback', 'Nova skill adicionada com sucesso!', 'success');
            } else {
                setFlashMessage('homepage_feedback', 'Ocorreu um erro ao adicionar a skill.', 'danger');
            }
        }

        header('Location: /admin/homepage');
        exit;
    }

    /**
     * Exclui uma soft skill.
     */
    public function deleteSkill(int $id)
    {
        // Adicionar uma verificação CSRF aqui para requisições GET é mais complexo.
        // A confirmação JS já é uma camada. Em um sistema de alta segurança,
        // a exclusão seria feita via POST/formulário.

        if ($this->softSkillModel->delete($id)) {
            setFlashMessage('homepage_feedback', 'Skill excluída com sucesso!', 'success');
        } else {
            setFlashMessage('homepage_feedback', 'Ocorreu um erro ao excluir a skill.', 'danger');
        }

        header('Location: /admin/homepage');
        exit;
    }

    /**
     * Atualiza uma soft skill existente.
     */
    public function updateSkill(int $id)
    {
        $this->validatePostRequest(); // Valida CSRF e se é POST

        $data = [
            'icon_class' => $_POST['icon_class'] ?? '',
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];

        // Validação simples
        if (empty($data['icon_class']) || empty($data['title']) || empty($data['description'])) {
            setFlashMessage('homepage_feedback', 'Todos os campos são obrigatórios para editar a skill.', 'danger');
        } else {
            if ($this->softSkillModel->update($id, $data)) {
                setFlashMessage('homepage_feedback', 'Skill atualizada com sucesso!', 'success');
            } else {
                setFlashMessage('homepage_feedback', 'Ocorreu um erro ao atualizar a skill.', 'danger');
            }
        }

        header('Location: /admin/homepage');
        exit;
    }

    /**
     * Move uma skill para cima ou para baixo na ordem de exibição.
     * @param int $id ID da skill a ser movida.
     * @param string $direction Direção do movimento ('up' ou 'down').
     */
    public function moveSkill(int $id, string $direction)
    {
        $skills = $this->softSkillModel->findAllForAdmin();
        $skillKeys = array_keys(array_column($skills, 'id'), $id);

        if (empty($skillKeys)) {
            setFlashMessage('homepage_feedback', 'Skill não encontrada.', 'danger');
            header('Location: /admin/homepage');
            exit;
        }

        $currentIndex = $skillKeys[0];
        $swapIndex = -1;

        if ($direction === 'up' && $currentIndex > 0) {
            $swapIndex = $currentIndex - 1;
        } elseif ($direction === 'down' && $currentIndex < (count($skills) - 1)) {
            $swapIndex = $currentIndex + 1;
        }

        if ($swapIndex !== -1) {
            $skillToSwapWith = $skills[$swapIndex];
            $this->softSkillModel->swapOrder($id, $skillToSwapWith['id']);
        }

        // Nenhuma mensagem de feedback é necessária, a mudança visual é o feedback.
        header('Location: /admin/homepage');
        exit;
    }
}
