<?php
// app/Models/AdminUserModel.php
namespace App\Models;

use App\Core\Database;
use PDO;

class AdminUserModel
{
    private PDO $db;
    private string $table = 'admin_users';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Busca um usuário administrador pelo seu username.
     * @param string $username
     * @return array|false Retorna os dados do usuário ou false se não encontrado.
     */
    public function findByUsername(string $username): array|false
    {
        try {
            $query = "SELECT id, username, email, password_hash, name, photo 
                      FROM " . $this->table . " 
                      WHERE username = :username 
                      LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // error_log("Erro ao buscar admin por username '$username': " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca um usuário administrador pelo seu ID.
     * @param int $id O ID do usuário.
     * @return array|false Dados do usuário ou false se não encontrado.
     */
    public function findById(int $id): array|false
    {
        try {
            $query = "SELECT id, username, email, name, photo FROM " . $this->table . " WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // error_log("Erro ao buscar admin por ID '$id': " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza os dados de um usuário administrador.
     * @param int $id ID do usuário a ser atualizado.
     * @param array $data Dados a serem atualizados (ex: ['name' => 'Novo Nome', 'password_hash' => '...'])
     * @return bool True em sucesso, false em falha.
     */
    public function update(int $id, array $data): bool
    {
        if (empty($data)) {
            return false; // Nada para atualizar
        }

        // Monta a parte SET da query dinamicamente baseada nos dados recebidos
        $setParts = [];
        foreach (array_keys($data) as $key) {
            $setParts[] = "$key = :$key";
        }
        $setString = implode(', ', $setParts);

        try {
            $query = "UPDATE " . $this->table . " SET " . $setString . " WHERE id = :id";
            $stmt = $this->db->prepare($query);

            // Faz o bind dos valores do array $data e do id
            foreach ($data as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (\PDOException $e) {
            // error_log("Erro ao atualizar admin ID '$id': " . $e->getMessage());
            // die("Erro ao atualizar perfil: " . $e->getMessage()); // Para debug
            return false;
        }
    }

    public function findByIdWithPassword(int $id): array|false
    {
        try {
            // Esta query INCLUI o password_hash
            $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return false;
        }
    }
}
