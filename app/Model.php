<?php
abstract class Model {
    // Informations de la base de données (idéalement à stocker dans un fichier config.php)
    private $host = "localhost";
    private $db_name = "mvc";
    private $username = "root";
    private $password = "";
    
    // Instance de la connexion
    protected $_connexion;

    // Propriétés dynamiques
    public $table;
    public $id;

    /**
     * Initialise la connexion à la base de données
     */
    public function getConnection() {
        $this->_connexion = null;

        try {
            // Connexion avec options de sécurité et de performance
            $this->_connexion = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8", 
                $this->username, 
                $this->password, 
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Activer les exceptions
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Retourner un tableau associatif
                    PDO::ATTR_PERSISTENT => false // Désactiver la connexion persistante pour éviter certains bugs
                ]
            );
        } catch (PDOException $exception) {
            throw new Exception("Erreur de connexion : " . $exception->getMessage());
        }
    }

    /**
     * Récupère un enregistrement par son ID
     *
     * @return array|null
     */
    public function getOne() {
        if (!isset($this->id) || empty($this->table)) {
            throw new Exception("Erreur : ID ou table non défini.");
        }

        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $query = $this->_connexion->prepare($sql);
        $query->bindParam(':id', $this->id, PDO::PARAM_INT);
        $query->execute();

        return $query->fetch() ?: null;
    }

    /**
     * Récupère tous les enregistrements de la table
     *
     * @return array
     */
    public function getAll() {
        if (empty($this->table)) {
            throw new Exception("Erreur : Table non définie.");
        }

        $sql = "SELECT * FROM {$this->table}";
        $query = $this->_connexion->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }
}
