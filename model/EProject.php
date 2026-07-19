<?php

class EProject {
    private int    $id;
    private string $titolo;
    private string $descrizione;
    private string $categoria;
    private string $dataUpload;
    private int    $idAutore;

    // Costruttore pieno
    public function __construct(
        int    $id,
        string $titolo,
        string $descrizione,
        string $categoria,
        string $dataUpload = '',
        int    $idAutore   = 0
    ) {
        $this->id          = $id;
        $this->titolo      = $titolo;
        $this->descrizione = $descrizione;
        $this->categoria   = $categoria;
        $this->dataUpload  = $dataUpload;
        $this->idAutore    = $idAutore;
    }

    // --- Getter e Setter ---

    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getTitolo(): string { return $this->titolo; }
    public function setTitolo(string $titolo): void { $this->titolo = $titolo; }

    public function getDescrizione(): string { return $this->descrizione; }
    public function setDescrizione(string $descrizione): void { $this->descrizione = $descrizione; }

    public function getCategoria(): string { return $this->categoria; }
    public function setCategoria(string $categoria): void { $this->categoria = $categoria; }

    public function getDataUpload(): string { return $this->dataUpload; }
    public function setDataUpload(string $dataUpload): void { $this->dataUpload = $dataUpload; }

    public function getIdAutore(): int { return $this->idAutore; }
    public function setIdAutore(int $idAutore): void { $this->idAutore = $idAutore; }


    // Restituisce la descrizione troncata a N caratteri
    public function getDescrizioneBreve(int $lunghezza = 100): string {
        if (strlen($this->descrizione) <= $lunghezza)
            return $this->descrizione;
        return substr($this->descrizione, 0, $lunghezza) . '...';
    }
}
