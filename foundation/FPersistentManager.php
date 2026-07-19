<?php

class FPersistentManager
{
    private static ?FPersistentManager $instance = null;
    private PDO $pdo;


    public static function getInstance(): FPersistentManager
    {
        if (self::$instance === null)
            self::$instance = new FPersistentManager();
        return self::$instance;
    }

    private function __construct()
    {
        require_once __DIR__ . '/../config.php';
        try {
            $this->pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Connessione al database fallita: " . $e->getMessage());
        }
    }

    // Impedisce la clonazione del singleton
    private function __clone()
    {
    }

    // Getter della connessione PDO
    public function getPdo(): PDO
    {
        return $this->pdo;
    }


    // ---------CRUD---------

    // C
    // Riceve il nome della classe F e l'oggetto E
    // Restituisce l'id del record inserito oppure null
    public function create(string $fclass, $obj): ?int
    {
        try {
            $table = call_user_func([$fclass, "getTable"]);
            $value = call_user_func([$fclass, "getValue"]);
            $sql = "INSERT INTO $table VALUES $value";
            $stmt = $this->pdo->prepare($sql);

            // Delega il binding dei parametri alla classe F specifica
            call_user_func([$fclass, "bind"], $stmt, $obj);

            $stmt->execute();

            return (int) $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Errore create(): " . $e->getMessage());
            return null;
        }
    }

    // R
    public function retrieve(string $table, string $key, $id): array
    {
        try {
            $sql = "SELECT * FROM $table WHERE $key = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: [];
        } catch (PDOException $e) {
            error_log("Errore retrieve(): " . $e->getMessage());
            return [];
        }
    }

    // U 
    public function update(string $table, string $field, $value, string $key, $id): bool
    {
        try {
            $sql = "UPDATE $table SET $field = :value WHERE $key = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':value', $value);
            $stmt->bindValue(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Errore update(): " . $e->getMessage());
            return false;
        }
    }

    // D - DELETE
    public function delete(string $table, string $key, $id): bool
    {
        try {
            $sql = "DELETE FROM $table WHERE $key = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Errore delete(): " . $e->getMessage());
            return false;
        }
    }


    // Calcolano automaticamente la classe F dall'entità E
    private static function getFClass($obj): string
    {
        return "F" . substr(get_class($obj), 1);
    }

    public static function createObj($obj): bool
    {
        $fclass = self::getFClass($obj);
        return call_user_func([$fclass, "createObject"], $obj);
    }

    public static function retrieveObj(string $eclass, $id)
    {
        $fclass = "F" . substr($eclass, 1);
        return call_user_func([$fclass, "retrieveObject"], $id);
    }

    public static function updateObj($obj, string $field, $value): bool
    {
        $fclass = self::getFClass($obj);
        return call_user_func([$fclass, "updateObject"], $obj, $field, $value);
    }

    public static function deleteObj($obj): bool
    {
        $fclass = self::getFClass($obj);
        return call_user_func([$fclass, "deleteObject"], $obj->getId());
    }
}
