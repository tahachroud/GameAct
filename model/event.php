<?php
class Event
{
    private ?int $id;
    private string $titre;
    private string $description;
    private string $lieu;
    private string $date;
    private string $statut;
    private string $heure_deb;
    private string $heure_fin;
    // NOUVEAU
    private ?float $latitude;
    private ?float $longitude;


    
    public function __construct(
        ?int $id = null,
        string $titre,
        string $description,
        string $lieu,
        string $date,
        string $statut,
        string $heure_deb,
        string $heure_fin,
        // NOUVEAU
        ?float $latitude = null,
        ?float $longitude = null
    ) {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->lieu = $lieu;
        $this->date = $date;
        $this->statut = $statut;
        $this->heure_deb = $heure_deb;
        $this->heure_fin = $heure_fin;
        // NOUVEAU
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
    $this->titre = $titre;
    return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getLieu(): string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;
        return $this;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getHeureDeb(): string
    {
        return $this->heure_deb;
    }

    public function setHeureDeb(string $heure_deb): self
    {
        $this->heure_deb = $heure_deb;
        return $this;
    }

    public function getHeureFin(): string
    {
        return $this->heure_fin;
    }

    public function setHeureFin(string $heure_fin): self
    {
        $this->heure_fin = $heure_fin;
        return $this;
    }

    // NOUVEAU: Getters et Setters pour la latitude
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    // NOUVEAU: Getters et Setters pour la longitude
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }
}