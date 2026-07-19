<?php

class FFollow {
    private static string $table = "follows";
    private static string $value = "(:id_follower, :id_followed)";
    private static string $key   = "id_follower";  // chiave composta, gestita manualmente

  

    public static function getTable(): string { return self::$table; }
    public static function getValue(): string { return self::$value; }
    public static function getKey(): string   { return self::$key; }
    public static function getClass(): string { return self::class; }

    
    // ------------------CRUD----------------------
    

    // C 
    public static function createObject(EFollow $obj): bool {
        try {
            $pdo  = FPersistentManager::getInstance()->getPdo();
            $stmt = $pdo->prepare(
                "INSERT INTO follows (id_follower, id_followed) 
                 VALUES (:id_follower, :id_followed)"
            );
            self::bind($stmt, $obj);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Errore FFollow::createObject(): " . $e->getMessage());
            return false;
        }
    }

    // R 
    public static function retrieveObject(int $idFollower, int $idFollowed): ?EFollow {
        try {
            $pdo  = FPersistentManager::getInstance()->getPdo();
            $stmt = $pdo->prepare(
                "SELECT * FROM follows 
                 WHERE id_follower = :id_follower AND id_followed = :id_followed"
            );
            $stmt->bindValue(':id_follower', $idFollower, PDO::PARAM_INT);
            $stmt->bindValue(':id_followed', $idFollowed, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0)
                return self::createEntity($result);
            return null;
        } catch (PDOException $e) {
            error_log("Errore FFollow::retrieveObject(): " . $e->getMessage());
            return null;
        }
    }

    // U - non applicabile per i follow
    public static function updateObject(): void {}

    // D 
    public static function deleteObject(array $id): bool {
        try {
            $pdo  = FPersistentManager::getInstance()->getPdo();
            $stmt = $pdo->prepare(
                "DELETE FROM follows 
                 WHERE id_follower = :id_follower AND id_followed = :id_followed"
            );
            $stmt->bindValue(':id_follower', $id['id_follower'], PDO::PARAM_INT);
            $stmt->bindValue(':id_followed', $id['id_followed'], PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Errore FFollow::deleteObject(): " . $e->getMessage());
            return false;
        }
    }

    
    // -----------------QUERY AGGIUNTIVE------------------
    

    // Lista dei follower di un utente
    public static function retrieveFollowers(int $idFollowed): array {
        try {
            $pdo  = FPersistentManager::getInstance()->getPdo();
            $stmt = $pdo->prepare(
                "SELECT * FROM follows WHERE id_followed = :id_followed"
            );
            $stmt->bindValue(':id_followed', $idFollowed, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                $entities = self::createEntity($result);
                return is_array($entities) ? $entities : [$entities];
            }
            return [];
        } catch (PDOException $e) {
            error_log("Errore FFollow::retrieveFollowers(): " . $e->getMessage());
            return [];
        }
    }

    // Lista degli utenti che un utente segue
    public static function retrieveFollowing(int $idFollower): array {
        try {
            $pdo  = FPersistentManager::getInstance()->getPdo();
            $stmt = $pdo->prepare(
                "SELECT * FROM follows WHERE id_follower = :id_follower"
            );
            $stmt->bindValue(':id_follower', $idFollower, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                $entities = self::createEntity($result);
                return is_array($entities) ? $entities : [$entities];
            }
            return [];
        } catch (PDOException $e) {
            error_log("Errore FFollow::retrieveFollowing(): " . $e->getMessage());
            return [];
        }
    }

    // Conta i follower di un utente
    public static function countFollowers(int $idFollowed): int {
        try {
            $pdo  = FPersistentManager::getInstance()->getPdo();
            $stmt = $pdo->prepare(
                "SELECT COUNT(*) as tot FROM follows WHERE id_followed = :id_followed"
            );
            $stmt->bindValue(':id_followed', $idFollowed, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['tot'];
        } catch (PDOException $e) {
            error_log("Errore FFollow::countFollowers(): " . $e->getMessage());
            return 0;
        }
    }

    // Conta gli utenti che un utente segue
    public static function countFollowing(int $idFollower): int {
        try {
            $pdo  = FPersistentManager::getInstance()->getPdo();
            $stmt = $pdo->prepare(
                "SELECT COUNT(*) as tot FROM follows WHERE id_follower = :id_follower"
            );
            $stmt->bindValue(':id_follower', $idFollower, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['tot'];
        } catch (PDOException $e) {
            error_log("Errore FFollow::countFollowing(): " . $e->getMessage());
            return 0;
        }
    }

    // Controlla se un utente segue già un altro utente
    public static function exists(int $idFollower, int $idFollowed): bool {
        return self::retrieveObject($idFollower, $idFollowed) !== null;
    }

    
    // -----------------METODI DI SUPPORTO----------------------
    

    // Costruisce uno o più EFollow dal risultato della query
    public static function createEntity(array $queryResult): EFollow|array {
        $follows = [];

        foreach ($queryResult as $result) {
            $follow = new EFollow(
                (int) $result['id_follower'],
                (int) $result['id_followed']
            );
            $follows[] = $follow;
        }

        if (count($follows) === 1)
            return $follows[0];

        return $follows;
    }

    // Binding parametri PDO
    public static function bind($stmt, EFollow $follow): void {
        $stmt->bindValue(':id_follower', $follow->getIdFollower(), PDO::PARAM_INT);
        $stmt->bindValue(':id_followed', $follow->getIdFollowed(), PDO::PARAM_INT);
    }
}
