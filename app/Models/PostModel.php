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
            $query = "SELECT id, title, slug, LEFT(content, 200) AS excerpt, created_at, featured_image 
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
            $query = "SELECT id, title, slug, content, created_at, updated_at, featured_image
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
            $query = "SELECT id, title, slug, content, created_at, updated_at, featured_image
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

    /**
     * Busca todos os posts para a área administrativa, sem filtro de status por padrão.
     * @param string $orderBy Coluna para ordenação.
     * @param string $orderDir Direção da ordenação (ASC ou DESC).
     * @return array Lista de posts.
     */
    public function getAllForAdmin(string $orderBy = 'created_at', string $orderDir = 'DESC'): array
    {
        try {
            // Validação simples para orderBy e orderDir para evitar SQL Injection
            $allowedOrderBy = ['id', 'title', 'slug', 'created_at', 'updated_at', 'status'];
            $allowedOrderDir = ['ASC', 'DESC'];
            if (!in_array($orderBy, $allowedOrderBy)) {
                $orderBy = 'created_at'; // Default seguro
            }
            if (!in_array(strtoupper($orderDir), $allowedOrderDir)) {
                $orderDir = 'DESC'; // Default seguro
            }

            $query = "SELECT id, title, slug, status, created_at, updated_at 
                      FROM " . $this->table . " 
                      ORDER BY " . $orderBy . " " . $orderDir;

            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // error_log("Erro ao buscar todos os posts para admin: " . $e->getMessage());
            return [];
        }
    }

    // --- Métodos para Criar, Atualizar e Deletar Posts (CRUD) ---

    /**
     * Cria um novo post no banco de dados.
     * @param array $data Dados do post (ex: ['title' => ..., 'slug' => ..., 'content' => ..., 'status' => ...])
     * @return int|false Retorna o ID do último post inserido em caso de sucesso, ou false em caso de falha.
     */
    /**
     * Cria um novo post no banco de dados.
     * @param array $data Dados do post (incluindo 'featured_image' opcional)
     * @return int|false Retorna o ID do último post inserido ou false em caso de falha.
     */
    public function create(array $data): int|false
    {
        try {
            // Query CORRIGIDA para incluir a coluna featured_image
            $query = "INSERT INTO " . $this->table . " (title, slug, content, status, author_id, featured_image) 
                          VALUES (:title, :slug, :content, :status, :author_id, :featured_image)";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':slug', $data['slug']);
            $stmt->bindParam(':content', $data['content']);
            $stmt->bindParam(':status', $data['status']);

            // Trata o author_id opcional
            $authorId = $data['author_id'] ?? null;
            $stmt->bindValue(':author_id', $authorId, PDO::PARAM_INT);

            // Trata o featured_image opcional
            $featuredImage = $data['featured_image'] ?? null;
            $stmt->bindValue(':featured_image', $featuredImage, PDO::PARAM_STR);

            if ($stmt->execute()) {
                return (int) $this->db->lastInsertId();
            }
            return false;
        } catch (\PDOException $e) {
            // error_log("Erro ao criar post: " . $e->getMessage());
            // Descomente a linha abaixo para depuração detalhada do erro SQL
            // die("Erro ao criar post: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza um post existente no banco de dados.
     * @param int $id ID do post a ser atualizado.
     * @param array $data Dados do post a serem atualizados (incluindo 'featured_image')
     * @return bool Retorna true em caso de sucesso, ou false em caso de falha.
     */
    public function update(int $id, array $data): bool
    {
        try {
            // Query CORRIGIDA para incluir featured_image na cláusula SET
            $query = "UPDATE " . $this->table . " 
                          SET title = :title, 
                              slug = :slug, 
                              content = :content, 
                              status = :status,
                              featured_image = :featured_image -- Adicionado aqui
                         WHERE id = :id";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':slug', $data['slug']);
            $stmt->bindParam(':content', $data['content']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Trata o featured_image. Se não for passado em $data, mantenha o valor existente.
            // O controller já cuida dessa lógica, então aqui apenas fazemos o bind.
            $stmt->bindValue(':featured_image', $data['featured_image'] ?? null, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (\PDOException $e) {
            // error_log("Erro ao atualizar post ID '$id': " . $e->getMessage());
            // die("Erro ao atualizar post: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Exclui um post do banco de dados pelo seu ID.
     * @param int $id ID do post a ser excluído.
     * @return bool Retorna true em caso de sucesso, ou false em caso de falha.
     */
    public function delete(int $id): bool
    {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\PDOException $e) {
            // error_log("Erro ao excluir post ID '$id': " . $e->getMessage());
            // die("Erro ao excluir post (PDOException): " . $e->getMessage());
            return false;
        }
    }
}
