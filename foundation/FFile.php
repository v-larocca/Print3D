<?php

class FFile
{
    private static string $table = "files";
    private static string $value = "(:id, :id_project, :nome_file, :tipo, :dato)";
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
    public static function createObject(EFile $obj): bool
    {
        $newId = FPersistentManager::getInstance()->create(self::class, $obj);
        if ($newId !== null) {
            $obj->setId($newId);
            return true;
        }
        return false;
    }

    // R 
    public static function retrieveObject(int $id): ?EFile
    {
        $result = FPersistentManager::getInstance()
            ->retrieve(self::getTable(), self::getKey(), $id);
        if (count($result) > 0)
            return self::createEntity($result);
        return null;
    }

    // U 
    public static function updateObject(EFile $obj, string $field, $value): bool
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

    // Recupera l'immagine di anteprima di un progetto
    public static function retrieveImmagine(int $idProject): ?EFile
    {
        try {
            $pdo = FPersistentManager::getInstance()->getPdo();
            $stmt = $pdo->prepare(
                "SELECT * FROM files 
                 WHERE id_project = :id_project AND tipo = 'immagine' 
                 LIMIT 1"
            );
            $stmt->bindValue(':id_project', $idProject, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0)
                return self::createEntity($result);
            return null;
        } catch (PDOException $e) {
            error_log("Errore retrieveImmagine(): " . $e->getMessage());
            return null;
        }
    }

    // Recupera il file zip di un progetto
    public static function retrieveZip(int $idProject): ?EFile
    {
        try {
            $pdo = FPersistentManager::getInstance()->getPdo();
            $stmt = $pdo->prepare(
                "SELECT * FROM files 
                 WHERE id_project = :id_project AND tipo = 'zip' 
                 LIMIT 1"
            );
            $stmt->bindValue(':id_project', $idProject, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0)
                return self::createEntity($result);
            return null;
        } catch (PDOException $e) {
            error_log("Errore retrieveZip(): " . $e->getMessage());
            return null;
        }
    }

    // Elimina tutti i file di un progetto
    public static function deleteByProject(int $idProject): bool
    {
        try {
            $pdo = FPersistentManager::getInstance()->getPdo();
            $stmt = $pdo->prepare(
                "DELETE FROM files WHERE id_project = :id_project"
            );
            $stmt->bindValue(':id_project', $idProject, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Errore deleteByProject(): " . $e->getMessage());
            return false;
        }
    }


    // Costruisce uno o più EFile dal risultato della query
    // Gestisce anche il caso in cui PDO restituisce il blob come stream
    public static function createEntity(array $queryResult): EFile|array
    {
        $files = [];

        foreach ($queryResult as $result) {
            $dato = $result['dato'];      // PDO può restituire blob grandi come stream PHP
            if (is_resource($dato))
                $dato = stream_get_contents($dato);

            $file = new EFile(
                (int) $result['id'],
                (int) $result['id_project'],
                $result['nome_file'],
                $result['tipo'],
                $dato
            );
            $files[] = $file;
        }

        // Singolo file → oggetto, più file → array
        if (count($files) === 1)
            return $files[0];

        return $files;
    }

    // Binding parametri PDO
    public static function bind($stmt, EFile $file): void
    {
        $stmt->bindValue(':id', null, PDO::PARAM_NULL);
        $stmt->bindValue(':id_project', $file->getIdProject(), PDO::PARAM_INT);
        $stmt->bindValue(':nome_file', $file->getNomeFile(), PDO::PARAM_STR);
        $stmt->bindValue(':tipo', $file->getTipo(), PDO::PARAM_STR);
        $stmt->bindValue(':dato', $file->getDato(), PDO::PARAM_LOB);
    }
}
