<?php
// app/Models/PostModel.php
namespace App\Models;

use App\Core\Database;
use PDO;

class PostModel
{
    /** @var PDO A instância da conexão com o banco de dados. */
    private PDO $db;
    
    /** @var string O nome da tabela de posts. */
    private string $table = 'posts';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Busca os posts publicados para a página principal do blog, com paginação.
     * A ordenação prioriza a data de publicação, garantindo que os posts mais recentes apareçam primeiro.
     * @param int $limit O número de posts a serem retornados.
     * @param int $offset O ponto de partida para a busca (para paginação).
     * @return array Uma lista de posts.
     */
    public function findAll(int $limit, int $offset): array
    {
        try {
            $query = "SELECT id, title, slug, LEFT(content, 200) AS excerpt, created_at, published_at, featured_image 
                      FROM " . $this->table . " 
                      WHERE status = 'published' 
                      ORDER BY published_at DESC, created_at DESC
                      LIMIT :limit OFFSET :offset";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Em um ambiente de produção, é ideal logar o erro sem interromper o fluxo do usuário.
            // error_log("Erro ao buscar posts paginados: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Conta o total de posts com o status 'published'.
     * Essencial para o cálculo da paginação na página do blog.
     * @return int O número total de posts publicados.
     */
    public function countAllPublished(): int
    {
        try {
            $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE status = 'published'";
            $stmt = $this->db->query($query);
            return (int) $stmt->fetchColumn();
        } catch (\PDOException $e) {
            return 0;
        }
    }

    /**
     * Busca um único post pelo seu slug, apenas se estiver publicado.
     * Garante que visitantes não acessem rascunhos diretamente pela URL.
     * @param string $slug O slug amigável do post.
     * @return array|false Os dados do post ou false se não for encontrado.
     */
    public function findBySlug(string $slug): array|false
    {
        try {
            $query = "SELECT id, title, slug, content, created_at, updated_at, published_at, featured_image
                      FROM " . $this->table . " 
                      WHERE slug = :slug AND status = 'published'
                      LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Busca um único post pelo seu ID, independentemente do status.
     * Método utilizado principalmente na área administrativa para edição.
     * @param int $id O ID do post.
     * @return array|false Os dados do post ou false se não for encontrado.
     */
    public function findById(int $id): array|false
    {
        try {
            $query = "SELECT id, title, slug, content, status, created_at, updated_at, published_at, featured_image
                      FROM " . $this->table . " 
                      WHERE id = :id 
                      LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Busca todos os posts para a listagem na área administrativa, sem paginação.
     * @return array Uma lista de todos os posts.
     */
    public function getAllForAdmin(): array
    {
        try {
            $query = "SELECT id, title, slug, status, created_at, updated_at, published_at
                      FROM " . $this->table . " 
                      ORDER BY created_at DESC";
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return [];
        }
    }

    /**
     * Cria um novo post no banco de dados.
     * @param array $data Dados do post, incluindo 'title', 'slug', 'content', 'status', etc.
     * @return int|false O ID do post recém-criado ou false em caso de falha.
     */
    public function create(array $data): int|false
    {
        try {
            $sql = "INSERT INTO " . $this->table . " (title, slug, content, status, author_id, featured_image, published_at) 
                    VALUES (:title, :slug, :content, :status, :author_id, :featured_image, :published_at)";
            
            $stmt = $this->db->prepare($sql);

            // bindValue é usado aqui por sua flexibilidade em lidar com valores nulos,
            // que são comuns em campos opcionais como imagem de destaque ou data de publicação.
            $stmt->bindValue(':title', $data['title']);
            $stmt->bindValue(':slug', $data['slug']);
            $stmt->bindValue(':content', $data['content']);
            $stmt->bindValue(':status', $data['status']);
            $stmt->bindValue(':author_id', $data['author_id'] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':featured_image', $data['featured_image'] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':published_at', $data['published_at'] ?? null);

            if ($stmt->execute()) {
                return (int) $this->db->lastInsertId();
            }
            return false;
        } catch (\PDOException $e) {
            // error_log("Erro ao criar post: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza um post existente.
     * @param int $id O ID do post a ser atualizado.
     * @param array $data Os novos dados para o post.
     * @return bool True em sucesso, false em falha.
     */
    public function update(int $id, array $data): bool
    {
        try {
            $sql = "UPDATE " . $this->table . " SET 
                        title = :title, 
                        slug = :slug, 
                        content = :content, 
                        status = :status,
                        featured_image = :featured_image,
                        published_at = :published_at
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':title', $data['title']);
            $stmt->bindValue(':slug', $data['slug']);
            $stmt->bindValue(':content', $data['content']);
            $stmt->bindValue(':status', $data['status']);
            $stmt->bindValue(':featured_image', $data['featured_image'] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':published_at', $data['published_at'] ?? null);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Exclui um post do banco de dados.
     * @param int $id O ID do post a ser excluído.
     * @return bool True em sucesso, false em falha.
     */
    public function delete(int $id): bool
    {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Busca posts publicados que correspondem a um termo de pesquisa.
     * A busca é feita com 'LIKE' e wildcards '%' para encontrar o termo em qualquer parte do título ou conteúdo.
     * @param string $term O termo de busca.
     * @param int $limit O número de resultados por página.
     * @param int $offset O deslocamento para a paginação.
     * @return array Uma lista de posts encontrados.
     */
    public function search(string $term, int $limit, int $offset): array
    {
        try {
            $searchTerm = '%' . $term . '%';
            $sql = "SELECT id, title, slug, LEFT(content, 250) AS excerpt, created_at, published_at
                    FROM " . $this->table . "
                    WHERE status = 'published' AND (title LIKE ? OR content LIKE ?)
                    ORDER BY published_at DESC, created_at DESC
                    LIMIT ? OFFSET ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$searchTerm, $searchTerm, $limit, $offset]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return [];
        }
    }

    /**
     * Conta o total de resultados para uma busca por posts publicados.
     * @param string $term O termo de busca.
     * @return int O número total de posts encontrados.
     */
    public function countSearchResults(string $term): int
    {
        try {
            $searchTerm = '%' . $term . '%';
            $sql = "SELECT COUNT(*) FROM " . $this->table . " WHERE status = 'published' AND (title LIKE ? OR content LIKE ?)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$searchTerm, $searchTerm]);
            return (int) $stmt->fetchColumn();
        } catch (\PDOException $e) {
            return 0;
        }
    }

    /**
     * Conta o total de todos os posts na tabela, independentemente do status.
     * Utilizado para o dashboard administrativo.
     * @return int O número total de posts.
     */
    public function countAll(): int
    {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM " . $this->table);
            return (int) $stmt->fetchColumn();
        } catch (\PDOException $e) {
            return 0;
        }
    }
}
