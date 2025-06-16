<?php
// app/Models/SiteSettingsModel.php
namespace App\Models;

use App\Core\Database;
use PDO;

class SiteSettingsModel
{
    private $db;
    private $table = 'site_settings';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Busca o valor de uma configuração específica pela sua chave.
     * @param string $key A chave da configuração (ex: 'homepage_introduction_text').
     * @return string|null O valor da configuração ou null se não for encontrado.
     */
    public function getSetting(string $key)
    {
        try {
            $stmt = $this->db->prepare("SELECT setting_value FROM " . $this->table . " WHERE setting_key = :key");
            $stmt->bindParam(':key', $key);
            $stmt->execute();
            // fetchColumn() é perfeito para buscar um único valor de uma única coluna.
            $result = $stmt->fetchColumn();
            return $result !== false ? $result : null;
        } catch (\PDOException $e) {
            // error_log("Erro ao buscar configuração '$key': " . $e->getMessage());
            return null;
        }
    }

    /**
     * Atualiza ou cria uma configuração.
     * @param string $key A chave da configuração.
     * @param string $value O novo valor para a configuração.
     * @return bool True em sucesso, false em falha.
     */
    public function updateSetting(string $key, string $value): bool
    {
        try {
            // Tenta um UPDATE. Se não afetar nenhuma linha (porque a chave não existe), faz um INSERT.
            $sql = "UPDATE " . $this->table . " SET setting_value = :value WHERE setting_key = :key";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':key', $key);
            $stmt->bindParam(':value', $value);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return true; // UPDATE bem-sucedido
            } else {
                // Se o UPDATE não afetou linhas, a chave provavelmente não existe. Tenta inserir.
                $sql = "INSERT INTO " . $this->table . " (setting_key, setting_value) VALUES (:key, :value)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':key', $key);
                $stmt->bindParam(':value', $value);
                return $stmt->execute();
            }
        } catch (\PDOException $e) {
            // error_log("Erro ao atualizar configuração '$key': " . $e->getMessage());
            return false;
        }
    }
}
