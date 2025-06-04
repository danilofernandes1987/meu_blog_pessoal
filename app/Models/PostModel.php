<?php
// app/Models/PostModel.php
namespace App\Models;

use App\Core\Database; // Para usar nossa classe de conexão com o banco
use PDO; // Para type hinting e usar constantes PDO

class PostModel
{
    private PDO $db; // Propriedade para armazenar a conexão PDO
    private string $table = 'posts'; // Nome da tabela no banco de dados

    public function __construct()
    {
        // Obtém a instância da conexão com o banco de dados e a armazena
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Busca todos os posts do banco de dados, ordenados pelo mais recente.
     * @return array Lista de posts ou um array vazio se não houver posts.
     */
    public function findAll(int $limit, int $offset): array
    {
        try {
            $query = "SELECT id, title, slug, LEFT(content, 200) AS excerpt, created_at 
                      FROM " . $this->table . " 
                      WHERE status = 'published' 
                      ORDER BY created_at DESC
                      LIMIT :limit OFFSET :offset";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // error_log("Erro ao buscar posts paginados: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Conta o total de posts publicados.
     * @return int Total de posts publicados.
     */
    public function countAllPublished(): int
    {
        try {
            $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE status = 'published'";
            $stmt = $this->db->query($query);
            return (int) $stmt->fetchColumn(); // fetchColumn() para pegar um único valor
        } catch (\PDOException $e) {
            // error_log("Erro ao contar posts: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Busca um post específico pelo seu slug.
     * @param string $slug O slug do post a ser encontrado.
     * @return array|false Retorna os dados do post como array associativo ou false se não encontrado.
     */
    public function findBySlug(string $slug): array|false
    {
        try {
            $query = "SELECT id, title, slug, content, created_at, updated_at 
                      FROM " . $this->table . " 
                      WHERE slug = :slug 
                      LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); // fetch() para um único resultado
        } catch (\PDOException $e) {
            // error_log("Erro ao buscar post pelo slug '$slug': " . $e->getMessage());
            // die("Erro ao buscar post: " . $e->getMessage());
            return false; // Retorna false em caso de erro
        }
    }

    /**
     * Busca um post específico pelo seu ID.
     * @param int $id O ID do post a ser encontrado.
     * @return array|false Retorna os dados do post como array associativo ou false se não encontrado.
     */
    public function findById(int $id): array|false
    {
        try {
            $query = "SELECT id, title, slug, content, created_at, updated_at 
                      FROM " . $this->table . " 
                      WHERE id = :id 
                      LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // error_log("Erro ao buscar post pelo ID '$id': " . $e->getMessage());
            // die("Erro ao buscar post: " . $e->getMessage());
            return false; // Retorna false em caso de erro
        }
    }

    // --- Métodos para Criar, Atualizar e Deletar Posts (CRUD) ---
    // Adicionaremos estes métodos mais tarde, quando formos construir o painel administrativo.
    // public function create(array $data): int|false { ... }
    // public function update(int $id, array $data): bool { ... }
    // public function delete(int $id): bool { ... }
}
