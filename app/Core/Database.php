<?php
// app/Core/Database.php
namespace App\Core;

use PDO; // Importa a classe PDO global
use PDOException; // Importa a classe PDOException global

class Database {
    private static ?Database $instance = null; // Atributo para o Singleton
    private PDO $connection; // O objeto de conexão PDO

    // Detalhes da conexão (serão carregados do config)
    private string $host;
    private string $db_name;
    private string $username;
    private string $password;
    private string $charset;
    private array $options;

    // Construtor privado para evitar instanciação direta (Singleton)
    private function __construct() {
        $configPath = __DIR__ . '/../../config/database.php'; // Caminho de app/Core para config/

        if (!file_exists($configPath)) {
            // Para um app real, trate isso com uma exceção mais específica ou um log.
            die("Erro Crítico: Arquivo de configuração do banco de dados não encontrado em: " . $configPath);
        }
        $config = require $configPath;

        $this->host = $config['host'];
        $this->db_name = $config['database'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->charset = $config['charset'] ?? 'utf8mb4'; // Usa utf8mb4 como padrão
        $this->options = $config['options'] ?? [ // Opções PDO padrão
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=' . $this->charset;

        try {
            $this->connection = new PDO($dsn, $this->username, $this->password, $this->options);
        } catch (PDOException $e) {
            // Para desenvolvimento, 'die' mostra o erro. Em produção, logue o erro e mostre uma mensagem amigável.
            // error_log('Erro de Conexão com BD: ' . $e->getMessage());
            die('Erro de Conexão com o Banco de Dados. Por favor, tente mais tarde.');
            // Ou, mais detalhado para debug local: die('Erro de Conexão: ' . $e->getMessage());
        }
    }

    /**
     * Método estático para obter a instância Singleton da classe Database.
     */
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self(); // Cria a instância se ela ainda não existir
        }
        return self::$instance;
    }

    /**
     * Retorna o objeto de conexão PDO ativo.
     */
    public function getConnection(): PDO {
        return $this->connection;
    }
}
?>