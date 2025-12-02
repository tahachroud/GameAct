<?php
/**
 * Model Cart - Gestion du panier
 * Utilise PDO pour toutes les opérations CRUD
 */

class Cart {
    
    // Propriétés privées
    private ?int $id = null;
    private ?int $user_id = null;
    private ?int $game_id = null;
    private ?int $quantity = null;
    private ?string $added_at = null;
    
    // ========================================
    // GETTERS
    // ========================================
    
    public function getId(): ?int {
        return $this->id;
    }
    
    public function getUserId(): ?int {
        return $this->user_id;
    }
    
    public function getGameId(): ?int {
        return $this->game_id;
    }
    
    public function getQuantity(): ?int {
        return $this->quantity;
    }
    
    public function getAddedAt(): ?string {
        return $this->added_at;
    }
    
    // ========================================
    // SETTERS
    // ========================================
    
    public function setId(?int $id): void {
        $this->id = $id;
    }
    
    public function setUserId(?int $user_id): void {
        $this->user_id = $user_id;
    }
    
    public function setGameId(?int $game_id): void {
        $this->game_id = $game_id;
    }
    
    public function setQuantity(?int $quantity): void {
        $this->quantity = $quantity;
    }
    
    public function setAddedAt(?string $added_at): void {
        $this->added_at = $added_at;
    }
    
    // ========================================
    // MÉTHODES CRUD - Utilisation de PDO
    // ========================================
    
    /**
     * Récupérer tous les articles du panier d'un utilisateur
     * @param int $userId ID de l'utilisateur
     * @return array Liste des articles avec les détails des jeux
     */
    public function getCartByUserId(int $userId): array {
        try {
            $pdo = config::getConnexion();
            $query = $pdo->prepare('
                SELECT 
                    c.id,
                    c.user_id,
                    c.game_id,
                    c.quantity,
                    c.added_at,
                    g.title,
                    g.category,
                    g.price,
                    g.is_free,
                    g.image_path,
                    g.rating,
                    (g.price * c.quantity) as subtotal
                FROM cart c
                INNER JOIN games g ON c.game_id = g.id
                WHERE c.user_id = :user_id
                ORDER BY c.added_at DESC
            ');
            $query->execute(['user_id' => $userId]);
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Erreur lors de la récupération du panier : ' . $e->getMessage());
        }
    }
    
    /**
     * Ajouter un article au panier
     * @param int $userId ID de l'utilisateur
     * @param int $gameId ID du jeu
     * @param int $quantity Quantité (par défaut 1)
     * @return bool Succès de l'opération
     */
    public function addToCart(int $userId, int $gameId, int $quantity = 1): bool {
        try {
            $pdo = config::getConnexion();
            
            // Vérifier si le jeu existe déjà dans le panier
            $checkQuery = $pdo->prepare('
                SELECT id, quantity 
                FROM cart 
                WHERE user_id = :user_id AND game_id = :game_id
            ');
            $checkQuery->execute([
                'user_id' => $userId,
                'game_id' => $gameId
            ]);
            $existing = $checkQuery->fetch();
            
            if ($existing) {
                // Mettre à jour la quantité
                $updateQuery = $pdo->prepare('
                    UPDATE cart 
                    SET quantity = quantity + :quantity 
                    WHERE id = :id
                ');
                $updateQuery->execute([
                    'quantity' => $quantity,
                    'id' => $existing['id']
                ]);
            } else {
                // Ajouter un nouvel article
                $insertQuery = $pdo->prepare('
                    INSERT INTO cart (user_id, game_id, quantity, added_at)
                    VALUES (:user_id, :game_id, :quantity, NOW())
                ');
                $insertQuery->execute([
                    'user_id' => $userId,
                    'game_id' => $gameId,
                    'quantity' => $quantity
                ]);
            }
            
            return true;
        } catch (Exception $e) {
            die('Erreur lors de l\'ajout au panier : ' . $e->getMessage());
        }
    }
    
    /**
     * Mettre à jour la quantité d'un article dans le panier
     * @param int $cartId ID de l'article dans le panier
     * @param int $quantity Nouvelle quantité
     * @return bool Succès de l'opération
     */
    public function updateQuantity(int $cartId, int $quantity): bool {
        try {
            $pdo = config::getConnexion();
            
            if ($quantity <= 0) {
                // Si la quantité est 0 ou moins, supprimer l'article
                return $this->removeFromCart($cartId);
            }
            
            $query = $pdo->prepare('
                UPDATE cart 
                SET quantity = :quantity 
                WHERE id = :id
            ');
            $query->execute([
                'quantity' => $quantity,
                'id' => $cartId
            ]);
            
            return true;
        } catch (Exception $e) {
            die('Erreur lors de la mise à jour de la quantité : ' . $e->getMessage());
        }
    }
    
    /**
     * Supprimer un article du panier
     * @param int $cartId ID de l'article dans le panier
     * @return bool Succès de l'opération
     */
    public function removeFromCart(int $cartId): bool {
        try {
            $pdo = config::getConnexion();
            $query = $pdo->prepare('DELETE FROM cart WHERE id = :id');
            $query->execute(['id' => $cartId]);
            return true;
        } catch (Exception $e) {
            die('Erreur lors de la suppression de l\'article : ' . $e->getMessage());
        }
    }
    
    /**
     * Vider tout le panier d'un utilisateur
     * @param int $userId ID de l'utilisateur
     * @return bool Succès de l'opération
     */
    public function clearCart(int $userId): bool {
        try {
            $pdo = config::getConnexion();
            $query = $pdo->prepare('DELETE FROM cart WHERE user_id = :user_id');
            $query->execute(['user_id' => $userId]);
            return true;
        } catch (Exception $e) {
            die('Erreur lors du vidage du panier : ' . $e->getMessage());
        }
    }
    
    /**
     * Calculer le total du panier (sans réduction)
     * @param int $userId ID de l'utilisateur
     * @return float Total du panier
     */
    public function getCartTotal(int $userId): float {
        try {
            $pdo = config::getConnexion();
            $query = $pdo->prepare('
                SELECT SUM(g.price * c.quantity) as total
                FROM cart c
                INNER JOIN games g ON c.game_id = g.id
                WHERE c.user_id = :user_id AND g.is_free = 0
            ');
            $query->execute(['user_id' => $userId]);
            $result = $query->fetch();
            return $result['total'] ? (float)$result['total'] : 0.0;
        } catch (Exception $e) {
            die('Erreur lors du calcul du total : ' . $e->getMessage());
        }
    }
    
    /**
     * Compter le nombre d'articles dans le panier
     * @param int $userId ID de l'utilisateur
     * @return int Nombre d'articles
     */
    public function getCartItemCount(int $userId): int {
        try {
            $pdo = config::getConnexion();
            $query = $pdo->prepare('
                SELECT SUM(quantity) as count
                FROM cart
                WHERE user_id = :user_id
            ');
            $query->execute(['user_id' => $userId]);
            $result = $query->fetch();
            return $result['count'] ? (int)$result['count'] : 0;
        } catch (Exception $e) {
            die('Erreur lors du comptage des articles : ' . $e->getMessage());
        }
    }
    
    /**
     * Vérifier si un jeu est déjà dans le panier
     * @param int $userId ID de l'utilisateur
     * @param int $gameId ID du jeu
     * @return bool True si le jeu est dans le panier
     */
    public function isGameInCart(int $userId, int $gameId): bool {
        try {
            $pdo = config::getConnexion();
            $query = $pdo->prepare('
                SELECT COUNT(*) as count
                FROM cart
                WHERE user_id = :user_id AND game_id = :game_id
            ');
            $query->execute([
                'user_id' => $userId,
                'game_id' => $gameId
            ]);
            $result = $query->fetch();
            return $result['count'] > 0;
        } catch (Exception $e) {
            die('Erreur lors de la vérification : ' . $e->getMessage());
        }
    }
    
    // ========================================
    // MÉTHODES POUR LES CODES PROMO
    // ========================================
    
    /**
     * Vérifier et appliquer un code promo
     * @param string $promoCode Code promo à vérifier
     * @return array|null Données du code promo ou null si invalide
     */
    public function validatePromoCode(string $promoCode): ?array {
        // Pour l'instant, codes promo en dur (tu peux créer une table promo_codes plus tard)
        $promoCodes = [
            'WELCOME10' => ['discount' => 10, 'type' => 'percentage', 'description' => '10% de réduction'],
            'SUMMER20' => ['discount' => 20, 'type' => 'percentage', 'description' => '20% de réduction'],
            'SAVE5' => ['discount' => 5, 'type' => 'fixed', 'description' => '5$ de réduction'],
            'FREESHIP' => ['discount' => 0, 'type' => 'shipping', 'description' => 'Livraison gratuite'],
            'GAMEACT50' => ['discount' => 50, 'type' => 'percentage', 'description' => '50% de réduction - Offre spéciale !']
        ];
        
        $code = strtoupper(trim($promoCode));
        
        if (isset($promoCodes[$code])) {
            return [
                'code' => $code,
                'discount' => $promoCodes[$code]['discount'],
                'type' => $promoCodes[$code]['type'],
                'description' => $promoCodes[$code]['description']
            ];
        }
        
        return null;
    }
    
    /**
     * Calculer le montant de la réduction
     * @param float $subtotal Sous-total du panier
     * @param array $promoData Données du code promo
     * @return float Montant de la réduction
     */
    public function calculateDiscount(float $subtotal, array $promoData): float {
        if ($promoData['type'] === 'percentage') {
            return ($subtotal * $promoData['discount']) / 100;
        } elseif ($promoData['type'] === 'fixed') {
            return min($promoData['discount'], $subtotal); // Ne pas dépasser le sous-total
        }
        
        return 0.0;
    }
    
    /**
     * Obtenir un résumé complet du panier avec réduction
     * @param int $userId ID de l'utilisateur
     * @param string|null $promoCode Code promo (optionnel)
     * @return array Résumé du panier
     */
    public function getCartSummary(int $userId, ?string $promoCode = null): array {
        $subtotal = $this->getCartTotal($userId);
        $discount = 0.0;
        $promoData = null;
        
        if ($promoCode) {
            $promoData = $this->validatePromoCode($promoCode);
            if ($promoData) {
                $discount = $this->calculateDiscount($subtotal, $promoData);
            }
        }
        
        $total = max(0, $subtotal - $discount);
        
        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'promo_code' => $promoData ? $promoData['code'] : null,
            'promo_description' => $promoData ? $promoData['description'] : null,
            'item_count' => $this->getCartItemCount($userId)
        ];
    }
}
?>