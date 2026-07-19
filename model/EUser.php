<?php

class EUser
{
    private int $id;
    private string $username;
    private string $email;
    private string $password;
    private string $ruolo;
    private string $dataReg;

    // Costruttore pieno
    public function __construct(
        int $id,
        string $username,
        string $email,
        string $password,
        string $ruolo = 'user',
        string $dataReg = ''
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->ruolo = $ruolo;
        $this->dataReg = $dataReg;
    }

    // --- Getter e Setter ---

    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRuolo(): string
    {
        return $this->ruolo;
    }
    public function setRuolo(string $ruolo): void
    {
        $this->ruolo = $ruolo;
    }

    public function getDataReg(): string
    {
        return $this->dataReg;
    }
    public function setDataReg(string $dataReg): void
    {
        $this->dataReg = $dataReg;
    }



    // Restituisce true se l'utente è admin
    public function isAdmin(): bool
    {
        return $this->ruolo === 'admin';
    }

    // Restituisce il nome visualizzabile dell'utente
    public function getDisplayUsername(): string
    {
        return $this->username;
    }
}
