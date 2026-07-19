<?php

class FLike
{
    private static string $table = "likes";
    private static string $value = "(:id_user, :id_project)";
    private static string $key = "id_user";  // chiave composta, gestita manualmente



    public static function getTable(): string
    {
        return self::$table;
    }
    public static function getValue(): string
    {
        return self::$value;
    }
    public static function getKey(): string
    {
        return self::$key;
    }
    public static function getClass(): string
    {
        return self::class;
    }


    // ---------CRUD---------
    // Like e Follow hanno chiave primaria COMPOSTA quindi create e delete sono gestiti manualmente


    // C
    public static function createObject(ELike $obj): bool
    {
        try {
            $pdo = FPersistentManager::getInstance()->getPdo();
            $stmt = $pdo->prepare(
                "INSERT INTO likes (id_user, id_project) 
                 VALUES (:id_user, :id_project)"
            );
            self::bind($stmt, $obj);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Errore FLike::createObject(): " . $e->getMessage());
            return false;
        }
    }

    // R 
    public static function retrieveObject(int $idUser, int $idProject): ?ELike
    {
        try {
            $pdo = FPersistentManager::getInstance()->getPdo();
            $stmt = $pdo->prepare(
                "SELECT * FROM likes 
                 WHERE id_user = :id_user AND id_project = :id_project"
            );
            $stmt->bindValue(':id_user', $idUser, PDO::PARAM_INT);
            $stmt->bindValue(':id_project', $idProject, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0)
                return self::createEntity($result);
            return null;
        } catch (PDOException $e) {
            error_log("Errore FLike::retrieveObject(): " . $e->getMessage());
            return null;
        }
    }

    // U - non applicabile per i like
    public static function updateObject(): void
    {
    }

    // D 
    public static function deleteObject(array $id): bool
    {
        try {
            $pdo = FPersistentManager::getInstance()->getPdo();
            $stmt = $pdo->prepare(
                "DELETE FROM likes 
                 WHERE id_user = :id_user AND id_project = :id_project"
            );
            $stmt->bindValue(':id_user', $id['id_user'], PDO::PARAM_INT);
            $stmt->bindValue(':id_project', $id['id_project'], PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Errore FLike::deleteObject(): " . $e->getMessage());
            return false;
        }
    }


    // ---------------QUERY---------------

    // Conta i like di un progetto
    public static function countByProject(int $idProject): int
    {
        try {
            $pdo = FPersistentManager::getInstance()->getPdo();
            $stmt = $pdo->prepare(
                "SELECT COUNT(*) as tot FROM likes WHERE id_project = :id_project"
            );
            $stmt->bindValue(':id_project', $idProject, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['tot'];
        } catch (PDOException $e) {
            error_log("Errore FLike::countByProject(): " . $e->getMessage());
            return 0;
        }
    }

    // Controlla se un utente ha già messo like a un progetto
    public static function exists(int $idUser, int $idProject): bool
    {
        return self::retrieveObject($idUser, $idProject) !== null;
    }


    // Costruisce uno o più ELike dal risultato della query
    public static function createEntity(array $queryResult): ELike|array
    {
        $likes = [];

        foreach ($queryResult as $result) {
            $like = new ELike(
                (int) $result['id_user'],
                (int) $result['id_project']
            );
            $likes[] = $like;
        }

        if (count($likes) === 1)
            return $likes[0];

        return $likes;
    }

    // Binding parametri PDO
    public static function bind($stmt, ELike $like): void
    {
        $stmt->bindValue(':id_user', $like->getIdUser(), PDO::PARAM_INT);
        $stmt->bindValue(':id_project', $like->getIdProject(), PDO::PARAM_INT);
    }
}
