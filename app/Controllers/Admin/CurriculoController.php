<?php
// app/Controllers/Admin/CurriculoController.php
namespace App\Controllers\Admin;

use App\Core\BaseAdminController;
use App\Models\WorkExperienceModel;
use App\Models\EducationModel;
use App\Models\CourseModel;
use App\Core\Validator;

class CurriculoController extends BaseAdminController
{
    /** @var WorkExperienceModel */
    private $experienceModel;

    /** @var EducationModel */
    private $educationModel;

    /** @var CourseModel */
    private $courseModel;

    public function __construct()
    {
        parent::__construct();
        $this->experienceModel = new WorkExperienceModel();
        $this->educationModel = new EducationModel();
        $this->courseModel = new CourseModel();
    }

    public function index()
    {
        $data = [
            'pageTitle'    => 'Gerenciar Currículo',
            'contentTitle' => 'Gerenciamento do Currículo',
            'experiences'  => $this->experienceModel->findAll(),
            'educations'   => $this->educationModel->findAll(),
            'courses'      => $this->courseModel->findAll(),
            'errors'       => $_SESSION['errors'] ?? [],
            'old_input'    => $_SESSION['old_input'] ?? [],
            'openModal'    => $_SESSION['open_modal'] ?? null,
            'edit_id'      => $_SESSION['edit_id'] ?? null,
        ];
        unset($_SESSION['errors'], $_SESSION['old_input'], $_SESSION['open_modal'], $_SESSION['edit_id']);

        $this->view('admin.curriculo.index', $data, 'layouts.admin');
    }

    /**
     * Salva uma nova experiência profissional.
     */
    public function storeExperience()
    {
        $this->validatePostRequest();

        // --- VALIDAÇÃO CENTRALIZADA ---
        $validator = new Validator($_POST);
        $validator->validate([
            'job_title'   => 'required|min:3',
            'company'    => 'required|min:3',
            'content' => 'required',
            'start_date' => 'required',
            'description' => 'required|min:30',
        ]);

        if ($validator->fails()) {
            setFlashMessage('curriculo_feedback', 'Por favor, corrija os erros no formulário.', 'danger');
            $_SESSION['errors'] = $validator->errors();
            $_SESSION['old_input'] = $_POST;
            $_SESSION['open_modal'] = '#addExperienceModal';
            header('Location: /admin/curriculo');
            exit;
        }
        // --- FIM DA VALIDAÇÃO ---

        if ($this->experienceModel->create($_POST)) {
            setFlashMessage('curriculo_feedback', 'Nova experiência profissional adicionada com sucesso!', 'success');
        } else {
            setFlashMessage('curriculo_feedback', 'Ocorreu um erro ao adicionar a experiência.', 'danger');
        }

        header('Location: /admin/curriculo');
        exit;
    }

    /**
     * Atualiza uma experiência profissional.
     */
    public function updateExperience(int $id)
    {
        $this->validatePostRequest();

        // --- VALIDAÇÃO CENTRALIZADA ---
        $validator = new Validator($_POST);
        $validator->validate([
            'job_title'   => 'required|min:3',
            'company'    => 'required|min:3',
            'content' => 'required',
            'start_date' => 'required',
            'description' => 'required|min:30',
        ]);

        if ($validator->fails()) {
            $_SESSION['errors'] = $validator->errors();
            $_SESSION['old_input'] = $_POST;
            $_SESSION['open_modal'] = '#editExperienceModal';
            $_SESSION['edit_id'] = $id;
            header('Location: /admin/curriculo');
            exit;
        }


        if ($this->experienceModel->update($id, $_POST)) {
            setFlashMessage('curriculo_feedback', 'Experiência atualizada com sucesso!', 'success');
        } else {
            setFlashMessage('curriculo_feedback', 'Ocorreu um erro ao atualizar a experiência.', 'danger');
        }

        header('Location: /admin/curriculo');
        exit;
    }

    /**
     * Exclui uma experiência profissional.
     */
    public function deleteExperience(int $id)
    {
        if ($this->experienceModel->delete($id)) {
            setFlashMessage('curriculo_feedback', 'Experiência excluída com sucesso!', 'success');
        } else {
            setFlashMessage('curriculo_feedback', 'Ocorreu um erro ao excluir a experiência.', 'danger');
        }

        header('Location: /admin/curriculo');
        exit;
    }

    /**
     * Salva uma nova formação acadêmica.
     */
    public function storeEducation()
    {
        $this->validatePostRequest();

        // --- VALIDAÇÃO CENTRALIZADA ---
        $validator = new Validator($_POST);
        $validator->validate([
            'degree'   => 'required|min:4',
            'institution'    => 'required|min:3',
            'start_year' => 'required|number',
            'end_year' => 'number',
        ]);

        if ($validator->fails()) {
            $_SESSION['errors'] = $validator->errors();
            $_SESSION['old_input'] = $_POST;
            $_SESSION['open_modal'] = '#addEducationModal';
            header('Location: /admin/curriculo');
            exit;
        }
        // --- FIM DA VALIDAÇÃO ---


        if ($this->educationModel->create($_POST)) {
            setFlashMessage('curriculo_feedback', 'Nova formação adicionada com sucesso!', 'success');
        } else {
            setFlashMessage('curriculo_feedback', 'Ocorreu um erro ao adicionar a formação.', 'danger');
        }

        header('Location: /admin/curriculo');
        exit;
    }

    /**
     * Atualiza formação acadêmica.
     */
    public function updateEducation(int $id)
    {
        $this->validatePostRequest();

        // --- VALIDAÇÃO CENTRALIZADA ---
        $validator = new Validator($_POST);
        $validator->validate([
            'degree' => 'required|min:4',
            'institution' => 'required|min:3',
            'start_year' => 'required|number',
            'end_year' => 'number',
        ]);

        if ($validator->fails()) {
            $_SESSION['errors'] = $validator->errors();
            $_SESSION['old_input'] = $_POST;
            $_SESSION['open_modal'] = '#editEducationModal';
            $_SESSION['edit_id'] = $id;
            header('Location: /admin/curriculo');
            exit;
        }

        if ($this->educationModel->update($id, $_POST)) {
            setFlashMessage('curriculo_feedback', 'Formação atualizada com sucesso!', 'success');
        } else {
            setFlashMessage('curriculo_feedback', 'Ocorreu um erro ao atualizar a formação.', 'danger');
        }
        header('Location: /admin/curriculo');
        exit;
    }

    /**
     * Exclui formação acadêmica.
     */
    public function deleteEducation(int $id)
    {
        if ($this->educationModel->delete($id)) {
            setFlashMessage('curriculo_feedback', 'Formação excluída com sucesso!', 'success');
        } else {
            setFlashMessage('curriculo_feedback', 'Ocorreu um erro ao excluir a formação.', 'danger');
        }
        header('Location: /admin/curriculo');
        exit;
    }

    /**
     * Salva um novo curso.
     */
    public function storeCourse()
    {
        $this->validatePostRequest();

        // --- VALIDAÇÃO CENTRALIZADA ---
        $validator = new Validator($_POST);
        $validator->validate([
            'course_name' => 'required|min:4',
            'course_institution' => 'required|min:3',
            'completion_year' => 'required|number',
        ]);

        if ($validator->fails()) {
            $_SESSION['errors'] = $validator->errors();
            $_SESSION['old_input'] = $_POST;
            $_SESSION['open_modal'] = '#addCourseModal';
            header('Location: /admin/curriculo');
            exit;
        }
        // --- FIM DA VALIDAÇÃO ---


        if ($this->courseModel->create($_POST)) {
            setFlashMessage('curriculo_feedback', 'Novo curso adicionado com sucesso!', 'success');
        } else {
            setFlashMessage('curriculo_feedback', 'Ocorreu um erro ao adicionar o curso.', 'danger');
        }

        header('Location: /admin/curriculo');
        exit;
    }

    /**
     * Atualiza um curso.
     */
    public function updateCourse(int $id)
    {
        $this->validatePostRequest();

        // --- VALIDAÇÃO CENTRALIZADA ---
        $validator = new Validator($_POST);
        $validator->validate([
            'course_name' => 'required|min:4',
            'course_institution' => 'required|min:3',
            'completion_year' => 'required|number',
        ]);

        if ($validator->fails()) {
            $_SESSION['errors'] = $validator->errors();
            $_SESSION['old_input'] = $_POST;
            $_SESSION['open_modal'] = '#editCourseModal';
            $_SESSION['edit_id'] = $id;
            header('Location: /admin/curriculo');
            exit;
        }

        if ($this->courseModel->update($id, $_POST)) {
            setFlashMessage('curriculo_feedback', 'Curso atualizado com sucesso!', 'success');
        } else {
            setFlashMessage('curriculo_feedback', 'Ocorreu um erro ao atualizar o curso.', 'danger');
        }
        header('Location: /admin/curriculo');
        exit;
    }


    /**
     * Exclui um curso.
     */
    public function deleteCourse(int $id)
    {
        if ($this->courseModel->delete($id)) {
            setFlashMessage('curriculo_feedback', 'Curso excluído com sucesso!', 'success');
        } else {
            setFlashMessage('curriculo_feedback', 'Ocorreu um erro ao excluir o curso.', 'danger');
        }
        header('Location: /admin/curriculo');
        exit;
    }
}
