<?php
// app/Controllers/Admin/PostsController.php
namespace App\Controllers\Admin;

use App\Core\BaseAdminController; // Herda do nosso guardião de admin
use App\Models\PostModel;
use App\Controllers\ErrorController;

class PostsController extends BaseAdminController
{
    private PostModel $postModel;

    public function __construct()
    {
        parent::__construct(); // Executa o construtor do BaseAdminController (verificação de auth)
        $this->postModel = new PostModel();
    }

    /**
     * Lista todos os posts na área administrativa.
     */
    public function index(): void
    {
        $posts = $this->postModel->getAllForAdmin(); // Usa o método do model

        $data = [
            'pageTitle'    => 'Gerenciar Posts',
            'contentTitle' => 'Todos os Posts do Blog',
            'posts'        => $posts,
            'siteName'     => $this->siteConfig['siteName'] ?? 'Painel Admin', // Para o layout admin
            'adminUsername' => $_SESSION['admin_username'] ?? '' // Para o layout admin
        ];

        // Renderiza a view dentro do layout administrativo
        $this->view('admin.posts.index', $data, 'layouts.admin');
    }

    /**
     * Lida com o upload da imagem de destaque.
     * @param array $fileInput O array do arquivo vindo de $_FILES['featured_image'].
     * @param string|null $oldImageFilename O nome do arquivo da imagem antiga, para exclusão durante a atualização.
     * @return string|null|false Retorna o nome do novo arquivo em sucesso, null se nenhum arquivo foi enviado, ou false em caso de erro de upload/validação.
     */
    protected function handleImageUpload(array $fileInput, ?string $oldImageFilename = null): string|null|false
    {
        // Verifica se um arquivo foi enviado e se não houve erros no upload
        if (isset($fileInput) && $fileInput['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../../public/uploads/images/';
            $maxFileSize = 2 * 1024 * 1024; // 2 MB
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            // 1. Validação de tamanho
            if ($fileInput['size'] > $maxFileSize) {
                // erro de tamanho
                $_SESSION['errors']['featured_image'] = 'O arquivo de imagem é muito grande (máximo 2MB).';
                return false;
            }

            // 2. Validação de tipo de arquivo
            $fileType = mime_content_type($fileInput['tmp_name']);
            if (!in_array($fileType, $allowedTypes)) {
                // erro de tipo
                $_SESSION['errors']['featured_image'] = 'Tipo de arquivo inválido. Apenas JPG, PNG, GIF e WebP são permitidos.';
                return false;
            }

            // 3. Gerar nome de arquivo único para evitar conflitos
            $fileName = time() . '-' . preg_replace('/[^A-Za-z0-9.\-]/', '', basename($fileInput['name']));
            $targetFile = $uploadDir . $fileName;

            // 4. Mover o arquivo para o diretório de uploads
            if (move_uploaded_file($fileInput['tmp_name'], $targetFile)) {
                // 5. Se moveu com sucesso e existe uma imagem antiga, delete-a
                if ($oldImageFilename && file_exists($uploadDir . $oldImageFilename)) {
                    unlink($uploadDir . $oldImageFilename);
                }
                return $fileName; // Retorna o nome do novo arquivo
            } else {
                // erro ao mover
                $_SESSION['errors']['featured_image'] = 'Ocorreu um erro ao salvar a imagem.';
                return false;
            }
        } elseif (isset($fileInput) && $fileInput['error'] !== UPLOAD_ERR_NO_FILE) {
            // Se houve um erro de upload que não seja "nenhum arquivo enviado"
            $_SESSION['errors']['featured_image'] = 'Ocorreu um erro durante o upload da imagem.';
            return false;
        }

        // Se nenhum arquivo novo foi enviado, retorna null
        return null;
    }

    // create(), store(), edit(), update(), delete()

    /**
     * Exibe o formulário para criar um novo post.
     * Acessado via GET /admin/posts/create
     */
    public function create(): void
    {
        $data = [
            'pageTitle' => 'Criar Novo Post',
            'contentTitle' => 'Adicionar Novo Post ao Blog',
            'siteName'     => $this->siteConfig['siteName'] ?? 'Painel Admin',
            'adminUsername' => $_SESSION['admin_username'] ?? '',
            'errors' => $_SESSION['errors'] ?? [], // Para exibir erros de validação
            'old_input' => $_SESSION['old_input'] ?? [] // Para repopular o formulário
        ];
        unset($_SESSION['errors']); // Limpa os erros da sessão após exibi-los
        unset($_SESSION['old_input']); // Limpa o input antigo da sessão

        $this->view('admin.posts.create', $data, 'layouts.admin');
    }

    /**
     * Armazena um novo post no banco de dados.
     * Acessado via POST /admin/posts/store (ou /admin/posts/create se preferir)
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validatePostRequest(); // Valida CSRF e método POST

            // Lógica de upload da imagem
            $newImageName = $this->handleImageUpload($_FILES['featured_image'] ?? null);
            if ($newImageName === false) { // Ocorreu um erro de upload
                $_SESSION['old_input'] = $_POST;
                header('Location: /admin/posts/create');
                exit;
            }

            echo $newImageName;


            // Validação básica (em um app real, use uma biblioteca de validação ou regras mais robustas)
            $errors = [];
            $title = trim($_POST['title'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $status = $_POST['status'] ?? 'draft'; // Default para draft se não enviado

            if (empty($title)) {
                $errors['title'] = 'O título é obrigatório.';
            }
            if (empty($slug)) {
                $errors['slug'] = 'O slug é obrigatório.';
            } elseif (!preg_match('/^[a-z0-9-]+$/', $slug)) {
                $errors['slug'] = 'O slug deve conter apenas letras minúsculas, números e hífens.';
            }
            if (empty($content)) {
                $errors['content'] = 'O conteúdo é obrigatório.';
            }
            if (!in_array($status, ['published', 'draft'])) {
                $status = 'draft'; // Força um valor válido
            }

            if (!empty($errors)) {
                // Guarda os erros e o input antigo na sessão para exibir no formulário
                $_SESSION['errors'] = $errors;
                $_SESSION['old_input'] = $_POST;
                // Se o upload falhou e a validação de outros campos também, os erros de imagem já estão na sessão
                $_SESSION['errors'] = array_merge($_SESSION['errors'] ?? [], $errors);
                // Usando flash message para erro de validação geral se necessário, embora os erros de campo sejam mais específicos
                // setFlashMessage('post_form_error', 'Por favor, corrija os erros no formulário.', 'danger');
                header('Location: /admin/posts/create'); // Redireciona de volta para o formulário
                exit;
            }

            // Se não houver erros, tenta criar o post
            $dataToSave = [
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'status' => $status,
                'featured_image' => $newImageName,
                'author_id' => $_SESSION['admin_user_id'] ?? null // Pega o ID do admin logado
            ];

            $postId = $this->postModel->create($dataToSave);

            if ($postId) {
                // Adicionar mensagem de sucesso (flash message) seria ideal aqui
                setFlashMessage('post_feedback', 'Post criado com sucesso!', 'success');
                header('Location: /admin/posts'); // Redireciona para a lista de posts
                exit;
            } else {
                // Erro ao salvar no banco
                // A view create já lida com erros de campo via $_SESSION['errors']
                // Mas podemos adicionar um erro geral se a criação falhar por outro motivo (ex: slug duplicado não pego antes)
                setFlashMessage('post_feedback', 'Erro ao criar o post. Verifique se o slug já existe ou tente novamente.', 'danger');
                // Manter os dados antigos para repopular o formulário
                $_SESSION['old_input'] = $_POST;
                header('Location: /admin/posts/create');
                exit;
            }
        } else {
            // Se não for POST, redireciona ou mostra erro
            header('Location: /admin/posts/create');
            exit;
        }
    }

    /**
     * Exibe o formulário para editar um post existente.
     * Acessado via GET /admin/posts/edit/{id}
     */
    public function edit(int $id): void
    {
        $post = $this->postModel->findById($id);

        if (!$post) {
            $errorController = new ErrorController();
            $errorController->show404("Post com ID '$id' não encontrado para edição.");
            return;
        }

        $data = [
            'pageTitle'    => 'Editar Post',
            'contentTitle' => 'Editando Post: ' . htmlspecialchars($post['title']),
            'post'         => $post, // Passa os dados do post para preencher o formulário
            'siteName'     => $this->siteConfig['siteName'] ?? 'Painel Admin',
            'adminUsername' => $_SESSION['admin_username'] ?? '',
            'errors'       => $_SESSION['errors'] ?? [],
            'old_input'    => $_SESSION['old_input'] ?? [] // Em caso de erro de validação no update
        ];
        unset($_SESSION['errors']);
        unset($_SESSION['old_input']);

        $this->view('admin.posts.edit', $data, 'layouts.admin');
    }

    /**
     * Atualiza um post existente no banco de dados.
     * Acessado via POST /admin/posts/update/{id}
     */
    public function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validatePostRequest();
            $postOriginal = $this->postModel->findById($id);
            if (!$postOriginal) {
                $errorController = new ErrorController();
                $errorController->show404("Post com ID '$id' não encontrado para atualização.");
                return;
            }

            // Lógica de upload da nova imagem (passando o nome da antiga para ser deletada)
            $newImageName = $this->handleImageUpload($_FILES['featured_image'] ?? null, $postOriginal['featured_image']);
            if ($newImageName === false) { // Ocorreu um erro de upload
                $_SESSION['old_input'] = $_POST;
                header('Location: /admin/posts/edit/' . $id);
                exit;
            }

            $errors = [];
            $title = trim($_POST['title'] ?? '');
            // Se o slug puder ser alterado, valide-o. Se não, use o original ou gere se o título mudou.
            // Por simplicidade, vamos permitir alteração do slug com validação.
            $slug = trim($_POST['slug'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $status = $_POST['status'] ?? 'draft';

            if (empty($title)) {
                $errors['title'] = 'O título é obrigatório.';
            }
            if (empty($slug)) {
                $errors['slug'] = 'O slug é obrigatório.';
            } elseif (!preg_match('/^[a-z0-9-]+$/', $slug)) {
                $errors['slug'] = 'O slug deve conter apenas letras minúsculas, números e hífens.';
            }
            if (empty($content)) {
                $errors['content'] = 'O conteúdo é obrigatório.';
            }
            if (!in_array($status, ['published', 'draft'])) {
                $status = 'draft';
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                // Para repopular o formulário de edição, usamos os dados que vieram do POST
                // mas mantendo o ID original do post
                $_SESSION['old_input'] = $_POST;
                $_SESSION['old_input']['id'] = $id; // Garante que o ID seja mantido
                header('Location: /admin/posts/edit/' . $id);
                exit;
            }

            $dataToUpdate = [
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'status' => $status,
                'featured_image' => ($newImageName !== null) ? $newImageName : $postOriginal['featured_image'],
                // author_id geralmente não muda na edição, ou seria uma funcionalidade separada.
                // Se você quiser permitir a mudança de autor, adicione aqui:
                // 'author_id' => $_POST['author_id'] ?? $postOriginal['author_id'],
            ];

            if ($this->postModel->update($id, $dataToUpdate)) {
                setFlashMessage('post_feedback', 'Post atualizado com sucesso!', 'success');
                header('Location: /admin/posts');
                exit;
            } else {
                setFlashMessage('post_feedback', 'Erro ao atualizar o post. Verifique se o novo slug já existe ou tente novamente.', 'danger');
                $_SESSION['old_input'] = $_POST;
                $_SESSION['old_input']['id'] = $id;
                header('Location: /admin/posts/edit/' . $id);
                exit;
            }
        } else {
            // Se não for POST, redireciona para a lista ou para o formulário de edição
            header('Location: /admin/posts/edit/' . $id);
            exit;
        }
    }

    /**
     * Exclui um post.
     * Acessado via GET /admin/posts/delete/{id} (com confirmação JS no link)
     */
    public function delete(int $id): void
    {
        // Poderia adicionar uma verificação extra aqui para garantir que o post existe antes de tentar excluir,
        // mas o PostModel::delete() já retornará false se o ID não existir e nada acontecerá.
        // Para uma mensagem de feedback mais específica, a verificação seria útil.

        $post = $this->postModel->findById($id);

        if (!$post) {
            setFlashMessage('post_feedback', "Post com ID '$id' não encontrado para exclusão.", 'warning'); // << AQUI
            header('Location: /admin/posts');
            exit;
        }

        if ($this->postModel->delete($id)) {
            // Se o post foi deletado do banco, também deleta a imagem associada
            if (!empty($post['featured_image'])) {
                $imagePath = __DIR__ . '/../../../public/uploads/images/' . $post['featured_image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            setFlashMessage('post_feedback', 'Post excluído com sucesso!', 'success'); // << AQUI
        } else {
            setFlashMessage('post_feedback', 'Erro ao excluir o post. Tente novamente.', 'danger'); // << AQUI
        }
        // Redireciona de volta para a lista de posts em ambos os casos (sucesso ou falha no delete)
        header('Location: /admin/posts');
        exit;
    }
}
