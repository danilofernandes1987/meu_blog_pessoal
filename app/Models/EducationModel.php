<?php
// app/Models/EducationModel.php
namespace App\Models;

use App\Core\Database;
use PDO;

class EducationModel
{
    private $db;
    private $table = 'educations';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM " . $this->table . " ORDER BY display_order DESC, start_year DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return [];
        }
    }

    public function create(array $data)
    {
        try {
            $endYear = !empty($data['end_year']) ? $data['end_year'] : null;
            $sql = "INSERT INTO " . $this->table . " (degree, institution, start_year, end_year) VALUES (:degree, :institution, :start_year, :end_year)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':degree', $data['degree']);
            $stmt->bindParam(':institution', $data['institution']);
            $stmt->bindParam(':start_year', $data['start_year'], PDO::PARAM_INT);
            $stmt->bindParam(':end_year', $endYear, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Busca uma formação pelo seu ID.
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
     * Atualiza uma formação acadêmica.
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data)
    {
        try {
            $endYear = !empty($data['end_year']) ? $data['end_year'] : null;
            $sql = "UPDATE " . $this->table . " SET degree = :degree, institution = :institution, start_year = :start_year, end_year = :end_year WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':degree', $data['degree']);
            $stmt->bindParam(':institution', $data['institution']);
            $stmt->bindParam(':start_year', $data['start_year'], PDO::PARAM_INT);
            $stmt->bindParam(':end_year', $endYear, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Exclui uma formação acadêmica.
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
