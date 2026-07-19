<?php

class EFile {
    private int    $id;
    private int    $idProject;
    private string $nomeFile;
    private string $tipo;      // 'zip' o 'immagine'
    private string $dato;      // contenuto binario (blob)

    // Costruttore pieno
    public function __construct(
        int    $id,
        int    $idProject,
        string $nomeFile,
        string $tipo,
        string $dato = ''
    ) {
        $this->id        = $id;
        $this->idProject = $idProject;
        $this->nomeFile  = $nomeFile;
        $this->tipo      = $tipo;
        $this->dato      = $dato;
    }

    // --- Getter e Setter ---

    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getIdProject(): int { return $this->idProject; }
    public function setIdProject(int $idProject): void { $this->idProject = $idProject; }

    public function getNomeFile(): string { return $this->nomeFile; }
    public function setNomeFile(string $nomeFile): void { $this->nomeFile = $nomeFile; }

    public function getTipo(): string { return $this->tipo; }
    public function setTipo(string $tipo): void { $this->tipo = $tipo; }

    public function getDato(): string { return $this->dato; }
    public function setDato(string $dato): void { $this->dato = $dato; }

    // --- Metodi di comodo ---

    // Restituisce true se il file è uno zip
    public function isZip(): bool {
        return $this->tipo === 'zip';
    }

    // Restituisce true se il file è un'immagine
    public function isImmagine(): bool {
        return $this->tipo === 'immagine';
    }

    // Restituisce la dimensione del blob in byte
    public function getDimensione(): int {
        return strlen($this->dato);
    }
}
