<?php

class FUser {
    private static string $table = "users";
    private static string $value = "(:id, :username, :email, :password, :ruolo, :data_reg)";
    private static string $key   = "id";

    // --- Metodi descrittori della tabella ---

    public static function getTable(): string { return self::$table; }
    public static function getValue(): string { return self::$value; }
    public static function getKey(): string   { return self::$key; }
    public static function getClass(): string { return self::class; }

    // ================================================
    // CRUD
    // ================================================

    // C - INSERT
    public static function createObject(EUser $obj): bool {
        $newId = FPersistentManager::getInstance()->create(self::class, $obj);
        if ($newId !== null) {
            $obj->setId($newId);
            return true;
        }
        return false;
    }

    // R - SELECT per id
    public static function retrieveObject(int $id): ?EUser {
        $result = FPersistentManager::getInstance()
            ->retrieve(self::getTable(), self::getKey(), $id);
        if (count($result) > 0)
            return self::createEntity($result);
        return null;
    }

    // U - UPDATE
    public static function updateObject(EUser $obj, string $field, $value): bool {
        return FPersistentManager::getInstance()->update(
            self::getTable(), $field, $value, self::getKey(), $obj->getId()
        );
    }

    // D - DELETE
    public static function deleteObject(int $id): bool {
        return FPersistentManager::getInstance()
            ->delete(self::getTable(), self::getKey(), $id);
    }

    // ================================================
    // QUERY AGGIUNTIVE
    // ================================================

    // Recupera un utente tramite email — usato nel login
    public static function retrieveByEmail(string $email): ?EUser {
        try {
            $pdo  = FPersistentManager::getInstance()->getPdo();
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0)
                return self::createEntity($result);
            return null;
        } catch (PDOException $e) {
            error_log("Errore retrieveByEmail(): " . $e->getMessage());
            return null;
        }
    }

    // Recupera un utente tramite username — usato nel login e verifica
    public static function retrieveByUsername(string $username): ?EUser {
        try {
            $pdo  = FPersistentManager::getInstance()->getPdo();
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0)
                return self::createEntity($result);
            return null;
        } catch (PDOException $e) {
            error_log("Errore retrieveByUsername(): " . $e->getMessage());
            return null;
        }
    }

    // Cerca utenti per username — usato nella ricerca, ordinati alfabeticamente
    public static function searchByUsername(string $query): array {
        try {
            $pdo  = FPersistentManager::getInstance()->getPdo();
            $stmt = $pdo->prepare(
                "SELECT * FROM users WHERE username LIKE :query ORDER BY username ASC"
            );
            $like = '%' . $query . '%';
            $stmt->bindValue(':query', $like, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                $entities = self::createEntity($result);
                return is_array($entities) ? $entities : [$entities];
            }
            return [];
        } catch (PDOException $e) {
            error_log("Errore searchByUsername(): " . $e->getMessage());
            return [];
        }
    }

    // Recupera tutti gli utenti, ordinati alfabeticamente
    // Usato dal pannello admin e dalla ricerca utenti con query vuota
    public static function retrieveAll(): array {
        try {
            $pdo  = FPersistentManager::getInstance()->getPdo();
            $stmt = $pdo->prepare("SELECT * FROM users ORDER BY username ASC");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                $entities = self::createEntity($result);
                return is_array($entities) ? $entities : [$entities];
            }
            return [];
        } catch (PDOException $e) {
            error_log("Errore retrieveAll(): " . $e->getMessage());
            return [];
        }
    }

    
    // METODI DI SUPPORTO
    // ================================================

    // Costruisce uno o più EUser dal risultato della query
    public static function createEntity(array $queryResult): EUser|array {
        $users = [];

        foreach ($queryResult as $result) {
            $dataReg = ($result['data_reg'] !== null) ? (string) $result['data_reg'] : '';
            $user = new EUser(
                (int)    $result['id'],
                         $result['username'],
                         $result['email'],
                         $result['password'],
                         $result['ruolo'],
                         $dataReg
            );
            $users[] = $user;
        }

        if (count($users) === 1)
            return $users[0];

        return $users;
    }

    // Binding parametri PDO
    public static function bind($stmt, EUser $user): void {
        $stmt->bindValue(':id',       null,                    PDO::PARAM_NULL);
        $stmt->bindValue(':username', $user->getUsername(),    PDO::PARAM_STR);
        $stmt->bindValue(':email',    $user->getEmail(),       PDO::PARAM_STR);
        $stmt->bindValue(':password', $user->getPassword(),    PDO::PARAM_STR);
        $stmt->bindValue(':ruolo',    $user->getRuolo(),       PDO::PARAM_STR);
        $stmt->bindValue(':data_reg', null,                    PDO::PARAM_NULL);
    }
}
