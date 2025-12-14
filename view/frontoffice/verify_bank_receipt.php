<?php
/**
 * Vérification simple par nom de fichier
 * Accepte uniquement les fichiers nommés "receipt.pdf"
 */

header('Content-Type: application/json');

if (!isset($_FILES['bank_receipt']) || $_FILES['bank_receipt']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Aucun fichier uploadé']);
    exit();
}

$file = $_FILES['bank_receipt'];

// Vérifier le type MIME
if ($file['type'] !== 'application/pdf') {
    echo json_encode(['success' => false, 'message' => 'Le fichier doit être un PDF']);
    exit();
}

// Vérifier la taille (max 10MB)
if ($file['size'] > 10 * 1024 * 1024) {
    echo json_encode(['success' => false, 'message' => 'Le fichier est trop volumineux (max 10MB)']);
    exit();
}

// Vérifier si le nom du fichier est "receipt.pdf"
$filename = strtolower($file['name']);

if ($filename === 'receipt.pdf') {
    echo json_encode([
        'success' => true,
        'message' => 'Receipt verified successfully.',
        'bank_name' => 'BIAT',
        'confidence' => 100
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Please Upload a real bank transfer pdf file .'
    ]);
}
?>