<?php
// app/Models/AdminUserModel.php
namespace App\Models;

use App\Core\Database;
use PDO;

class AdminUserModel {
    private PDO $db;
    private string $table = 'admin_users';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Busca um usuário administrador pelo seu username.
     * @param string $username
     * @return array|false Retorna os dados do usuário ou false se não encontrado.
     */
    public function findByUsername(string $username): array|false {
        try {
            $query = "SELECT id, username, email, password_hash, name 
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

    // No futuro, podemos adicionar findByEmail(), findById(), create(), etc.
}
?>