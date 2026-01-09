<?php
/**
 * Model Game - Gestion des jeux
 * Utilise PDO pour toutes les opérations CRUD
 */

class Game {
    
    // Propriétés privées
    private ?int $id = null;
    private ?string $title = null;
    private ?string $category = null;
    private ?string $description = null;
    private ?string $storyline = null;
    private ?float $price = null;
    private ?bool $is_free = null;
    private ?float $rating = null;
    private ?string $image_path = null;
    private ?string $trailer_path = null;
    private ?string $zip_file_path = null;
    private ?string $download_link = null;
    private ?string $date_added = null;
    private ?int $downloads = null;
    private ?int $likes = null;
    private ?int $downloads_7days = null;
    
    // ========================================
    // GETTERS
    // ========================================
    
    public function getId(): ?int {
        return $this->id;
    }
    
    public function getTitle(): ?string {
        return $this->title;
    }
    
    public function getCategory(): ?string {
        return $this->category;
    }
    
    public function getDescription(): ?string {
        return $this->description;
    }
    
    public function getStoryline(): ?string {
        return $this->storyline;
    }
    
    public function getPrice(): ?float {
        return $this->price;
    }
    
    public function getIsFree(): ?bool {
        return $this->is_free;
    }
    
    public function getRating(): ?float {
        return $this->rating;
    }
    
    public function getImagePath(): ?string {
        return $this->image_path;
    }
    
    public function getTrailerPath(): ?string {
        return $this->trailer_path;
    }
    
    public function getZipFilePath(): ?string {
        return $this->zip_file_path;
    }

    public function getDownloadLink(): ?string {
        return $this->download_link;
    }
    
    public function getDateAdded(): ?string {
        return $this->date_added;
    }
    
    public function getDownloads(): ?int {
        return $this->downloads;
    }
    
    public function getLikes(): ?int {
        return $this->likes;
    }
    
    public function getDownloads7days(): ?int {
        return $this->downloads_7days;
    }
    
    // ========================================
    // SETTERS
    // ========================================
    
    public function setId(?int $id): void {
        $this->id = $id;
    }
    
    public function setTitle(?string $title): void {
        $this->title = $title;
    }
    
    public function setCategory(?string $category): void {
        $this->category = $category;
    }
    
    public function setDescription(?string $description): void {
        $this->description = $description;
    }
    
    public function setStoryline(?string $storyline): void {
        $this->storyline = $storyline;
    }
    
    public function setPrice(?float $price): void {
        $this->price = $price;
    }
    
    public function setIsFree(?bool $is_free): void {
        $this->is_free = $is_free;
    }
    
    public function setRating(?float $rating): void {
        $this->rating = $rating;
    }
    
    public function setImagePath(?string $image_path): void {
        $this->image_path = $image_path;
    }
    
    public function setTrailerPath(?string $trailer_path): void {
        $this->trailer_path = $trailer_path;
    }
    
    public function setZipFilePath(?string $zip_file_path): void {
        $this->zip_file_path = $zip_file_path;
    }

    public function setDownloadLink(?string $download_link): void {
        $this->download_link = $download_link;
    }
    
    public function setDateAdded(?string $date_added): void {
        $this->date_added = $date_added;
    }
    
    public function setDownloads(?int $downloads): void {
        $this->downloads = $downloads;
    }
    
    public function setLikes(?int $likes): void {
        $this->likes = $likes;
    }
    
    public function setDownloads7days(?int $downloads_7days): void {
        $this->downloads_7days = $downloads_7days;
    }
    
    // ========================================
    // MÉTHODES CRUD - Utilisation de PDO
    // ========================================
    
    /**
     * Récupérer tous les jeux
     * @return array Liste de tous les jeux
     */
    public function getAllGames(): array {
        try {
            $pdo = config::getConnexion();
            $query = $pdo->query('SELECT * FROM games ORDER BY date_added DESC');
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Erreur lors de la récupération des jeux : ' . $e->getMessage());
        }
    }
    
    /**
     * Récupérer un jeu par son ID
     * @param int $id ID du jeu
     * @return array|null Données du jeu ou null
     */
    public function getGameById(int $id): ?array {
        try {
            $pdo = config::getConnexion();
            $query = $pdo->prepare('SELECT * FROM games WHERE id = :id');
            $query->execute(['id' => $id]);
            $result = $query->fetch();
            return $result ?: null;
        } catch (Exception $e) {
            die('Erreur lors de la récupération du jeu : ' . $e->getMessage());
        }
    }
    
    /**
     * Ajouter un nouveau jeu
     * @param Game $game Objet Game à ajouter
     * @return bool Succès de l'opération
     */
    public function addGame(Game $game): bool {
        try {
            $pdo = config::getConnexion();
            $query = $pdo->prepare('
                INSERT INTO games (title, category, description, storyline, price, is_free, 
                                 rating, image_path, trailer_path, download_link, date_added, 
                                 downloads, likes, downloads_7days)
                VALUES (:title, :category, :description, :storyline, :price, :is_free, 
                       :rating, :image_path, :trailer_path, :download_link, :date_added, 
                       :downloads, :likes, :downloads_7days)
            ');

            $query->execute([
                'title' => $game->title,
                'category' => $game->category,
                'description' => $game->description,
                'storyline' => $game->storyline,
                'price' => $game->price,
                'is_free' => $game->is_free ? 1 : 0,
                'rating' => $game->rating ?? 0.0,
                'image_path' => $game->image_path,
                'trailer_path' => $game->trailer_path,
                'download_link' => $game->download_link,
                'date_added' => $game->date_added ?? date('Y-m-d'),
                'downloads' => $game->downloads ?? 0,
                'likes' => $game->likes ?? 0,
                'downloads_7days' => $game->downloads_7days ?? 0
            ]);
            
            return true;
        } catch (Exception $e) {
            die('Erreur lors de l\'ajout du jeu : ' . $e->getMessage());
        }
    }
    
    /**
     * Mettre à jour un jeu existant
     * @param Game $game Objet Game avec les nouvelles données
     * @return bool Succès de l'opération
     */
    public function updateGame(Game $game): bool {
        try {
            $pdo = config::getConnexion();
            $query = $pdo->prepare('
                UPDATE games 
                SET title = :title,
                    category = :category,
                    description = :description,
                    storyline = :storyline,
                    price = :price,
                    is_free = :is_free,
                    rating = :rating,
                    image_path = :image_path,
                    trailer_path = :trailer_path,
                    download_link = :download_link
                WHERE id = :id
            ');

            $query->execute([
                'id' => $game->id,
                'title' => $game->title,
                'category' => $game->category,
                'description' => $game->description,
                'storyline' => $game->storyline,
                'price' => $game->price,
                'is_free' => $game->is_free ? 1 : 0,
                'rating' => $game->rating,
                'image_path' => $game->image_path,
                'trailer_path' => $game->trailer_path,
                'download_link' => $game->download_link
            ]);
            
            return true;
        } catch (Exception $e) {
            die('Erreur lors de la mise à jour du jeu : ' . $e->getMessage());
        }
    }
    
    /**
     * Supprimer un jeu
     * @param int $id ID du jeu à supprimer
     * @return bool Succès de l'opération
     */
    public function deleteGame(int $id): bool {
        try {
            $pdo = config::getConnexion();
            $query = $pdo->prepare('DELETE FROM games WHERE id = :id');
            $query->execute(['id' => $id]);
            return true;
        } catch (Exception $e) {
            die('Erreur lors de la suppression du jeu : ' . $e->getMessage());
        }
    }
    
    /**
     * Rechercher des jeux par titre ou catégorie
     * @param string $search Terme de recherche
     * @return array Liste des jeux correspondants
     */
    public function searchGames(string $search): array {
        try {
            $pdo = config::getConnexion();
            $query = $pdo->prepare('
                SELECT * FROM games 
                WHERE title LIKE :search 
                   OR category LIKE :search 
                ORDER BY rating DESC
            ');
            $query->execute(['search' => "%$search%"]);
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Erreur lors de la recherche : ' . $e->getMessage());
        }
    }
    
    /**
     * Récupérer les jeux par catégorie
     * @param string $category Catégorie
     * @return array Liste des jeux de cette catégorie
     */
    public function getGamesByCategory(string $category): array {
        try {
            $pdo = config::getConnexion();
            $query = $pdo->prepare('SELECT * FROM games WHERE category = :category ORDER BY rating DESC');
            $query->execute(['category' => $category]);
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Erreur lors de la récupération par catégorie : ' . $e->getMessage());
        }
    }
    
    /**
     * Récupérer les jeux gratuits
     * @return array Liste des jeux gratuits
     */
    public function getFreeGames(): array {
        try {
            $pdo = config::getConnexion();
            $query = $pdo->query('SELECT * FROM games WHERE is_free = 1 ORDER BY rating DESC');
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Erreur lors de la récupération des jeux gratuits : ' . $e->getMessage());
        }
    }
    
    /**
     * Récupérer les jeux les mieux notés
     * @param int $limit Nombre de jeux à récupérer
     * @return array Top jeux par note
     */
    public function getTopRatedGames(int $limit = 5): array {
        try {
            $pdo = config::getConnexion();
            $query = $pdo->prepare('SELECT * FROM games ORDER BY rating DESC LIMIT :limit');
            $query->bindValue(':limit', $limit, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Erreur lors de la récupération des meilleurs jeux : ' . $e->getMessage());
        }
    }
    
    /**
     * Récupérer les jeux les plus téléchargés (7 derniers jours)
     * @param int $limit Nombre de jeux à récupérer
     * @return array Top jeux par téléchargements
     */
    public function getTopDownloadedGames(int $limit = 5): array {
        try {
            $pdo = config::getConnexion();
            $query = $pdo->prepare('SELECT * FROM games ORDER BY downloads_7days DESC LIMIT :limit');
            $query->bindValue(':limit', $limit, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Erreur lors de la récupération des jeux populaires : ' . $e->getMessage());
        }
    }
    
    /**
     * Incrémenter le nombre de téléchargements
     * @param int $id ID du jeu
     * @return bool Succès de l'opération
     */
    public function incrementDownloads(int $id): bool {
        try {
            $pdo = config::getConnexion();
            $query = $pdo->prepare('
                UPDATE games 
                SET downloads = downloads + 1, 
                    downloads_7days = downloads_7days + 1 
                WHERE id = :id
            ');
            $query->execute(['id' => $id]);
            return true;
        } catch (Exception $e) {
            die('Erreur lors de l\'incrémentation des téléchargements : ' . $e->getMessage());
        }
    }
    
    /**
     * Incrémenter le nombre de likes
     * @param int $id ID du jeu
     * @return bool Succès de l'opération
     */
    public function incrementLikes(int $id): bool {
        try {
            $pdo = config::getConnexion();
            $query = $pdo->prepare('UPDATE games SET likes = likes + 1 WHERE id = :id');
            $query->execute(['id' => $id]);
            return true;
        } catch (Exception $e) {
            die('Erreur lors de l\'incrémentation des likes : ' . $e->getMessage());
        }
    }
    
    /**
     * Compter le nombre total de jeux
     * @return int Nombre total de jeux
     */
    public function getTotalGames(): int {
        try {
            $pdo = config::getConnexion();
            $query = $pdo->query('SELECT COUNT(*) as total FROM games');
            $result = $query->fetch();
            return (int)$result['total'];
        } catch (Exception $e) {
            die('Erreur lors du comptage des jeux : ' . $e->getMessage());
        }
    }
    
    /**
     * Calculer les statistiques globales
     * @return array Statistiques (total jeux, téléchargements, likes, note moyenne)
     */
    public function getGlobalStats(): array {
        try {
            $pdo = config::getConnexion();
            $query = $pdo->query('
                SELECT 
                    COUNT(*) as total_games,
                    SUM(downloads) as total_downloads,
                    SUM(likes) as total_likes,
                    AVG(rating) as avg_rating
                FROM games
            ');
            return $query->fetch();
        } catch (Exception $e) {
            die('Erreur lors de la récupération des statistiques : ' . $e->getMessage());
        }
    }
}
?>