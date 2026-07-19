<?php

class CFile
{

    // -------------------UPLOAD------------------------------------
    public static function uploadImmagine(array $file, int $idProject): bool
    {
        if (!self::validaFile($file, IMAGE_MAX_SIZE, IMAGE_ALLOWED_TYPES, IMAGE_ALLOWED_EXTENSIONS))
            return false;

        $dato = file_get_contents($file['tmp_name']);
        if ($dato === false)
            return false;

        $eFile = new EFile(0, $idProject, $file['name'], 'immagine', $dato);
        return FPersistentManager::createObj($eFile);
    }
    public static function uploadZip(array $file, int $idProject): bool
    {
        if (!self::validaFile($file, ZIP_MAX_SIZE, ZIP_ALLOWED_TYPES, ZIP_ALLOWED_EXTENSIONS))
            return false;

        if (self::isZipBomb($file['tmp_name']))
            return false;

        $dato = file_get_contents($file['tmp_name']);
        if ($dato === false)
            return false;

        $eFile = new EFile(0, $idProject, $file['name'], 'zip', $dato);
        return FPersistentManager::createObj($eFile);
    }


    // -------------------STREAM------------------------------------

    public static function streamImmagine(int $idProject): void
    {
        $file = FFile::retrieveImmagine($idProject);
        if (!$file) {
            http_response_code(404);
            exit;
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->buffer($file->getDato());

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . $file->getDimensione());
        echo $file->getDato();
        exit;
    }

    // -------------------DOWNLOAD------------------------------------

    public static function downloadZip(int $idProject): void
    {
        $file = FFile::retrieveZip($idProject);
        if (!$file)
            return;

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $file->getNomeFile() . '"');
        header('Content-Length: ' . $file->getDimensione());
        echo $file->getDato();
        exit;
    }

    // -------------------METODI PRIVATI------------------------------------

    // Validazione comune a immagini e zip
    private static function validaFile(array $file, int $maxSize, array $allowedTypes, array $allowedExtensions): bool
    {
        if (!is_uploaded_file($file['tmp_name']))
            return false;

        if ($file['size'] > $maxSize)
            return false;

        if (!in_array($file['type'], $allowedTypes))
            return false;

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExtensions))
            return false;

        return true;
    }

    // Controlla se un file zip è una zip bomb
    private static function isZipBomb(string $tmpPath): bool
    {
        $zip = new ZipArchive();

        if ($zip->open($tmpPath) !== true)
            return true;

        $totalUncompressed = 0;
        $compressedSize = filesize($tmpPath);

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $info = $zip->statIndex($i);
            $totalUncompressed += $info['size'];

            if ($totalUncompressed > ZIP_MAX_UNCOMPRESSED_SIZE) {
                $zip->close();
                return true;
            }

            if ($compressedSize > 0 && ($totalUncompressed / $compressedSize) > ZIP_MAX_COMPRESSION_RATIO) {
                $zip->close();
                return true;
            }
        }

        $zip->close();
        return false;
    }
}
