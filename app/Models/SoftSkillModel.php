<?php
// app/Models/SoftSkillModel.php
namespace App\Models;

use App\Core\Database;
use PDO;

class SoftSkillModel
{
    private $db;
    private $table = 'soft_skills';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Busca todos os soft skills ativos, ordenados por 'display_order'.
     * @return array
     */
    public function findAllActive()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM " . $this->table . " WHERE is_active = TRUE ORDER BY display_order ASC, id ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return [];
        }
    }

    /**
     * Busca todos os soft skills para a área administrativa.
     * @return array
     */
    public function findAllForAdmin()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM " . $this->table . " ORDER BY display_order ASC, id ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return [];
        }
    }

    /**
     * Cria um novo soft skill.
     * @param array $data Dados da skill ['icon_class', 'title', 'description'].
     * @return bool
     */
    public function create(array $data)
    {
        try {
            $sql = "INSERT INTO " . $this->table . " (icon_class, title, description) VALUES (:icon_class, :title, :description)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':icon_class', $data['icon_class']);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':description', $data['description']);
            return $stmt->execute();
        } catch (\PDOException $e) {
            // error_log("Erro ao criar skill: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Exclui um soft skill pelo seu ID.
     * @param int $id ID da skill.
     * @return bool
     */
    public function delete(int $id)
    {
        try {
            $sql = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\PDOException $e) {
            // error_log("Erro ao excluir skill: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca um soft skill pelo seu ID.
     * @param int $id
     * @return array|false
     */
    public function findById(int $id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Atualiza um soft skill.
     * @param int $id ID da skill.
     * @param array $data Dados a serem atualizados.
     * @return bool
     */
    public function update(int $id, array $data)
    {
        try {
            $sql = "UPDATE " . $this->table . " SET icon_class = :icon_class, title = :title, description = :description WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':icon_class', $data['icon_class']);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':description', $data['description']);
            return $stmt->execute();
        } catch (\PDOException $e) {
            // error_log("Erro ao atualizar skill: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Troca a ordem de exibição entre duas skills.
     * @param int $skillId1 ID da primeira skill.
     * @param int $skillId2 ID da segunda skill.
     * @return bool
     */
    public function swapOrder(int $skillId1, int $skillId2): bool
    {
        try {
            // Busca a ordem atual das duas skills
            $skill1 = $this->findById($skillId1);
            $skill2 = $this->findById($skillId2);

            if (!$skill1 || !$skill2) {
                return false;
            }

            $order1 = $skill1['display_order'];
            $order2 = $skill2['display_order'];

            // Inicia uma transação
            $this->db->beginTransaction();

            // Atualiza a primeira skill com a ordem da segunda
            $stmt1 = $this->db->prepare("UPDATE " . $this->table . " SET display_order = :order WHERE id = :id");
            $stmt1->bindParam(':order', $order2, PDO::PARAM_INT);
            $stmt1->bindParam(':id', $skillId1, PDO::PARAM_INT);
            $stmt1->execute();

            // Atualiza a segunda skill com a ordem da primeira
            $stmt2 = $this->db->prepare("UPDATE " . $this->table . " SET display_order = :order WHERE id = :id");
            $stmt2->bindParam(':order', $order1, PDO::PARAM_INT);
            $stmt2->bindParam(':id', $skillId2, PDO::PARAM_INT);
            $stmt2->execute();

            // Confirma a transação
            return $this->db->commit();
        } catch (\PDOException $e) {
            // Em caso de erro, desfaz a transação
            $this->db->rollBack();
            // error_log("Erro ao trocar ordem das skills: " . $e->getMessage());
            return false;
        }
    }
}
