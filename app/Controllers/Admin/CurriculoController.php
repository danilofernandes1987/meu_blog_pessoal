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
            'openModal'    => $_SESSION['open_modal'] ?? null
        ];
        unset($_SESSION['errors'], $_SESSION['old_input'], $_SESSION['open_modal']);

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

        $data = [
            'job_title'   => $_POST['job_title'] ?? '',
            'company'     => $_POST['company'] ?? '',
            'start_date'  => $_POST['start_date'] ?? '',
            'end_date'    => $_POST['end_date'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];


        if ($this->experienceModel->create($data)) {
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
            'end_date' => 'required',
            'description' => 'required|min:30',
        ]);

        if ($validator->fails()) {
            $_SESSION['errors'] = $validator->errors();
            $_SESSION['old_input'] = $_POST;
            header('Location: /admin/curriculo');
            exit;
        }
        // --- FIM DA VALIDAÇÃO ---

        $data = [
            'job_title'   => $_POST['job_title'] ?? '',
            'company'     => $_POST['company'] ?? '',
            'start_date'  => $_POST['start_date'] ?? '',
            'end_date'    => $_POST['end_date'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];


        if ($this->experienceModel->update($id, $data)) {
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
            'degree'   => 'required|min:3|number',
            'institution'    => 'required|min:3',
            'start_year' => 'required',
            'end_year' => 'required',
        ]);

        if ($validator->fails()) {
            $_SESSION['errors'] = $validator->errors();
            $_SESSION['old_input'] = $_POST;
            header('Location: /admin/curriculo');
            exit;
        }
        // --- FIM DA VALIDAÇÃO ---

        $data = [
            'degree'      => $_POST['degree'] ?? '',
            'institution' => $_POST['institution'] ?? '',
            'start_year'  => $_POST['start_year'] ?? '',
            'end_year'    => $_POST['end_year'] ?? '',
        ];

        if ($this->educationModel->create($data)) {
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
        $data = [
            'degree'      => $_POST['degree'] ?? '',
            'institution' => $_POST['institution'] ?? '',
            'start_year'  => $_POST['start_year'] ?? '',
            'end_year'    => $_POST['end_year'] ?? '',
        ];

        if (empty($data['degree']) || empty($data['institution']) || empty($data['start_year'])) {
            setFlashMessage('curriculo_feedback', 'Grau, Instituição e Ano de Início são obrigatórios.', 'danger');
        } else {
            if ($this->educationModel->update($id, $data)) {
                setFlashMessage('curriculo_feedback', 'Formação atualizada com sucesso!', 'success');
            } else {
                setFlashMessage('curriculo_feedback', 'Ocorreu um erro ao atualizar a formação.', 'danger');
            }
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

        $data = [
            'course_name'     => $_POST['course_name'] ?? '',
            'institution'     => $_POST['institution'] ?? '',
            'completion_year' => $_POST['completion_year'] ?? '',
            'workload_hours'  => $_POST['workload_hours'] ?? '',
        ];

        if (empty($data['course_name']) || empty($data['institution']) || empty($data['completion_year'])) {
            setFlashMessage('curriculo_feedback', 'Nome do Curso, Instituição e Ano de Conclusão são obrigatórios.', 'danger');
        } else {
            if ($this->courseModel->create($data)) {
                setFlashMessage('curriculo_feedback', 'Novo curso adicionado com sucesso!', 'success');
            } else {
                setFlashMessage('curriculo_feedback', 'Ocorreu um erro ao adicionar o curso.', 'danger');
            }
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
        $data = [
            'course_name'     => $_POST['course_name'] ?? '',
            'institution'     => $_POST['institution'] ?? '',
            'completion_year' => $_POST['completion_year'] ?? '',
            'workload_hours'  => $_POST['workload_hours'] ?? '',
        ];

        if (empty($data['course_name']) || empty($data['institution']) || empty($data['completion_year'])) {
            setFlashMessage('curriculo_feedback', 'Nome do Curso, Instituição e Ano de Conclusão são obrigatórios.', 'danger');
        } else {
            if ($this->courseModel->update($id, $data)) {
                setFlashMessage('curriculo_feedback', 'Curso atualizado com sucesso!', 'success');
            } else {
                setFlashMessage('curriculo_feedback', 'Ocorreu um erro ao atualizar o curso.', 'danger');
            }
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
