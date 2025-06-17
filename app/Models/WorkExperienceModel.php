<?php
// app/Models/WorkExperienceModel.php
namespace App\Models;

use App\Core\Database;
use PDO;

class WorkExperienceModel
{
    private $db;
    private $table = 'work_experiences';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM " . $this->table . " ORDER BY display_order DESC, start_date DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return [];
        }
    }

    public function create(array $data)
    {
        try {
            $endDate = !empty($data['end_date']) ? $data['end_date'] : null;
            $sql = "INSERT INTO " . $this->table . " (job_title, company, start_date, end_date, description) VALUES (:job_title, :company, :start_date, :end_date, :description)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':job_title', $data['job_title']);
            $stmt->bindParam(':company', $data['company']);
            $stmt->bindParam(':start_date', $data['start_date']);
            $stmt->bindParam(':end_date', $endDate);
            $stmt->bindParam(':description', $data['description']);
            return $stmt->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Busca uma experiência pelo seu ID.
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
     * Atualiza uma experiência profissional.
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data)
    {
        try {
            $endDate = !empty($data['end_date']) ? $data['end_date'] : null;
            $sql = "UPDATE " . $this->table . " SET job_title = :job_title, company = :company, start_date = :start_date, end_date = :end_date, description = :description WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':job_title', $data['job_title']);
            $stmt->bindParam(':company', $data['company']);
            $stmt->bindParam(':start_date', $data['start_date']);
            $stmt->bindParam(':end_date', $endDate);
            $stmt->bindParam(':description', $data['description']);
            return $stmt->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Exclui uma experiência profissional.
     * @param int $id
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
            return false;
        }
    }
}
