<?php
// app/Controllers/SearchController.php
namespace App\Controllers;

use App\Core\BaseController;
use App\Models\PostModel;

class SearchController extends BaseController
{
    public function index()
    {
        $term = trim($_GET['q'] ?? '');
        $postModel = new PostModel();
        
        $postsPerPage = $this->siteConfig['postsPerPage'] ?? 5;
        $currentPage = isset($_GET['page']) && filter_var($_GET['page'], FILTER_VALIDATE_INT) ? (int)$_GET['page'] : 1;
        $offset = ($currentPage - 1) * $postsPerPage;

        $results = [];
        $totalResults = 0;

        if (!empty($term)) {
            $results = $postModel->search($term, $postsPerPage, $offset);
            $totalResults = $postModel->countSearchResults($term);
        }

        $totalPages = ceil($totalResults / $postsPerPage);
        
        $data = [
            'pageTitle'    => 'Resultados da Busca por "' . htmlspecialchars($term) . '"',
            'contentTitle' => 'Resultados da Busca',
            'searchTerm'   => $term,
            'results'      => $results,
            'totalResults' => $totalResults,
            'currentPage'  => $currentPage,
            'totalPages'   => $totalPages
        ];

        $this->view('search.index', $data);
    }
}
