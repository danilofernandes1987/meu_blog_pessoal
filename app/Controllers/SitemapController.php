<?php
// app/Controllers/SitemapController.php
namespace App\Controllers;

use App\Models\PostModel;

class SitemapController
{
    public function index()
    {
        $postModel = new PostModel();
        // Busca todos os posts publicados (você pode precisar de um método para isso)
        $posts = $postModel->getAllForSitemap(); // Você precisará criar este método no PostModel

        header("Content-Type: application/xml; charset=utf-8");
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // URL da Página Inicial
        echo '<url><loc>https://www.danilosilva.net/</loc><priority>1.0</priority></url>';
        // URL do Currículo, Contato, etc.
        echo '<url><loc>https://www.danilosilva.net/curriculo</loc><priority>0.8</priority></url>';
        echo '<url><loc>https://www.danilosilva.net/contact</loc><priority>0.5</priority></url>';
        echo '<url><loc>https://www.danilosilva.net/posts</loc><priority>0.7</priority></url>';

        // URLs dos Posts
        foreach ($posts as $post) {
            echo '<url>';
            echo '<loc>https://www.danilosilva.net/posts/show/' . htmlspecialchars($post['slug']) . '</loc>';
            echo '<lastmod>' . date('c', strtotime($post['updated_at'])) . '</lastmod>';
            echo '<priority>0.9</priority>';
            echo '</url>';
        }

        echo '</urlset>';
    }
}
