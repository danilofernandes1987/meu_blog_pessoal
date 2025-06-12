<?php
// app/Controllers/PostController.php
namespace App\Controllers;

use App\Core\BaseController; // Para usar o método view()
use App\Models\PostModel;    // Nosso model de posts
// Poderíamos usar também o ErrorController se quiséssemos ter um método específico aqui,
// mas o Router já deve estar tratando controllers/métodos não encontrados.
// Se um post específico não for encontrado, podemos lidar com isso aqui.

class PostController extends BaseController {
    private PostModel $postModel;

    public function __construct() {
        parent::__construct(); // Chama o construtor do BaseController (para carregar config, etc.)
        $this->postModel = new PostModel(); // Cria uma instância do PostModel
    }

   /**
     * Exibe uma lista de todos os posts com paginação.
     */
    public function index()
    {
        // 1. Obter o número de posts por página da configuração
        $postsPerPage = $this->siteConfig['postsPerPage'] ?? 5;

        // 2. Obter o número da página atual da URL (ex: /posts?page=2)
        $currentPage = isset($_GET['page']) && filter_var($_GET['page'], FILTER_VALIDATE_INT) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;

        // 3. Calcular o offset para a query SQL
        $offset = ($currentPage - 1) * $postsPerPage;

        // 4. Buscar os posts paginados (CHAMADA CORRIGIDA)
        $posts = $this->postModel->findAll($postsPerPage, $offset);

        // 5. Obter o total de posts para calcular o total de páginas
        $totalPosts = $this->postModel->countAllPublished();
        $totalPages = ceil($totalPosts / $postsPerPage);
        
        // 6. Se a página pedida não existir, mostra 404
        if ($currentPage > $totalPages && $totalPosts > 0) {
            $errorController = new ErrorController();
            $errorController->show404("Página de blog não encontrada.");
            return;
        }

        // 7. Passar os dados para a view
        $data = [
            'pageTitle'    => 'Blog - Página ' . $currentPage,
            'contentTitle' => 'Artigos Recentes do Blog',
            'posts'        => $posts,
            'currentPage'  => $currentPage,
            'totalPages'   => $totalPages
        ];

        $this->view('posts.index', $data);
    }

    /**
     * Exibe um post específico baseado no seu slug.
     * O slug virá da URL, capturado pelo Router e passado como parâmetro.
     * Ex: /posts/show/meu-primeiro-post
     */
    public function show(string $slug = ''): void {
        if (empty($slug)) {
            // Se nenhum slug for fornecido, redireciona para a lista de posts ou mostra erro 404.
            // Por simplicidade, vamos chamar nosso ErrorController.
            $errorController = new ErrorController();
            $errorController->show404("Nenhum slug de post fornecido.");
            return; // exit já é chamado em trigger404/show404
        }

        $post = $this->postModel->findBySlug($slug);

        if (!$post) {
            // Post não encontrado com esse slug, mostra página 404
            $errorController = new ErrorController();
            $errorController->show404("Post com o slug '$slug' não encontrado.");
            return; // exit já é chamado em trigger404/show404
        }

        $data = [
            'pageTitle' => htmlspecialchars($post['title']) . ' - Blog', // Título da aba do navegador
            'contentTitle' => htmlspecialchars($post['title']),         // Título principal na página
            'post' => $post // Passa o post encontrado para a view
        ];

        // Renderiza a view que mostrará o post individual
        $this->view('posts.show', $data);
    }
}
?>