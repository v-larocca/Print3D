<?php

class FProject
{
    private static string $table = "projects";
    private static string $value = "(:id, :titolo, :descrizione, :categoria, :data_upload, :id_autore)";
    private static string $key = "id";

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

    // C
    public static function createObject(EProject $obj): bool
    {
        $newId = FPersistentManager::getInstance()->create(self::class, $obj);
        if ($newId !== null) {
            $obj->setId($newId);
            return true;
        }
        return false;
    }

    // R
    public static function retrieveObject(int $id): ?EProject
    {
        $result = FPersistentManager::getInstance()
            ->retrieve(self::getTable(), self::getKey(), $id);
        if (count($result) > 0)
            return self::createEntity($result);
        return null;
    }

    // U
    public static function updateObject(EProject $obj, string $field, $value): bool
    {
        return FPersistentManager::getInstance()->update(
            self::getTable(),
            $field,
            $value,
            self::getKey(),
            $obj->getId()
        );
    }

    // D
    public static function deleteObject(int $id): bool
    {
        return FPersistentManager::getInstance()
            ->delete(self::getTable(), self::getKey(), $id);
    }


    // ---------QUERY---------

    // Tutti i progetti di un utente
    public static function retrieveByAutore(int $idAutore): array
    {
        try {
            $pdo = FPersistentManager::getInstance()->getPdo();
            $stmt = $pdo->prepare(
                "SELECT * FROM projects WHERE id_autore = :id_autore 
                 ORDER BY data_upload DESC"
            );
            $stmt->bindValue(':id_autore', $idAutore, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                $entities = self::createEntity($result);
                return is_array($entities) ? $entities : [$entities];
            }
            return [];
        } catch (PDOException $e) {
            error_log("Errore retrieveByAutore(): " . $e->getMessage());
            return [];
        }
    }

    // Progetti a cui un utente ha messo like
    public static function retrieveLikedByUser(int $idUser): array
    {
        try {
            $pdo = FPersistentManager::getInstance()->getPdo();
            $stmt = $pdo->prepare(
                "SELECT projects.* FROM projects
                 JOIN likes ON projects.id = likes.id_project
                 WHERE likes.id_user = :id_user
                 ORDER BY projects.data_upload DESC"
            );
            $stmt->bindValue(':id_user', $idUser, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                $entities = self::createEntity($result);
                return is_array($entities) ? $entities : [$entities];
            }
            return [];
        } catch (PDOException $e) {
            error_log("Errore retrieveLikedByUser(): " . $e->getMessage());
            return [];
        }
    }

    // Ricerca progetti per titolo con ordinamento
    public static function searchByTitolo(string $query, string $order = 'date'): array
    {
        try {
            $pdo = FPersistentManager::getInstance()->getPdo();

            // Sceglie l'ordinamento in base al parametro
            $orderClause = match ($order) {
                'likes' => "ORDER BY tot_likes DESC",
                'name' => "ORDER BY projects.titolo ASC",
                default => "ORDER BY projects.data_upload DESC"  // 'date'
            };

            $sql = "SELECT projects.*, COUNT(likes.id_project) as tot_likes
                    FROM projects
                    LEFT JOIN likes ON projects.id = likes.id_project
                    WHERE projects.titolo LIKE :query
                    GROUP BY projects.id
                    $orderClause";

            $stmt = $pdo->prepare($sql);
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
            error_log("Errore searchByTitolo(): " . $e->getMessage());
            return [];
        }
    }

    // Tutti i progetti
    public static function retrieveAll(string $order = 'date'): array
    {
        try {
            $pdo = FPersistentManager::getInstance()->getPdo();

            $orderClause = match ($order) {
                'likes' => "ORDER BY tot_likes DESC",
                'name' => "ORDER BY projects.titolo ASC",
                default => "ORDER BY projects.data_upload DESC"
            };

            $sql = "SELECT projects.*, COUNT(likes.id_project) as tot_likes
                    FROM projects
                    LEFT JOIN likes ON projects.id = likes.id_project
                    GROUP BY projects.id
                    $orderClause";

            $stmt = $pdo->prepare($sql);
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


    // Costruisce uno o più EProject dal risultato della query
    public static function createEntity(array $queryResult): EProject|array
    {
        $projects = [];

        foreach ($queryResult as $result) {

            // Converti data_upload in stringa prima di passarla al costruttore
            $dataUpload = ($result['data_upload'] !== null) ? (string) $result['data_upload'] : '';

            $project = new EProject(
                (int) $result['id'],
                $result['titolo'],
                $result['descrizione'],
                $result['categoria'],
                $dataUpload,           // ← già stringa garantita
                (int) $result['id_autore']
            );
            $projects[] = $project;
        }

        if (count($projects) === 1)
            return $projects[0];

        return $projects;
    }

    // Binding parametri PDO
    public static function bind($stmt, EProject $project): void
    {
        $stmt->bindValue(':id', null, PDO::PARAM_NULL);
        $stmt->bindValue(':titolo', $project->getTitolo(), PDO::PARAM_STR);
        $stmt->bindValue(':descrizione', $project->getDescrizione(), PDO::PARAM_STR);
        $stmt->bindValue(':categoria', $project->getCategoria(), PDO::PARAM_STR);
        $stmt->bindValue(':data_upload', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':id_autore', $project->getIdAutore(), PDO::PARAM_INT);
    }
}
