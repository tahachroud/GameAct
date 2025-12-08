<?php
class Participation
{
    private ?int $idP;
    private string $nomP;
    private string $emailP;
    private string $statutP;
    private string $remarqueP;
    private int $id;

    // Constructor
    public function __construct(
        ?int $idP = null,
        string $nomP,
        string $emailP,
        string $statutP,
        string $remarqueP,
        int $id
    ) {
        $this->idP = $idP;
        $this->nomP = $nomP;
        $this->emailP = $emailP;
        $this->statutP = $statutP;
        $this->remarqueP = $remarqueP;
        $this->id = $id;
    }

    // Getters and Setters
    public function getIdP(): ?int
    {
        return $this->idP;
    }

    public function setIdP(int $idP): self
    {
        $this->idP = $idP;
        return $this;
    }

    public function getNomP(): string
    {
        return $this->nomP;
    }

    public function setNomP(string $nomP): self
    {
        $this->nomP = $nomP;
        return $this;
    }

    public function getEmailP(): string
    {
        return $this->emailP;
    }

    public function setEmailP(string $emailP): self
    {
        $this->emailP = $emailP;
        return $this;
    }

    public function getStatutP(): string
    {
        return $this->statutP;
    }

    public function setStatutP(string $statutP): self
    {
        $this->statutP = $statutP;
        return $this;
    }

    public function getRemarqueP(): string
    {
        return $this->remarqueP;
    }

    public function setRemarqueP(string $remarqueP): self
    {
        $this->remarqueP = $remarqueP;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
}
?>
