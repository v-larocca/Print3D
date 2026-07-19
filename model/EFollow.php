<?php

class EFollow {
    private int $idFollower;
    private int $idFollowed;

    // Costruttore pieno
    public function __construct(
        int $idFollower,
        int $idFollowed
    ) {
        $this->idFollower = $idFollower;
        $this->idFollowed = $idFollowed;
    }

    // --- Getter e Setter ---

    public function getIdFollower(): int { return $this->idFollower; }
    public function setIdFollower(int $idFollower): void { $this->idFollower = $idFollower; }

    public function getIdFollowed(): int { return $this->idFollowed; }
    public function setIdFollowed(int $idFollowed): void { $this->idFollowed = $idFollowed; }

    // --- Metodi di comodo ---

    // EFollow non ha un id singolo ma una chiave composta
    // getId() restituisce un array con i due campi
    // usato da FPersistentManager::deleteObj()
    public function getId(): array {
        return [
            'id_follower' => $this->idFollower,
            'id_followed' => $this->idFollowed
        ];
    }
}
