<?php
// app/Models/CourseModel.php
namespace App\Models;

use App\Core\Database;
use PDO;

class CourseModel
{
    private $db;
    private $table = 'courses';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM " . $this->table . " ORDER BY display_order DESC, completion_year DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return [];
        }
    }

    public function create(array $data)
    {
        try {
            $workload = !empty($data['workload_hours']) ? $data['workload_hours'] : null;
            $sql = "INSERT INTO " . $this->table . " (course_name, course_institution, completion_year, workload_hours) VALUES (:course_name, :course_institution, :completion_year, :workload_hours)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':course_name', $data['course_name']);
            $stmt->bindParam(':course_institution', $data['course_institution']);
            $stmt->bindParam(':completion_year', $data['completion_year'], PDO::PARAM_INT);
            $stmt->bindParam(':workload_hours', $workload, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Busca um curso pelo seu ID.
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
     * Atualiza um curso.
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data)
    {
        try {
            $workload = !empty($data['workload_hours']) ? $data['workload_hours'] : null;
            $sql = "UPDATE " . $this->table . " SET course_name = :course_name, course_institution = :course_institution, completion_year = :completion_year, workload_hours = :workload_hours WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':course_name', $data['course_name']);
            $stmt->bindParam(':course_institution', $data['course_institution']);
            $stmt->bindParam(':completion_year', $data['completion_year'], PDO::PARAM_INT);
            $stmt->bindParam(':workload_hours', $workload, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Exclui um curso.
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

    /**
     * Conta todos os cursos.
     * @return int
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
