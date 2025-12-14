<?php
/**
 * Certificate Generation Controller
 * controller/generate_certificate.php
 * UPDATED: Fetches username from database (friend's auth doesn't store name in session)
 */

require_once __DIR__ . '/../vendor/fpdf/fpdf.php';
require_once __DIR__ . '/../config/db.php';

class CertificateController {
    
    /**
     * Generate and display/download certificate
     */
    public function generate() {
        // Get parameters from URL
        $quiz_id = $_GET['quiz_id'] ?? null;
        $percentage = $_GET['percentage'] ?? 0;
        $correct_answers = $_GET['correct'] ?? 0;
        $total_questions = $_GET['total'] ?? 0;
        $time_taken = $_GET['time'] ?? 0;
        $action = $_GET['action'] ?? 'view'; // 'view' or 'download'
        
        // Get user ID from session
        $user_id = $_SESSION['user_id'] ?? 1; // FIXED: Use 'user_id' from friend's auth
        
        // Fetch username from database (friend's auth doesn't store it in session)
        $conn = config::getConnexion();
        
        $userQuery = "SELECT CONCAT(name, ' ', lastname) as fullname FROM users WHERE id = :user_id";
        $userStmt = $conn->prepare($userQuery);
        $userStmt->bindParam(':user_id', $user_id);
        $userStmt->execute();
        $userData = $userStmt->fetch(PDO::FETCH_ASSOC);
        
        $username = $userData['fullname'] ?? 'Guest Player';
        
        // Validate percentage
        if ($percentage < 50) {
            die('Certificate only available for scores >= 50%');
        }
        
        // Get quiz details from database
        $query = "SELECT titre, categorie FROM quiz WHERE id_quiz = :id_quiz";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_quiz', $quiz_id);
        $stmt->execute();
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$quiz) {
            die('Quiz not found');
        }
        
        // Determine mention based on percentage
        $mention = $this->getMention($percentage);
        
        // Format time
        $time_minutes = floor($time_taken / 60);
        $time_seconds = $time_taken % 60;
        $time_formatted = $time_minutes . 'm ' . $time_seconds . 's';
        
        // Generate PDF
        $pdf = new PDF_Certificate();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        
        // Path to logo
        $logoPath = __DIR__ . '/../assets/images/logo.png';
        
        // Draw certificate
        $pdf->DrawCertificate(
            $username,
            $quiz['titre'],
            $correct_answers . '/' . $total_questions,
            $percentage,
            $mention,
            $quiz['categorie'],
            $time_formatted,
            $logoPath
        );
        
        // Output PDF
        if ($action === 'download') {
            $filename = 'Certificate_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $quiz['titre']) . '_' . date('Y-m-d') . '.pdf';
            $pdf->Output('D', $filename);
        } else {
            $pdf->Output('I', 'Certificate.pdf');
        }
        
        exit;
    }
    
    /**
     * Get mention based on percentage
     */
    private function getMention($percentage) {
        if ($percentage >= 90) {
            return 'Excellent';
        } elseif ($percentage >= 80) {
            return 'Very Good';
        } elseif ($percentage >= 70) {
            return 'Good';
        } elseif ($percentage >= 60) {
            return 'Ok';
        } else {
            return 'Pass';
        }
    }
}

/**
 * Custom FPDF class for certificate
 */
class PDF_Certificate extends FPDF {
    
    /**
     * Draw the complete certificate
     */
    public function DrawCertificate($username, $quizTitle, $score, $percentage, $mention, $category, $time, $logoPath) {
        // Set page background color (dark gray/black)
        $this->SetFillColor(31, 33, 34);
        $this->Rect(0, 0, 210, 297, 'F');
        
        // Draw decorative border
        $this->DrawBorder();
        
        // Add logo at top center
        if (file_exists($logoPath)) {
            $this->Image($logoPath, 85, 15, 40);
        }
        
        // Main title "CERTIFICATE OF ACHIEVEMENT"
        $this->SetY(60);
        $this->SetFont('Arial', 'B', 28);
        $this->SetTextColor(200, 100, 200); // Light Purple #c864c8
        $this->Cell(0, 15, 'CERTIFICATE OF ACHIEVEMENT', 0, 1, 'C');
        
        // Decorative line under title
        $this->SetDrawColor(200, 100, 200);
        $this->SetLineWidth(1);
        $this->Line(50, 77, 160, 77);
        
        // "This is to certify that"
        $this->SetY(85);
        $this->SetFont('Arial', 'I', 12);
        $this->SetTextColor(200, 200, 200);
        $this->Cell(0, 10, 'This is to certify that', 0, 1, 'C');
        
        // Username (large and bold)
        $this->SetFont('Arial', 'B', 24);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 12, strtoupper($username), 0, 1, 'C');
        
        // Decorative underline for name
        $nameWidth = $this->GetStringWidth(strtoupper($username));
        $nameX = (210 - $nameWidth) / 2;
        $this->SetDrawColor(220, 120, 180); // Medium Pink #dc78b4
        $this->SetLineWidth(0.5);
        $this->Line($nameX, 110, $nameX + $nameWidth, 110);
        
        // "has successfully completed"
        $this->SetY(115);
        $this->SetFont('Arial', 'I', 12);
        $this->SetTextColor(200, 200, 200);
        $this->Cell(0, 10, 'has successfully completed', 0, 1, 'C');
        
        // Quiz title
        $this->SetFont('Arial', 'B', 16);
        $this->SetTextColor(220, 120, 180); // Medium Pink #dc78b4
        $this->MultiCell(0, 8, $quizTitle, 0, 'C');
        
        // Mention box (under quiz title)
        $this->SetY(138);
        $this->DrawMentionBox($mention);
        
        // Score box (centered, large percentage)
        $this->SetY(156);
        $this->DrawScoreBox($percentage);
        
        // Details section (3 boxes: category, time, score)
        $this->SetY(188);
        $this->DrawDetailsThreeBoxes($category, $time, $score);
        
        // Date
        $this->SetY(215);
        $this->SetFont('Arial', '', 11);
        $this->SetTextColor(200, 200, 200);
        $this->Cell(0, 10, 'Date of Completion: ' . date('F d, Y'), 0, 1, 'C');
        
        // Signature line
        $this->SetY(240);
        $this->SetDrawColor(150, 150, 150);
        $this->SetLineWidth(0.3);
        $this->Line(75, 240, 135, 240);
        $this->SetY(242);
        $this->SetFont('Arial', 'I', 10);
        $this->SetTextColor(150, 150, 150);
        $this->Cell(0, 10, 'GameAct Platform', 0, 1, 'C');
        
        // Footer text
        $this->SetY(270);
        $this->SetFont('Arial', 'I', 9);
        $this->SetTextColor(120, 120, 120);
        $this->Cell(0, 5, 'This certificate validates your gaming knowledge and quiz completion', 0, 1, 'C');
    }
    
    /**
     * Draw decorative border
     */
    private function DrawBorder() {
        // Outer border (Deep Purple #663399)
        $this->SetDrawColor(102, 51, 153);
        $this->SetLineWidth(3);
        $this->Rect(10, 10, 190, 277);
        
        // Inner border (Light Purple #c864c8)
        $this->SetDrawColor(200, 100, 200);
        $this->SetLineWidth(1);
        $this->Rect(15, 15, 180, 267);
        
        // Corner decorations
        $this->DrawCornerDecoration(10, 10, 'tl');
        $this->DrawCornerDecoration(200, 10, 'tr');
        $this->DrawCornerDecoration(10, 287, 'bl');
        $this->DrawCornerDecoration(200, 287, 'br');
    }
    
    /**
     * Draw corner decorations
     */
    private function DrawCornerDecoration($x, $y, $corner) {
        $this->SetDrawColor(200, 100, 200); // Light Purple
        $this->SetLineWidth(0.5);
        
        $size = 8;
        
        switch($corner) {
            case 'tl':
                $this->Line($x, $y, $x + $size, $y);
                $this->Line($x, $y, $x, $y + $size);
                break;
            case 'tr':
                $this->Line($x, $y, $x - $size, $y);
                $this->Line($x, $y, $x, $y + $size);
                break;
            case 'bl':
                $this->Line($x, $y, $x + $size, $y);
                $this->Line($x, $y, $x, $y - $size);
                break;
            case 'br':
                $this->Line($x, $y, $x - $size, $y);
                $this->Line($x, $y, $x, $y - $size);
                break;
        }
    }
    
    /**
     * Draw mention box (under quiz title)
     */
    private function DrawMentionBox($mention) {
        $boxWidth = 100;
        $boxHeight = 14;
        $boxX = (210 - $boxWidth) / 2;
        $boxY = 138;
        
        // Box background (darker)
        $this->SetFillColor(50, 30, 60);
        $this->Rect($boxX, $boxY, $boxWidth, $boxHeight, 'F');
        
        // Border (Medium Pink #dc78b4)
        $this->SetDrawColor(220, 120, 180);
        $this->SetLineWidth(1.5);
        $this->Rect($boxX, $boxY, $boxWidth, $boxHeight, 'D');
        
        // Mention text (centered)
        $this->SetXY($boxX, $boxY + 2);
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(220, 120, 180);
        $this->Cell($boxWidth, 10, strtoupper($mention), 0, 0, 'C');
    }
    
    /**
     * Draw score box (large percentage only)
     */
    private function DrawScoreBox($percentage) {
        $boxWidth = 70;
        $boxHeight = 28;
        $boxX = (210 - $boxWidth) / 2;
        $boxY = 156;
        
        // Box background (darker)
        $this->SetFillColor(50, 30, 60);
        $this->Rect($boxX, $boxY, $boxWidth, $boxHeight, 'F');
        
        // Border (Deep Purple #663399)
        $this->SetDrawColor(102, 51, 153);
        $this->SetLineWidth(2);
        $this->Rect($boxX, $boxY, $boxWidth, $boxHeight, 'D');
        
        // Score percentage (large)
        $this->SetXY($boxX, $boxY + 3);
        $this->SetFont('Arial', 'B', 24);
        $this->SetTextColor(200, 100, 200); // Light Purple
        $this->Cell($boxWidth, 12, $percentage . '%', 0, 0, 'C');
        
        // Label
        $this->SetXY($boxX, $boxY + 18);
        $this->SetFont('Arial', '', 9);
        $this->SetTextColor(220, 150, 200);
        $this->Cell($boxWidth, 4, 'Final Score', 0, 0, 'C');
    }
    
    /**
     * Draw details section with 3 boxes (category, time, score)
     */
    private function DrawDetailsThreeBoxes($category, $time, $score) {
        $boxY = 188;
        $boxWidth = 50;
        $spacing = 8;
        
        // Calculate positions to center 3 boxes
        $totalWidth = ($boxWidth * 3) + ($spacing * 2);
        $startX = (210 - $totalWidth) / 2;
        
        // Category box (left)
        $this->DrawDetailBox($startX, $boxY, $boxWidth, 'Category', strtoupper($category));
        
        // Time box (middle)
        $this->DrawDetailBox($startX + $boxWidth + $spacing, $boxY, $boxWidth, 'Time Taken', $time);
        
        // Score box (right)
        $this->DrawDetailBox($startX + ($boxWidth + $spacing) * 2, $boxY, $boxWidth, 'Score', $score);
    }
    
    /**
     * Draw individual detail box
     */
    private function DrawDetailBox($x, $y, $width, $label, $value) {
        $height = 16;
        
        // Box background - use simple Rect instead of RoundedRect
        $this->SetFillColor(50, 30, 60);
        $this->Rect($x, $y, $width, $height, 'F');
        
        // Border (Soft Pink #d9889f)
        $this->SetDrawColor(217, 136, 159);
        $this->SetLineWidth(0.8);
        $this->Rect($x, $y, $width, $height, 'D');
        
        // Label
        $this->SetXY($x, $y + 2);
        $this->SetFont('Arial', '', 7);
        $this->SetTextColor(200, 150, 180);
        $this->Cell($width, 4, $label, 0, 0, 'C');
        
        // Value
        $this->SetXY($x, $y + 8);
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(255, 200, 230);
        $this->Cell($width, 5, $value, 0, 0, 'C');
    }
    
    /**
     * Draw rounded rectangle
     */
    private function RoundedRect($x, $y, $w, $h, $r, $style = '') {
        $k = $this->k;
        $hp = $this->h;
        
        $MyArc = 4/3 * (sqrt(2) - 1);
        
        $this->_out(sprintf('%.2F %.2F m', ($x+$r)*$k, ($hp-$y)*$k ));
        
        $xc = $x+$w-$r;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k, ($hp-$y)*$k ));
        $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
        
        $xc = $x+$w-$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l', ($x+$w)*$k, ($hp-$yc)*$k ));
        $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
        
        $xc = $x+$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k, ($hp-($y+$h))*$k ));
        $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
        
        $xc = $x+$r;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', ($x)*$k, ($hp-$yc)*$k ));
        $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        
        $this->_out(' ' . $style);
    }
    
    private function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }
}
?>