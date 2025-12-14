<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../model/Game.php';

class GameController {
    
    private Game $gameModel;
    private Cart $cartModel;

    public function __construct() {
        $this->gameModel = new Game();
    }

    private function initCartModel(): void {
        if (!isset($this->cartModel)) {
            require_once __DIR__ . '/../model/Cart.php';
            $this->cartModel = new Cart();
        }
    }
    

    public function getAllGames(): array {
        return $this->gameModel->getAllGames();
    }

    public function getGameById(int $id): ?array {
        return $this->gameModel->getGameById($id);
    }

    public function getTopRatedGames(int $limit = 5): array {
        return $this->gameModel->getTopRatedGames($limit);
    }

    public function getTopDownloadedGames(int $limit = 5): array {
        return $this->gameModel->getTopDownloadedGames($limit);
    }

    public function searchGames(string $search): array {
        return $this->gameModel->searchGames($search);
    }
    
    public function getGamesByCategory(string $category): array {
        return $this->gameModel->getGamesByCategory($category);
    }

    public function getFreeGames(): array {
        return $this->gameModel->getFreeGames();
    }
    
    public function getGlobalStats(): array {
        return $this->gameModel->getGlobalStats();
    }
    
    public function incrementDownloads(int $id): bool {
        return $this->gameModel->incrementDownloads($id);
    }
    
    public function incrementLikes(int $id): bool {
        return $this->gameModel->incrementLikes($id);
    }

    public function addGame(Game $game): bool {
        return $this->gameModel->addGame($game);
    }

    public function updateGame(Game $game): bool {
        return $this->gameModel->updateGame($game);
    }

    public function deleteGame(int $id): bool {
        return $this->gameModel->deleteGame($id);
    }

    public function showShop(): void {
        try {
            $games = $this->gameModel->getAllGames();
            $featuredGames = $this->gameModel->getTopRatedGames(3);
            $topGames = $this->gameModel->getTopDownloadedGames(3);
            require_once __DIR__ . '/../views/frontoffice/shop.php';
        } catch (Exception $e) {
            die('Erreur lors de l\'affichage du shop : ' . $e->getMessage());
        }
    }
    
    public function showAllGames(): void {
        try {
            $games = $this->gameModel->getAllGames();
            require_once __DIR__ . '/../views/frontoffice/all-games.php';
        } catch (Exception $e) {
            die('Erreur lors de l\'affichage de tous les jeux : ' . $e->getMessage());
        }
    }

    public function showGameDetails(): void {
        try {
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                header('Location: shop.php');
                exit();
            }
            
            $gameId = (int)$_GET['id'];
            $game = $this->gameModel->getGameById($gameId);
            
            if (!$game) {
                header('Location: shop.php');
                exit();
            }
            
            require_once __DIR__ . '/../views/frontoffice/details.php';
        } catch (Exception $e) {
            die('Erreur lors de l\'affichage des détails : ' . $e->getMessage());
        }
    }

    public function searchGamesView(): void {
        try {
            $searchTerm = $_GET['search'] ?? '';
            
            if (empty($searchTerm)) {
                header('Location: shop.php');
                exit();
            }
            
            $games = $this->gameModel->searchGames($searchTerm);
            require_once __DIR__ . '/../views/frontoffice/search-results.php';
        } catch (Exception $e) {
            die('Erreur lors de la recherche : ' . $e->getMessage());
        }
    }
    
    public function filterByCategory(): void {
        try {
            $category = $_GET['category'] ?? '';
            
            if (empty($category)) {
                header('Location: shop.php');
                exit();
            }
            
            $games = $this->gameModel->getGamesByCategory($category);
            require_once __DIR__ . '/../views/frontoffice/category-games.php';
        } catch (Exception $e) {
            die('Erreur lors du filtrage : ' . $e->getMessage());
        }
    }

    public function downloadGame(): void {
        try {
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                echo json_encode(['success' => false, 'message' => 'ID manquant']);
                exit();
            }
            
            $gameId = (int)$_GET['id'];
            $success = $this->gameModel->incrementDownloads($gameId);
            
            echo json_encode(['success' => $success]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function showDashboard(): void {
        try {
            $stats = $this->gameModel->getGlobalStats();
            $topDownloads = $this->gameModel->getTopDownloadedGames(5);
            $topLikes = $this->gameModel->getTopRatedGames(5);
            require_once __DIR__ . '/../views/backoffice/dashboard.php';
        } catch (Exception $e) {
            die('Erreur lors de l\'affichage du dashboard : ' . $e->getMessage());
        }
    }

    public function showGamesList(): void {
        try {
            $games = $this->gameModel->getAllGames();
            require_once __DIR__ . '/../views/backoffice/games-list.php';
        } catch (Exception $e) {
            die('Erreur lors de l\'affichage de la liste : ' . $e->getMessage());
        }
    }
    
    public function showAddGameForm(): void {
        require_once __DIR__ . '/../views/backoffice/add-game.php';
    }

    public function addGameProcess(): void {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: add-game.php');
                exit();
            }
            
            $game = new Game();
            $game->setTitle($_POST['title'] ?? '');
            $game->setCategory($_POST['category'] ?? '');
            $game->setDescription($_POST['description'] ?? '');
            $game->setStoryline($_POST['storyline'] ?? '');
            
            $isFree = isset($_POST['is_free']) && $_POST['is_free'] == '1';
            $game->setIsFree($isFree);
            $game->setPrice($isFree ? 0.00 : (float)($_POST['price'] ?? 0));
            
            $game->setRating(0.0);
            $game->setDateAdded(date('Y-m-d'));
            
            $imagePath = $this->handleImageUpload($_FILES['image'] ?? null);
            $game->setImagePath($imagePath);
            
            $trailerPath = $this->handleVideoUpload($_FILES['trailer'] ?? null);
            $game->setTrailerPath($trailerPath);

            // Optional download link (external URL or page)
            $downloadLink = trim($_POST['download_link'] ?? '');
            if ($downloadLink !== '') {
                if (!filter_var($downloadLink, FILTER_VALIDATE_URL)) {
                    throw new Exception('Le lien de téléchargement n\'est pas une URL valide.');
                }
                $game->setDownloadLink($downloadLink);
            }
            
            $success = $this->gameModel->addGame($game);
            
            if ($success) {
                $_SESSION['success_message'] = 'Jeu ajouté avec succès !';
                header('Location: games-list.php');
            } else {
                $_SESSION['error_message'] = 'Erreur lors de l\'ajout du jeu.';
                header('Location: add-game.php');
            }
            exit();
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Erreur : ' . $e->getMessage();
            header('Location: add-game.php');
            exit();
        }
    }

    public function showEditGameForm(): void {
        try {
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                header('Location: games-list.php');
                exit();
            }
            
            $gameId = (int)$_GET['id'];
            $game = $this->gameModel->getGameById($gameId);
            
            if (!$game) {
                header('Location: games-list.php');
                exit();
            }
            
            require_once __DIR__ . '/../views/backoffice/edit-game.php';
        } catch (Exception $e) {
            die('Erreur lors de l\'affichage du formulaire : ' . $e->getMessage());
        }
    }

    public function updateGameProcess(): void {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: games-list.php');
                exit();
            }
            
            if (!isset($_POST['id']) || empty($_POST['id'])) {
                header('Location: games-list.php');
                exit();
            }
            
            $gameId = (int)$_POST['id'];
            $existingGame = $this->gameModel->getGameById($gameId);
            
            if (!$existingGame) {
                header('Location: games-list.php');
                exit();
            }
            
            $game = new Game();
            $game->setId($gameId);
            $game->setTitle($_POST['title'] ?? $existingGame['title']);
            $game->setCategory($_POST['category'] ?? $existingGame['category']);
            $game->setDescription($_POST['description'] ?? $existingGame['description']);
            $game->setStoryline($_POST['storyline'] ?? $existingGame['storyline']);
            
            $isFree = isset($_POST['is_free']) && $_POST['is_free'] == '1';
            $game->setIsFree($isFree);
            $game->setPrice($isFree ? 0.00 : (float)($_POST['price'] ?? $existingGame['price']));
            
            $game->setRating((float)$existingGame['rating']);
            
            $imagePath = $this->handleImageUpload($_FILES['image'] ?? null, $existingGame['image_path']);
            $game->setImagePath($imagePath);
            
            $trailerPath = $this->handleVideoUpload($_FILES['trailer'] ?? null, $existingGame['trailer_path']);
            $game->setTrailerPath($trailerPath);

            // If a download link is provided in the form, use it; otherwise keep existing
            $downloadLink = trim($_POST['download_link'] ?? $existingGame['download_link'] ?? '');
            if ($downloadLink !== '') {
                if (!filter_var($downloadLink, FILTER_VALIDATE_URL)) {
                    throw new Exception('Le lien de téléchargement n\'est pas une URL valide.');
                }
                $game->setDownloadLink($downloadLink);
            }
            
            $success = $this->gameModel->updateGame($game);
            
            if ($success) {
                $_SESSION['success_message'] = 'Jeu modifié avec succès !';
            } else {
                $_SESSION['error_message'] = 'Erreur lors de la modification du jeu.';
            }
            
            header('Location: games-list.php');
            exit();
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Erreur : ' . $e->getMessage();
            header('Location: games-list.php');
            exit();
        }
    }
    
    public function deleteGameProcess(): void {
        try {
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                echo json_encode(['success' => false, 'message' => 'ID manquant']);
                exit();
            }
            
            $gameId = (int)$_GET['id'];
            $game = $this->gameModel->getGameById($gameId);
            
            if ($game) {
                $this->deleteFile($game['image_path']);
                $this->deleteFile($game['trailer_path']);
            }
            
            $success = $this->gameModel->deleteGame($gameId);
            
            if ($success) {
                $_SESSION['success_message'] = 'Jeu supprimé avec succès !';
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function handleImageUpload(?array $file, string $defaultPath = ''): string {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return $defaultPath;
        }
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Type de fichier image non autorisé.');
        }
        
        $uploadDir = __DIR__ . '/../views/assets/images/games/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'game_' . uniqid() . '.' . $extension;
        $destination = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return 'assets/images/games/' . $filename;
        }
        
        return $defaultPath;
    }

    private function handleVideoUpload(?array $file, string $defaultPath = ''): string {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return $defaultPath;
        }
        
        $allowedTypes = ['video/mp4', 'video/webm'];
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Type de fichier vidéo non autorisé.');
        }
        
        $uploadDir = __DIR__ . '/../views/assets/videos/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'trailer_' . uniqid() . '.' . $extension;
        $destination = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return 'assets/videos/' . $filename;
        }
        
        return $defaultPath;
    }
    
    /**
     * Gérer l'upload d'un fichier ZIP
     */
    private function handleZipUpload(?array $file, string $defaultPath = ''): string {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return $defaultPath;
        }
        
        if ($file['type'] !== 'application/zip' && $file['type'] !== 'application/x-zip-compressed') {
            throw new Exception('Type de fichier ZIP non autorisé.');
        }
        
        $uploadDir = __DIR__ . '/../views/assets/games/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $filename = 'game_' . uniqid() . '.zip';
        $destination = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return 'assets/games/' . $filename;
        }
        
        return $defaultPath;
    }
    
    /**
     * Supprimer un fichier physique
     */
    private function deleteFile(?string $filePath): void {
        if (!empty($filePath)) {
            $fullPath = __DIR__ . '/../views/' . $filePath;
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    }
    
    // ========================================
    // MÉTHODES POUR LE PANIER (CART)
    // ========================================
    
    /**
     * Récupérer le panier d'un utilisateur
     */
    public function getCartByUserId(int $userId): array {
        $this->initCartModel();
        return $this->cartModel->getCartByUserId($userId);
    }
    
    /**
     * Ajouter un jeu au panier
     */
    public function addToCart(int $userId, int $gameId, int $quantity = 1): bool {
        $this->initCartModel();
        return $this->cartModel->addToCart($userId, $gameId, $quantity);
    }
    
    /**
     * Mettre à jour la quantité d'un article
     */
    public function updateCartQuantity(int $cartId, int $quantity): bool {
        $this->initCartModel();
        return $this->cartModel->updateQuantity($cartId, $quantity);
    }
    
    /**
     * Supprimer un article du panier
     */
    public function removeFromCart(int $cartId): bool {
        $this->initCartModel();
        return $this->cartModel->removeFromCart($cartId);
    }
    
    /**
     * Vider tout le panier
     */
    public function clearCart(int $userId): bool {
        $this->initCartModel();
        return $this->cartModel->clearCart($userId);
    }
    
    /**
     * Obtenir le total du panier
     */
    public function getCartTotal(int $userId): float {
        $this->initCartModel();
        return $this->cartModel->getCartTotal($userId);
    }
    
    /**
     * Compter les articles dans le panier
     */
    public function getCartItemCount(int $userId): int {
        $this->initCartModel();
        return $this->cartModel->getCartItemCount($userId);
    }
    
    /**
     * Vérifier si un jeu est dans le panier
     */
    public function isGameInCart(int $userId, int $gameId): bool {
        $this->initCartModel();
        return $this->cartModel->isGameInCart($userId, $gameId);
    }
    
    /**
     * Valider un code promo
     */
    public function validatePromoCode(string $promoCode): ?array {
        $this->initCartModel();
        return $this->cartModel->validatePromoCode($promoCode);
    }
    
    /**
     * Obtenir le résumé du panier avec réduction
     */
    public function getCartSummary(int $userId, ?string $promoCode = null): array {
        $this->initCartModel();
        return $this->cartModel->getCartSummary($userId, $promoCode);
    }
    
    /**
     * Afficher la page du panier
     */
    public function showCart(): void {
        try {
            $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;
            
            $this->initCartModel();
            $cartItems = $this->cartModel->getCartByUserId($userId);
            
            $promoCode = isset($_SESSION['promo_code']) ? $_SESSION['promo_code'] : null;
            $summary = $this->cartModel->getCartSummary($userId, $promoCode);
            
            require_once __DIR__ . '/../views/frontoffice/cart.php';
        } catch (Exception $e) {
            die('Erreur lors de l\'affichage du panier : ' . $e->getMessage());
        }
    }
    
    /**
     * Traiter l'ajout au panier (via AJAX)
     */
    public function addToCartProcess(): void {
        try {
            $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;
            
            if (!isset($_POST['game_id']) || empty($_POST['game_id'])) {
                echo json_encode(['success' => false, 'message' => 'ID du jeu manquant']);
                exit();
            }
            
            $gameId = (int)$_POST['game_id'];
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            
            $game = $this->gameModel->getGameById($gameId);
            if (!$game) {
                echo json_encode(['success' => false, 'message' => 'Jeu introuvable']);
                exit();
            }
            
            if ($game['is_free']) {
                echo json_encode(['success' => false, 'message' => 'Ce jeu est gratuit, il ne peut pas être ajouté au panier']);
                exit();
            }
            
            $this->initCartModel();
            $success = $this->cartModel->addToCart($userId, $gameId, $quantity);
            
            if ($success) {
                $itemCount = $this->cartModel->getCartItemCount($userId);
                
                echo json_encode([
                    'success' => true, 
                    'message' => 'Jeu ajouté au panier !',
                    'cart_count' => $itemCount
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * Mettre à jour la quantité (via AJAX)
     */
    public function updateCartQuantityProcess(): void {
        try {
            if (!isset($_POST['cart_id']) || !isset($_POST['quantity'])) {
                echo json_encode(['success' => false, 'message' => 'Données manquantes']);
                exit();
            }
            
            $cartId = (int)$_POST['cart_id'];
            $quantity = (int)$_POST['quantity'];
            
            $this->initCartModel();
            $success = $this->cartModel->updateQuantity($cartId, $quantity);
            
            if ($success) {
                $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;
                $summary = $this->cartModel->getCartSummary($userId, $_SESSION['promo_code'] ?? null);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Quantité mise à jour',
                    'summary' => $summary
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * Supprimer un article du panier (via AJAX)
     */
    public function removeFromCartProcess(): void {
        try {
            if (!isset($_POST['cart_id']) || empty($_POST['cart_id'])) {
                echo json_encode(['success' => false, 'message' => 'ID manquant']);
                exit();
            }
            
            $cartId = (int)$_POST['cart_id'];
            
            $this->initCartModel();
            $success = $this->cartModel->removeFromCart($cartId);
            
            if ($success) {
                $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;
                $itemCount = $this->cartModel->getCartItemCount($userId);
                $summary = $this->cartModel->getCartSummary($userId, $_SESSION['promo_code'] ?? null);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Article supprimé',
                    'cart_count' => $itemCount,
                    'summary' => $summary
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * Appliquer un code promo (via AJAX)
     */
    public function applyPromoCodeProcess(): void {
        try {
            if (!isset($_POST['promo_code']) || empty($_POST['promo_code'])) {
                echo json_encode(['success' => false, 'message' => 'Code promo manquant']);
                exit();
            }
            
            $promoCode = trim($_POST['promo_code']);
            $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;
            
            $this->initCartModel();
            $promoData = $this->cartModel->validatePromoCode($promoCode);
            
            if ($promoData) {
                $_SESSION['promo_code'] = $promoData['code'];
                
                $summary = $this->cartModel->getCartSummary($userId, $promoData['code']);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Code promo appliqué : ' . $promoData['description'],
                    'promo_data' => $promoData,
                    'summary' => $summary
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Code promo invalide']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * Retirer un code promo (via AJAX)
     */
    public function removePromoCodeProcess(): void {
        try {
            $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;
            
            unset($_SESSION['promo_code']);
            
            $this->initCartModel();
            $summary = $this->cartModel->getCartSummary($userId, null);
            
            echo json_encode([
                'success' => true,
                'message' => 'Code promo retiré',
                'summary' => $summary
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * Obtenir le nombre d'articles dans le panier (pour le badge dans le header)
     */
    public function getCartCount(): void {
        try {
            $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;
            
            $this->initCartModel();
            $count = $this->cartModel->getCartItemCount($userId);
            
            echo json_encode(['success' => true, 'count' => $count]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'count' => 0]);
        }
    }
    /**
 * Récupérer les détails d'une commande avec les jeux
 */
public function getOrderDetails(int $userId): array {
    try {
        $pdo = config::getConnexion();
        
        // Récupérer toutes les commandes récentes de l'utilisateur
        $stmt = $pdo->prepare('
            SELECT 
                o.id,
                o.game_id,
                o.game_title,
                o.quantity,
                o.price,
                o.payment_method,
                o.receipt_path,
                o.created_at,
                g.download_link,
                g.image_path
            FROM orders o
            LEFT JOIN games g ON o.game_id = g.id
            WHERE o.user_id = :user_id
            ORDER BY o.created_at DESC
        ');
        
        $stmt->execute([':user_id' => $userId]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($orders)) {
            throw new Exception("No orders found for this user");
        }
        
        // Calculer les totaux
        $subtotal = 0;
        $games = [];
        
        foreach ($orders as $order) {
            $itemTotal = $order['price'] * $order['quantity'];
            $subtotal += $itemTotal;
            
            $games[] = [
                'id' => $order['game_id'],
                'title' => $order['game_title'],
                'quantity' => $order['quantity'],
                'price' => $order['price'],
                'subtotal' => $itemTotal,
                'download_link' => $order['download_link'] ?? null,
                'image_path' => $order['image_path'] ?? null
            ];
        }
        
        return [
            'order_ids' => array_column($orders, 'id'),
            'games' => $games,
            'subtotal' => $subtotal,
            'discount' => 0, // Vous pouvez ajouter la logique de discount si nécessaire
            'total' => $subtotal,
            'payment_method' => $orders[0]['payment_method'],
            'created_at' => $orders[0]['created_at']
        ];
        
    } catch (Exception $e) {
        error_log("Error in getOrderDetails: " . $e->getMessage());
        throw $e;
    }
}

/**
 * Obtenir la connexion PDO (si elle n'existe pas déjà)
 */
private function getConnection() {
    return config::getConnexion();
}
}
?>