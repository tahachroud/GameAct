<?php
/**
 * Admin Edit Quiz Page with Question Editing
 */
include 'view/header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-content">
                
                <!-- Page Header -->
                <div class="row" style="margin-bottom: 30px;">
                    <div class="col-lg-12">
                        <div class="heading-section">
                            <h4><em>Admin</em> Edit Quiz</h4>
                        </div>
                    </div>
                </div>

                <!-- Edit Quiz Form -->
                <div class="row">
                    <div class="col-lg-10 offset-lg-1">
                        <div class="main-border-button" style="padding: 30px; background: #27292a; border-radius: 15px;">
                            
                            <form action="index.php?page=admin_quiz_update" method="POST" enctype="multipart/form-data" id="edit-quiz-form">
                                <input type="hidden" name="quiz-id" value="<?php echo $quiz['id_quiz']; ?>">
                                
                                <h3 style="color: white; margin-bottom: 30px;"><i class="fa fa-info-circle"></i> Quiz Information</h3>
                                
                                <!-- Current Image Preview -->
                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label style="color: white; font-weight: 600; margin-bottom: 10px;">Current Image:</label>
                                    <div>
                                        <img src="assets/images/<?php echo htmlspecialchars($quiz['image_url']); ?>" 
                                             alt="Current quiz image"
                                             id="current-image-preview"
                                             style="max-width: 200px; max-height: 200px; border-radius: 10px; border: 2px solid #e75e8d;">
                                    </div>
                                </div>
                                
                                <!-- Image Upload -->
                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label for="quiz-image" style="color: white; font-weight: 600; margin-bottom: 10px;">
                                        Upload New Image (Optional):
                                    </label>
                                    <input type="file" 
                                           class="form-control" 
                                           id="quiz-image" 
                                           name="quiz-image"
                                           accept="image/*"
                                           style="background-color: #1f2122; color: white; border: 2px solid #333; padding: 10px; border-radius: 10px;">
                                    <small style="color: #999; display: block; margin-top: 5px;">
                                        Accepted formats: JPG, PNG, GIF, WEBP (Max 5MB)
                                    </small>
                                    
                                    <!-- New Image Preview -->
                                    <div id="new-image-preview" style="margin-top: 15px; display: none;">
                                        <label style="color: white; font-weight: 600; margin-bottom: 10px;">New Image Preview:</label>
                                        <div>
                                            <img id="preview-img" 
                                                 src="" 
                                                 alt="New image preview"
                                                 style="max-width: 200px; max-height: 200px; border-radius: 10px; border: 2px solid #28a745;">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Quiz Title -->
                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label for="quiz-title" style="color: white; font-weight: 600; margin-bottom: 10px;">
                                        Quiz Title: *
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="quiz-title" 
                                           name="quiz-title"
                                           value="<?php echo htmlspecialchars($quiz['titre']); ?>"
                                           style="background-color: #1f2122; color: white; border: 2px solid #333; padding: 15px; border-radius: 10px;">
                                    <div id="error-title" class="error-message" style="display: none; color: #dc3545; margin-top: 5px; font-size: 14px;">
                                        Le titre doit contenir au moins 5 caractères
                                    </div>
                                </div>
                                
                                <!-- Quiz Description -->
                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label for="quiz-description" style="color: white; font-weight: 600; margin-bottom: 10px;">
                                        Description: *
                                    </label>
                                    <textarea class="form-control" 
                                              id="quiz-description" 
                                              name="quiz-description" 
                                              rows="4"
                                              style="background-color: #1f2122; color: white; border: 2px solid #333; padding: 15px; border-radius: 10px;"><?php echo htmlspecialchars($quiz['description']); ?></textarea>
                                    <div id="error-description" class="error-message" style="display: none; color: #dc3545; margin-top: 5px; font-size: 14px;">
                                        La description doit contenir au moins 20 caractères
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <!-- Category -->
                                        <div class="form-group" style="margin-bottom: 20px;">
                                            <label for="quiz-category" style="color: white; font-weight: 600; margin-bottom: 10px;">
                                                Category: *
                                            </label>
                                            <select class="form-control" 
                                                    id="quiz-category" 
                                                    name="quiz-category"
                                                    style="background-color: #1f2122; color: white; border: 2px solid #333; padding: 15px; border-radius: 10px;">
                                                <option value="">Select Category</option>
                                                <option value="retro" <?php echo $quiz['categorie'] === 'retro' ? 'selected' : ''; ?>>Retro Games</option>
                                                <option value="action" <?php echo $quiz['categorie'] === 'action' ? 'selected' : ''; ?>>Action</option>
                                                <option value="strategy" <?php echo $quiz['categorie'] === 'strategy' ? 'selected' : ''; ?>>Strategy</option>
                                                <option value="rpg" <?php echo $quiz['categorie'] === 'rpg' ? 'selected' : ''; ?>>RPG</option>
                                                <option value="fps" <?php echo $quiz['categorie'] === 'fps' ? 'selected' : ''; ?>>FPS</option>
                                                <option value="moba" <?php echo $quiz['categorie'] === 'moba' ? 'selected' : ''; ?>>MOBA</option>
                                                <option value="sports" <?php echo $quiz['categorie'] === 'sports' ? 'selected' : ''; ?>>Sports</option>
                                                <option value="racing" <?php echo $quiz['categorie'] === 'racing' ? 'selected' : ''; ?>>Racing</option>
                                            </select>
                                            <div id="error-category" class="error-message" style="display: none; color: #dc3545; margin-top: 5px; font-size: 14px;">
                                                Veuillez sélectionner une catégorie
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <!-- Difficulty -->
                                        <div class="form-group" style="margin-bottom: 20px;">
                                            <label for="quiz-difficulty" style="color: white; font-weight: 600; margin-bottom: 10px;">
                                                Difficulty: *
                                            </label>
                                            <select class="form-control" 
                                                    id="quiz-difficulty" 
                                                    name="quiz-difficulty"
                                                    style="background-color: #1f2122; color: white; border: 2px solid #333; padding: 15px; border-radius: 10px;">
                                                <option value="easy" <?php echo $quiz['difficulte'] === 'easy' ? 'selected' : ''; ?>>Easy</option>
                                                <option value="medium" <?php echo $quiz['difficulte'] === 'medium' ? 'selected' : ''; ?>>Medium</option>
                                                <option value="hard" <?php echo $quiz['difficulte'] === 'hard' ? 'selected' : ''; ?>>Hard</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <!-- Status (ADMIN ONLY) -->
                                        <div class="form-group" style="margin-bottom: 20px;">
                                            <label for="quiz-status" style="color: white; font-weight: 600; margin-bottom: 10px;">
                                                Status: *
                                            </label>
                                            <select class="form-control" 
                                                    id="quiz-status" 
                                                    name="quiz-status"
                                                    style="background-color: #1f2122; color: white; border: 2px solid #333; padding: 15px; border-radius: 10px;">
                                                <option value="active" <?php echo $quiz['statut'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                                <option value="pending" <?php echo $quiz['statut'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="deleted" <?php echo $quiz['statut'] === 'deleted' ? 'selected' : ''; ?>>Deleted</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr style="border-color: #444; margin: 40px 0;">
                                
                                <!-- Questions Section -->
                                <h3 style="color: white; margin-bottom: 20px;"><i class="fa fa-question-circle"></i> Questions (8-12 required)</h3>
                                
                                <div id="questions-container">
                                    <?php foreach ($questions as $index => $question): ?>
                                        <div class="question-card" data-question-id="<?php echo ($index + 1); ?>">
                                            <input type="hidden" name="question-id-<?php echo ($index + 1); ?>" value="<?php echo $question['id_question']; ?>">
                                            
                                            <div class="question-header">
                                                <h4>Question <?php echo ($index + 1); ?></h4>
                                                <button type="button" class="btn-remove-question" onclick="removeQuestion(this)">
                                                    <i class="fa fa-trash"></i> Remove
                                                </button>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Question Text: *</label>
                                                <input type="text" 
                                                       class="form-control question-text" 
                                                       name="question-text-<?php echo ($index + 1); ?>" 
                                                       value="<?php echo htmlspecialchars($question['texte_question']); ?>">
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Option A: *</label>
                                                        <input type="text" 
                                                               class="form-control" 
                                                               name="option-a-<?php echo ($index + 1); ?>" 
                                                               value="<?php echo htmlspecialchars($question['option_a']); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Option B: *</label>
                                                        <input type="text" 
                                                               class="form-control" 
                                                               name="option-b-<?php echo ($index + 1); ?>" 
                                                               value="<?php echo htmlspecialchars($question['option_b']); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Option C: *</label>
                                                        <input type="text" 
                                                               class="form-control" 
                                                               name="option-c-<?php echo ($index + 1); ?>" 
                                                               value="<?php echo htmlspecialchars($question['option_c']); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Option D: *</label>
                                                        <input type="text" 
                                                               class="form-control" 
                                                               name="option-d-<?php echo ($index + 1); ?>" 
                                                               value="<?php echo htmlspecialchars($question['option_d']); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Correct Answer: *</label>
                                                <div class="radio-group">
                                                    <label><input type="radio" name="correct-answer-<?php echo ($index + 1); ?>" value="A" <?php echo $question['reponse_correcte'] === 'A' ? 'checked' : ''; ?>> A</label>
                                                    <label><input type="radio" name="correct-answer-<?php echo ($index + 1); ?>" value="B" <?php echo $question['reponse_correcte'] === 'B' ? 'checked' : ''; ?>> B</label>
                                                    <label><input type="radio" name="correct-answer-<?php echo ($index + 1); ?>" value="C" <?php echo $question['reponse_correcte'] === 'C' ? 'checked' : ''; ?>> C</label>
                                                    <label><input type="radio" name="correct-answer-<?php echo ($index + 1); ?>" value="D" <?php echo $question['reponse_correcte'] === 'D' ? 'checked' : ''; ?>> D</label>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Explanation (Optional):</label>
                                                <textarea class="form-control" 
                                                          name="explanation-<?php echo ($index + 1); ?>" 
                                                          rows="2"><?php echo htmlspecialchars($question['explication'] ?? ''); ?></textarea>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <button type="button" id="btn-add-question" class="btn btn-success" style="margin-top: 20px; padding: 12px 30px;">
                                    <i class="fa fa-plus"></i> Add Question
                                </button>
                                
                                <!-- Buttons -->
                                <div style="margin-top: 40px; text-align: center; border-top: 2px solid #444; padding-top: 30px;">
                                    <button type="submit" class="btn btn-primary" style="padding: 15px 40px; font-size: 18px; font-weight: 600; border-radius: 10px; margin-right: 15px;">
                                        <i class="fa fa-save"></i> Update Quiz
                                    </button>
                                    <a href="index.php?page=admin_quiz_manage" class="btn btn-secondary" style="padding: 15px 40px; font-size: 18px; font-weight: 600; border-radius: 10px;">
                                        <i class="fa fa-times"></i> Cancel
                                    </a>
                                </div>
                                
                            </form>
                            
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<style>
.question-card {
    background: #1f2122;
    border: 2px solid #e75e8d;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 25px;
    position: relative;
}

.question-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #333;
}

.question-header h4 {
    color: #e75e8d;
    margin: 0;
}

.btn-remove-question {
    background: #dc3545;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: 600;
}

.btn-remove-question:hover {
    background: #c82333;
    transform: scale(1.05);
}

.form-control {
    background-color: #27292a;
    color: white;
    border: 2px solid #333;
    padding: 12px;
    border-radius: 8px;
    width: 100%;
    margin-top: 5px;
}

.form-control:focus {
    border-color: #e75e8d;
    outline: none;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    color: white;
    font-weight: 600;
    display: block;
    margin-bottom: 5px;
}

.radio-group {
    display: flex;
    gap: 20px;
    margin-top: 10px;
}

.radio-group label {
    color: white;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.radio-group input[type="radio"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.btn-primary {
    background: linear-gradient(145deg, #e75e8d, #d64077);
    border: 2px solid #c4356a;
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(145deg, #d64077, #c4356a);
    transform: scale(1.05);
}

.btn-success {
    background: linear-gradient(145deg, #28a745, #218838);
    border: 2px solid #1e7e34;
    color: white;
    padding: 12px 30px;
    border-radius: 10px;
    font-weight: 600;
}

.btn-success:hover {
    background: linear-gradient(145deg, #218838, #1e7e34);
    transform: scale(1.05);
}

.btn-secondary {
    background: linear-gradient(145deg, #6c757d, #5a6268);
    border: 2px solid #545b62;
    color: white;
}

.btn-secondary:hover {
    background: linear-gradient(145deg, #5a6268, #545b62);
    transform: scale(1.05);
}
</style>

<script>
let questionCount = <?php echo count($questions); ?>;
const MIN_QUESTIONS = 5;
const MAX_QUESTIONS = 20;

// Image preview functionality
document.getElementById("quiz-image").addEventListener("change", function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("preview-img").src = e.target.result;
            document.getElementById("new-image-preview").style.display = "block";
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById("new-image-preview").style.display = "none";
    }
});

// Add new question
document.getElementById("btn-add-question").addEventListener("click", function() {
    if (questionCount >= MAX_QUESTIONS) {
        alert("Maximum " + MAX_QUESTIONS + " questions allowed!");
        return;
    }
    
    questionCount++;
    const container = document.getElementById("questions-container");
    
    const questionHTML = `
        <div class="question-card" data-question-id="${questionCount}">
            <input type="hidden" name="question-id-${questionCount}" value="">
            
            <div class="question-header">
                <h4>Question ${questionCount}</h4>
                <button type="button" class="btn-remove-question" onclick="removeQuestion(this)">
                    <i class="fa fa-trash"></i> Remove
                </button>
            </div>
            
            <div class="form-group">
                <label>Question Text: *</label>
                <input type="text" class="form-control question-text" name="question-text-${questionCount}">
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Option A: *</label>
                        <input type="text" class="form-control" name="option-a-${questionCount}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Option B: *</label>
                        <input type="text" class="form-control" name="option-b-${questionCount}">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Option C: *</label>
                        <input type="text" class="form-control" name="option-c-${questionCount}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Option D: *</label>
                        <input type="text" class="form-control" name="option-d-${questionCount}">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Correct Answer: *</label>
                <div class="radio-group">
                    <label><input type="radio" name="correct-answer-${questionCount}" value="A"> A</label>
                    <label><input type="radio" name="correct-answer-${questionCount}" value="B"> B</label>
                    <label><input type="radio" name="correct-answer-${questionCount}" value="C"> C</label>
                    <label><input type="radio" name="correct-answer-${questionCount}" value="D"> D</label>
                </div>
            </div>
            
            <div class="form-group">
                <label>Explanation (Optional):</label>
                <textarea class="form-control" name="explanation-${questionCount}" rows="2"></textarea>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML("beforeend", questionHTML);
    updateAddButton();
});

// Remove question
function removeQuestion(button) {
    if (questionCount <= MIN_QUESTIONS) {
        alert("Minimum " + MIN_QUESTIONS + " questions required!");
        return;
    }
    
    if (confirm("Are you sure you want to remove this question?")) {
        const questionCard = button.closest(".question-card");
        questionCard.remove();
        questionCount--;
        renumberQuestions();
        updateAddButton();
    }
}

// Renumber questions after removal
function renumberQuestions() {
    const questions = document.querySelectorAll(".question-card");
    questions.forEach((card, index) => {
        const newNumber = index + 1;
        card.setAttribute("data-question-id", newNumber);
        card.querySelector(".question-header h4").textContent = "Question " + newNumber;
        
        // Update input names
        const oldNumber = card.querySelector(".question-text").name.match(/\d+/)[0];
        card.querySelectorAll("[name*='question-']").forEach(input => {
            input.name = input.name.replace(/\d+/, newNumber);
        });
        card.querySelectorAll("[name*='option-']").forEach(input => {
            input.name = input.name.replace(/\d+/, newNumber);
        });
        card.querySelectorAll("[name*='correct-answer-']").forEach(input => {
            input.name = input.name.replace(/\d+/, newNumber);
        });
        card.querySelectorAll("[name*='explanation-']").forEach(input => {
            input.name = input.name.replace(/\d+/, newNumber);
        });
    });
}

// Update add button state
function updateAddButton() {
    const btn = document.getElementById("btn-add-question");
    if (questionCount >= MAX_QUESTIONS) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-ban"></i> Maximum Questions Reached';
        btn.style.background = "#666";
    } else {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-plus"></i> Add Question';
        btn.style.background = "linear-gradient(145deg, #28a745, #218838)";
    }
}

// Form validation
function validateTitle() {
    const title = document.getElementById("quiz-title").value.trim();
    return title.length >= 5;
}

function validateDescription() {
    const description = document.getElementById("quiz-description").value.trim();
    return description.length >= 20;
}

function validateCategory() {
    const category = document.getElementById("quiz-category").value;
    return category !== "";
}

// Form submit
document.getElementById("edit-quiz-form").addEventListener("submit", function(e) {
    e.preventDefault();
    
    if (!validateTitle()) {
        alert("Le titre doit contenir au moins 5 caractères");
        return false;
    }
    
    if (!validateDescription()) {
        alert("La description doit contenir au moins 20 caractères");
        return false;
    }
    
    if (!validateCategory()) {
        alert("Veuillez sélectionner une catégorie");
        return false;
    }
    
    if (questionCount < MIN_QUESTIONS) {
        alert("Veuillez avoir au moins " + MIN_QUESTIONS + " questions");
        return false;
    }
    
    if (questionCount > MAX_QUESTIONS) {
        alert("Maximum " + MAX_QUESTIONS + " questions autorisées");
        return false;
    }
    
    // Validate each question
    const questions = document.querySelectorAll(".question-card");
    for (let i = 0; i < questions.length; i++) {
        const card = questions[i];
        const questionNumber = i + 1;
        
        const questionText = card.querySelector(".question-text").value.trim();
        if (questionText.length < 10) {
            alert("Question " + questionNumber + ": Le texte doit contenir au moins 10 caractères");
            return false;
        }
        
        const optionA = card.querySelector("[name*='option-a']").value.trim();
        const optionB = card.querySelector("[name*='option-b']").value.trim();
        const optionC = card.querySelector("[name*='option-c']").value.trim();
        const optionD = card.querySelector("[name*='option-d']").value.trim();
        
        if (!optionA || !optionB || !optionC || !optionD) {
            alert("Question " + questionNumber + ": Toutes les options doivent être remplies");
            return false;
        }
        
        const correctAnswer = card.querySelector("[name*='correct-answer']:checked");
        if (!correctAnswer) {
            alert("Question " + questionNumber + ": Veuillez sélectionner la réponse correcte");
            return false;
        }
    }
    
    if (confirm("Êtes-vous sûr de vouloir mettre à jour ce quiz avec " + questionCount + " questions?")) {
        this.submit();
    }
});

// Initialize
updateAddButton();
</script>

<?php
include 'view/footer.php';
?>