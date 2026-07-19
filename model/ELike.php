<?php

class ELike {
    private int $idUser;
    private int $idProject;

    // Costruttore pieno
    public function __construct(
        int $idUser,
        int $idProject
    ) {
        $this->idUser    = $idUser;
        $this->idProject = $idProject;
    }

    // --- Getter e Setter ---

    public function getIdUser(): int { return $this->idUser; }
    public function setIdUser(int $idUser): void { $this->idUser = $idUser; }

    public function getIdProject(): int { return $this->idProject; }
    public function setIdProject(int $idProject): void { $this->idProject = $idProject; }

    // --- Metodi di comodo ---

    // ELike non ha un id singolo ma una chiave composta
    // getId() restituisce un array con i due campi
    // usato da FPersistentManager::deleteObj()
    public function getId(): array {
        return [
            'id_user'    => $this->idUser,
            'id_project' => $this->idProject
        ];
    }
}
