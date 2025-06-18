<?php
// app/Controllers/Admin/PostsController.php
namespace App\Controllers\Admin;

use App\Core\BaseAdminController;
use App\Models\PostModel;
use App\Controllers\ErrorController;
use App\Core\Validator;

class PostsController extends BaseAdminController
{
    private PostModel $postModel;

    public function __construct()
    {
        parent::__construct();
        $this->postModel = new PostModel();
    }

    /**
     * Lista todos os posts na área administrativa.
     */
    public function index(): void
    {
        $posts = $this->postModel->getAllForAdmin();

        $data = [
            'pageTitle'     => 'Gerenciar Posts',
            'contentTitle'  => 'Todos os Posts do Blog',
            'posts'         => $posts,
            'siteName'      => $this->siteConfig['siteName'] ?? 'Painel Admin',
            'adminUsername' => $_SESSION['admin_username'] ?? ''
        ];

        $this->view('admin.posts.index', $data, 'layouts.admin');
    }

    /**
     * Exibe o formulário para criar um novo post.
     */
    public function create(): void
    {
        $data = [
            'pageTitle'     => 'Criar Novo Post',
            'contentTitle'  => 'Adicionar Novo Post ao Blog',
            'siteName'      => $this->siteConfig['siteName'] ?? 'Painel Admin',
            'adminUsername' => $_SESSION['admin_username'] ?? '',
            'errors'        => $_SESSION['errors'] ?? [],
            'old_input'     => $_SESSION['old_input'] ?? []
        ];
        unset($_SESSION['errors']);
        unset($_SESSION['old_input']);

        $this->view('admin.posts.create', $data, 'layouts.admin');
    }

    /**
     * Armazena um novo post no banco de dados.
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validatePostRequest();

            // --- LÓGICA DE VALIDAÇÃO CENTRALIZADA ---
            $validator = new Validator($_POST);
            $validator->validate([
                'title'   => 'required|min:3',
                'slug'    => 'required|slug',
                'content' => 'required'
            ]);

            if ($validator->fails()) {
                $_SESSION['errors'] = $validator->errors();
                $_SESSION['old_input'] = $_POST;
                header('Location: /admin/posts/create');
                exit;
            }
            // --- FIM DA VALIDAÇÃO ---

            $newImageName = $this->handleImageUpload($_FILES['featured_image'] ?? null);
            if ($newImageName === false) {
                setFlashMessage('post_feedback', $_SESSION['errors']['photo'] ?? 'Erro no upload da foto.', 'danger');
                $_SESSION['old_input'] = $_POST;
                header('Location: /admin/posts/create');
                exit;
            }
    
            $status = $_POST['status'] ?? 'draft';
    
            $dataToSave = [
                'title'          => $_POST['title'],
                'slug'           => $_POST['slug'],
                'content'        => $_POST['content'],
                'status'         => $status,
                'featured_image' => $newImageName,
                'published_at'   => ($status === 'published') ? date('Y-m-d H:i:s') : null,
                'author_id'      => $_SESSION['admin_user_id'] ?? null
            ];
    
            if ($this->postModel->create($dataToSave)) {
                setFlashMessage('post_feedback', 'Post criado com sucesso!', 'success');
            } else {
                setFlashMessage('post_feedback', 'Erro ao criar o post. Verifique se o slug já existe.', 'danger');
                $_SESSION['old_input'] = $_POST; // Guarda o input para repopular
                header('Location: /admin/posts/create');
                exit;
            }
    
            header('Location: /admin/posts');
            exit;
        }

        header('Location: /admin/posts/create');
        exit;
    }

    /**
     * Exibe o formulário para editar um post existente.
     */
    public function edit(int $id): void
    {
        $post = $this->postModel->findById($id);

        if (!$post) {
            (new ErrorController())->show404("Post com ID '$id' não encontrado para edição.");
            return;
        }

        $data = [
            'pageTitle'     => 'Editar Post',
            'contentTitle'  => 'Editando Post: ' . htmlspecialchars($post['title']),
            'post'          => $post,
            'siteName'      => $this->siteConfig['siteName'] ?? 'Painel Admin',
            'adminUsername' => $_SESSION['admin_username'] ?? '',
            'errors'        => $_SESSION['errors'] ?? [],
            'old_input'     => $_SESSION['old_input'] ?? []
        ];
        unset($_SESSION['errors'], $_SESSION['old_input']);

        $this->view('admin.posts.edit', $data, 'layouts.admin');
    }

    /**
     * Atualiza um post existente no banco de dados.
     */
    public function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validatePostRequest();

            $postOriginal = $this->postModel->findById($id);
            if (!$postOriginal) {
                (new ErrorController())->show404("Post com ID '$id' não encontrado para atualização.");
                return;
            }

            $newImageName = $this->handleImageUpload($_FILES['featured_image'] ?? null, $postOriginal['featured_image']);
            if ($newImageName === false) {
                $_SESSION['old_input'] = $_POST;
                header('Location: /admin/posts/edit/' . $id);
                exit;
            }

            $errors = [];
            $title = trim($_POST['title'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $status = $_POST['status'] ?? 'draft';

            if (empty($title)) $errors['title'] = 'O título é obrigatório.';
            if (empty($slug)) $errors['slug'] = 'O slug é obrigatório.';
            elseif (!preg_match('/^[a-z0-9-]+$/', $slug)) $errors['slug'] = 'O slug deve conter apenas letras minúsculas, números e hífens.';
            if (empty($content)) $errors['content'] = 'O conteúdo é obrigatório.';
            if (!in_array($status, ['published', 'draft'])) $status = 'draft';

            if (!empty($errors)) {
                $_SESSION['errors'] = array_merge($_SESSION['errors'] ?? [], $errors);
                $_SESSION['old_input'] = $_POST;
                header('Location: /admin/posts/edit/' . $id);
                exit;
            }

            $dataToUpdate = [
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'status' => $status,
                'featured_image' => ($newImageName !== null) ? $newImageName : $postOriginal['featured_image'],
            ];

            if ($status === 'published' && $postOriginal['status'] !== 'published') {
                $dataToUpdate['published_at'] = date('Y-m-d H:i:s');
            } else {
                $dataToUpdate['published_at'] = $postOriginal['published_at'];
            }

            if ($this->postModel->update($id, $dataToUpdate)) {
                setFlashMessage('post_feedback', 'Post atualizado com sucesso!', 'success');
            } else {
                setFlashMessage('post_feedback', 'Erro ao atualizar o post. Verifique se o novo slug já existe.', 'danger');
                $_SESSION['old_input'] = $_POST;
                header('Location: /admin/posts/edit/' . $id);
                exit;
            }

            header('Location: /admin/posts');
            exit;
        }

        header('Location: /admin/posts/edit/' . $id);
        exit;
    }

    /**
     * Exclui um post.
     */
    public function delete(int $id): void
    {
        $post = $this->postModel->findById($id);

        if (!$post) {
            setFlashMessage('post_feedback', "Post com ID '$id' não encontrado para exclusão.", 'warning');
            header('Location: /admin/posts');
            exit;
        }

        if ($this->postModel->delete($id)) {
            if (!empty($post['featured_image'])) {
                $imagePath = public_path('uploads/images/' . $post['featured_image']);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            setFlashMessage('post_feedback', 'Post excluído com sucesso!', 'success');
        } else {
            setFlashMessage('post_feedback', 'Erro ao excluir o post. Tente novamente.', 'danger');
        }

        header('Location: /admin/posts');
        exit;
    }
}
