<?php
$pageTitle = "Gaming Quiz - Test Your Knowledge";
$page = 'quiz_list';

$customCSS = '
.quiz-card {
    background: linear-gradient(to bottom, #1f2122 0%, #27292a 100%);
    border-radius: 23px;
    padding: 20px;
    margin-bottom: 30px;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    height: 350px;
}

.quiz-card:hover {
    transform: scale(1.05);
    box-shadow: 0 0 30px rgba(232, 64, 87, 0.5);
}

.quiz-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 23px;
    margin-bottom: 15px;
}

.quiz-info {
    position: relative;
    z-index: 2;
}

.quiz-info h4 {
    color: #fff;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
}

.quiz-category {
    display: inline-block;
    background: #e84057;
    color: #fff;
    padding: 5px 15px;
    border-radius: 15px;
    font-size: 12px;
    margin-bottom: 10px;
}

.quiz-creator {
    color: #666;
    font-size: 13px;
    margin-bottom: 10px;
}

.quiz-details {
    opacity: 0;
    max-height: 0;
    overflow: hidden;
    transition: all 0.3s ease;
}

.quiz-card:hover .quiz-details {
    opacity: 1;
    max-height: 200px;
}

.quiz-detail-item {
    display: flex;
    justify-content: space-between;
    padding: 5px 0;
    color: #fff;
    font-size: 13px;
    border-bottom: 1px solid #333;
}

.quiz-detail-item span:first-child {
    color: #666;
}

.filter-section {
    background: linear-gradient(to bottom, #1f2122 0%, #27292a 100%);
    border-radius: 23px;
    padding: 30px;
    margin-bottom: 40px;
}

.filter-btn {
    background: #27292a;
    border: none;
    color: #fff;
    padding: 10px 20px;
    border-radius: 23px;
    margin: 5px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.filter-btn:hover, .filter-btn.active {
    background: #e84057;
}

.create-quiz-btn {
    background: linear-gradient(to right, #e84057 0%, #e75e3d 100%);
    border: none;
    color: #fff;
    padding: 15px 30px;
    border-radius: 23px;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-block;
    text-decoration: none;
}

.create-quiz-btn:hover {
    transform: scale(1.05);
    color: #fff;
}

.user-stats {
    background: linear-gradient(to bottom, #1f2122 0%, #27292a 100%);
    border-radius: 23px;
    padding: 30px;
    margin-top: 50px;
}

.stat-item {
    text-align: center;
    padding: 20px;
}

.stat-number {
    font-size: 36px;
    font-weight: 700;
    color: #e84057;
    display: block;
}

.stat-label {
    color: #666;
    font-size: 14px;
    margin-top: 5px;
}
';

include 'view/header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-content">

                <!-- Success Messages -->
                <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Quiz created successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Main Banner -->
                <div class="main-banner">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="header-text">
                                <h6>Test Your Gaming Knowledge</h6>
                                <h4><em>Challenge</em> Yourself With Our Quiz</h4>
                                <div class="main-button">
                                    <a href="quiz_list.php?page=quiz_create" class="create-quiz-btn">
                                        <i class="fa fa-plus"></i> Create Your Own Quiz
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="filter-section">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="heading-section">
                                <h4><em>Filter</em> By Category</h4>
                            </div>
                            <div style="text-align: center;">
                                <a href="javascript:void(0)" onclick="filterByCategory(null)" class="filter-btn active" data-category="all">All</a>
                                <a href="javascript:void(0)" onclick="filterByCategory('retro')" class="filter-btn" data-category="retro">Retro</a>
                                <a href="javascript:void(0)" onclick="filterByCategory('action')" class="filter-btn" data-category="action">Action</a>
                                <a href="javascript:void(0)" onclick="filterByCategory('strategy')" class="filter-btn" data-category="strategy">Strategy</a>
                                <a href="javascript:void(0)" onclick="filterByCategory('rpg')" class="filter-btn" data-category="rpg">RPG</a>
                                <a href="javascript:void(0)" onclick="filterByCategory('fps')" class="filter-btn" data-category="fps">FPS</a>
                                <a href="javascript:void(0)" onclick="filterByCategory('moba')" class="filter-btn" data-category="moba">MOBA</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quiz List -->
                <div class="most-popular">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="heading-section">
                                <h4><em>Available</em> Quiz</h4>
                            </div>
                            <div class="row" id="quiz-list-container">
                                <?php if(empty($quizzes)): ?>
                                    <div class="col-12">
                                        <p style="text-align: center; color: #666; padding: 50px;">No quiz found.</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach($quizzes as $quiz): ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="quiz-card" onclick="window.location.href='quiz_list.php?page=quiz_play&id=<?php echo $quiz['id_quiz']; ?>'">
                                                <img src="assets/images/<?php echo htmlspecialchars($quiz['image_url']); ?>" 
                                                     alt="<?php echo htmlspecialchars($quiz['titre']); ?>" 
                                                     onerror="this.src='assets/images/popular-01.jpg'">
                                                <div class="quiz-info">
                                                    <span class="quiz-category"><?php echo strtoupper(htmlspecialchars($quiz['categorie'])); ?></span>
                                                    <h4><?php echo htmlspecialchars($quiz['titre']); ?></h4>
                                                    <p class="quiz-creator">
                                                        <i class="fa fa-user"></i> Created by: <?php echo htmlspecialchars($quiz['createur'] ?? 'Anonymous'); ?>
                                                    </p>
                                                    
                                                    <div class="quiz-details">
                                                        <div class="quiz-detail-item">
                                                            <span>Questions:</span>
                                                            <span><?php echo $quiz['nombre_questions']; ?></span>
                                                        </div>
                                                        <div class="quiz-detail-item">
                                                            <span>Duration:</span>
                                                            <span>
                                                                <?php 
                                                                $durationSeconds = 180 + (max(0, $quiz['nombre_questions'] - 8) * 15);
                                                                $durationMinutes = ceil($durationSeconds / 60);
                                                                echo $durationMinutes . ' min';
                                                                ?>
                                                            </span>
                                                        </div>
                                                        <div class="quiz-detail-item">
                                                            <span>Difficulty:</span>
                                                            <span><?php echo ucfirst($quiz['difficulte']); ?></span>
                                                        </div>
                                                        <div class="quiz-detail-item">
                                                            <span>Completed:</span>
                                                            <span><?php echo $quiz['nombre_completions']; ?> times</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Stats -->
                <div class="user-stats">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="heading-section">
                                <h4><em>Your</em> Quiz Statistics</h4>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="stat-item">
                                <span class="stat-number" id="stat-quiz-completed"><?php echo $stats['quiz_completes'] ?? 0; ?></span>
                                <p class="stat-label">Quiz Completed</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="stat-item">
                                <span class="stat-number" id="stat-avg-score"><?php echo isset($stats['pourcentage_moyen']) ? round($stats['pourcentage_moyen']) . '%' : '0%'; ?></span>
                                <p class="stat-label">Average Score</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="stat-item">
                                <span class="stat-number" id="stat-quiz-created"><?php echo $stats['quiz_crees'] ?? 0; ?></span>
                                <p class="stat-label">Quiz Created</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
// Function to update statistics
function updateStatistics() {
    const userId = localStorage.getItem('userId') || '13';
    fetch('quiz_list.php?page=get_user_stats&user_id=' + encodeURIComponent(userId))
        .then(response => response.json())
        .then(data => {
            // Update Quiz Completed
            document.getElementById('stat-quiz-completed').textContent = data.quiz_completes || 0;
            
            // Update Average Score
            const avgScore = data.pourcentage_moyen ? Math.round(data.pourcentage_moyen) + '%' : '0%';
            document.getElementById('stat-avg-score').textContent = avgScore;
            
            // Update Quiz Created
            document.getElementById('stat-quiz-created').textContent = data.quiz_crees || 0;
            
            console.log('✓ Statistics updated successfully');
        })
        .catch(error => {
            console.error('Error updating statistics:', error);
        });
}

// Filter quizzes by category using AJAX
function filterByCategory(category) {
    // Update active filter button styling
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    if (category !== null) {
        const activeBtn = document.querySelector(`[data-category="${category}"]`);
        if (activeBtn) {
            activeBtn.classList.add('active');
        }
    }
    
    // Prepare the URL
    const url = new URL(window.location.href);
    url.searchParams.set('page', 'get_quizzes_by_category');
    if (category !== null) {
        url.searchParams.set('category', category);
    }
    
    // Show loading state
    const container = document.getElementById('quiz-list-container');
    if (container) {
        container.innerHTML = '<div class="text-center"><p>Loading quizzes...</p></div>';
    }
    
    // Fetch filtered quizzes
    fetch(url.toString())
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success && container) {
                container.innerHTML = data.html;
                console.log('✓ Quizzes filtered: ' + data.count + ' quiz(zes) found');
            } else if (!data.success) {
                container.innerHTML = '<div class="alert alert-warning text-center">No quizzes found in this category.</div>';
            }
        })
        .catch(error => {
            console.error('Error filtering quizzes:', error);
            if (container) {
                container.innerHTML = '<div class="alert alert-danger text-center">Error loading quizzes. Please try again.</div>';
            }
        });
}

// Update statistics when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Initial update after 500ms (give page time to load)
    setTimeout(updateStatistics, 500);
    
    // Check if we just came back from playing a quiz or creating one
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success') || urlParams.has('quiz_completed')) {
        // Update again after 1 second to ensure database changes are reflected
        setTimeout(updateStatistics, 1000);
    }
});
</script>

<?php include 'view/footer.php'; ?>