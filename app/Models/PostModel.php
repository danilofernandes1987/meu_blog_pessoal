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
    public function create(array $data): int|false
    {
        try {
            // Define as colunas permitidas para inserção para segurança básica
            // author_id é opcional por enquanto
            $query = "INSERT INTO " . $this->table . " (title, slug, content, status, author_id) 
                      VALUES (:title, :slug, :content, :status, :author_id)";

            $stmt = $this->db->prepare($query);

            // Bind dos parâmetros
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':slug', $data['slug']);
            $stmt->bindParam(':content', $data['content']);
            $stmt->bindParam(':status', $data['status']);

            // author_id é opcional, pode ser NULL
            $authorId = $data['author_id'] ?? null;
            if ($authorId === null) {
                $stmt->bindValue(':author_id', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':author_id', $authorId, PDO::PARAM_INT);
            }

            if ($stmt->execute()) {
                return (int) $this->db->lastInsertId(); // Retorna o ID do post recém-criado
            }
            return false;
        } catch (\PDOException $e) {
            // Em uma aplicação real, trate o erro de slug duplicado (SQLSTATE[23000]) de forma mais específica.
            // error_log("Erro ao criar post: " . $e->getMessage());
            // Por agora, apenas para debug, podemos mostrar o erro:
            // if ($e->getCode() == 23000) { // Código para violação de constraint (ex: slug duplicado)
            //     die("Erro ao criar post: Possível slug duplicado ou outro campo único. Detalhe: " . $e->getMessage());
            // }
            // die("Erro ao criar post (PDOException): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza um post existente no banco de dados.
     * @param int $id ID do post a ser atualizado.
     * @param array $data Dados do post a serem atualizados (ex: ['title' => ..., 'slug' => ..., 'content' => ..., 'status' => ...])
     * @return bool Retorna true em caso de sucesso, ou false em caso de falha.
     */
    public function update(int $id, array $data): bool
    {
        try {
            // author_id pode ser atualizado se fizer parte dos $data
            // Se não for passado, não será alterado (ou defina como NULL se for o caso)
            $authorId = $data['author_id'] ?? null; // Pega o author_id se existir nos dados, senão null

            $query = "UPDATE " . $this->table . " 
                      SET title = :title, 
                          slug = :slug, 
                          content = :content, 
                          status = :status" .
                ($authorId !== null ? ", author_id = :author_id" : "") . // Adiciona author_id à query apenas se fornecido
                " WHERE id = :id";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':slug', $data['slug']);
            $stmt->bindParam(':content', $data['content']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($authorId !== null) {
                $stmt->bindParam(':author_id', $authorId, PDO::PARAM_INT);
            }

            return $stmt->execute();
        } catch (\PDOException $e) {
            // error_log("Erro ao atualizar post ID '$id': " . $e->getMessage());
            // die("Erro ao atualizar post (PDOException): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Exclui um post do banco de dados pelo seu ID.
     * @param int $id ID do post a ser excluído.
     * @return bool Retorna true em caso de sucesso, ou false em caso de falha.
     */
    public function delete(int $id): bool {
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
