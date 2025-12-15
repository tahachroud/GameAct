<?php
$pageTitle = "Create Quiz - Gaming Quiz";
$page = 'quiz_create';

$customCSS = '
.create-quiz-container {
    background: linear-gradient(to bottom, #1f2122 0%, #27292a 100%);
    border-radius: 23px;
    padding: 40px;
    margin: 30px 0;
}

.form-section {
    margin-bottom: 40px;
}

.form-section h3 {
    color: #e84057;
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #333;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 10px;
    display: block;
}

.form-group label .required {
    color: #e84057;
    margin-left: 5px;
}

.form-control, .form-select {
    background: #1f2122;
    border: 2px solid #333;
    border-radius: 15px;
    padding: 15px 20px;
    color: #fff;
    font-size: 14px;
    width: 100%;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    background: #1f2122;
    border-color: #e84057;
    color: #fff;
    outline: none;
}

.form-control::placeholder {
    color: #666;
}

textarea.form-control {
    min-height: 120px;
    resize: vertical;
}

.error-message {
    color: #dc3545;
    font-size: 13px;
    margin-top: 5px;
    display: none;
}

.question-card {
    background: #1f2122;
    border-radius: 23px;
    padding: 30px;
    margin-bottom: 20px;
    border: 2px solid #333;
    position: relative;
}

.question-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.question-number {
    color: #e84057;
    font-size: 20px;
    font-weight: 700;
}

.btn-remove-question {
    background: #dc3545;
    border: none;
    color: #fff;
    padding: 8px 15px;
    border-radius: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-remove-question:hover {
    background: #c82333;
}

.option-input-group {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.option-label {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    background: #e84057;
    border-radius: 50%;
    color: #fff;
    font-weight: 700;
    margin-right: 15px;
}

.radio-group {
    display: flex;
    gap: 20px;
    margin-top: 10px;
}

.radio-option {
    display: flex;
    align-items: center;
}

.radio-option input[type="radio"] {
    margin-right: 8px;
    cursor: pointer;
    width: 18px;
    height: 18px;
}

.btn-add-question {
    background: #28a745;
    border: none;
    color: #fff;
    padding: 15px 30px;
    border-radius: 23px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-add-question:hover {
    background: #218838;
    transform: scale(1.05);
}

.btn-submit {
    background: linear-gradient(to right, #e84057 0%, #e75e3d 100%);
    border: none;
    color: #fff;
    padding: 15px 40px;
    border-radius: 23px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-submit:hover {
    transform: scale(1.05);
}

.btn-cancel {
    background: #666;
    border: none;
    color: #fff;
    padding: 15px 40px;
    border-radius: 23px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.btn-upload {
    background: linear-gradient(105deg, #6a82fb 0%, #fc5c7d 100%);
    color: #fff;
    border: none;
    padding: 12px 30px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-upload:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(106, 130, 251, 0.4);
}

.btn-remove-image {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #dc3545;
    color: #fff;
    border: none;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 16px;
    transition: all 0.3s ease;
}

.btn-remove-image:hover {
    background: #c82333;
    transform: scale(1.1);
}

.question-counter {
    background: #1f2122;
    border-radius: 15px;
    padding: 15px 25px;
    text-align: center;
    margin-bottom: 30px;
}

.question-counter span {
    color: #e84057;
    font-size: 24px;
    font-weight: 700;
}
';

include 'view/header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-content">

                <div class="create-quiz-container">
                    
                    <div style="text-align: center; margin-bottom: 30px;">
                        <h2 style="color: #fff; font-size: 32px; font-weight: 700;">
                            <i class="fa fa-plus-circle" style="color: #e84057;"></i> Create Your Quiz
                        </h2>
                        <p style="color: #666; font-size: 16px;">Share your gaming knowledge with the community</p>
                    </div>

                    <?php if(!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <strong>Error:</strong>
                            <ul>
                                <?php foreach($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="quiz_list.php?page=quiz_create" id="create-quiz-form" enctype="multipart/form-data">
                        
                        <!-- Quiz Information Section -->
                        <div class="form-section">
                            <h3><i class="fa fa-info-circle"></i> Quiz Information</h3>
                            
                            <div class="form-group">
                                <label for="quiz-title">Quiz Title <span class="required">*</span></label>
                                <input type="text" id="quiz-title" name="quiz-title" class="form-control" 
                                       placeholder="Enter an engaging title for your quiz" maxlength="100">
                                <span class="error-message" id="error-title">Le titre doit contenir au moins 5 caractères</span>
                            </div>

                            <div class="form-group">
                                <label for="quiz-description">Description <span class="required">*</span></label>
                                <textarea id="quiz-description" name="quiz-description" class="form-control" 
                                          placeholder="Describe what your quiz is about..." maxlength="500"></textarea>
                                <span class="error-message" id="error-description">La description doit contenir au moins 20 caractères</span>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quiz-category">Category <span class="required">*</span></label>
                                        <select id="quiz-category" name="quiz-category" class="form-select">
                                            <option value="">Select a category</option>
                                            <option value="retro">Retro</option>
                                            <option value="action">Action</option>
                                            <option value="strategy">Strategy</option>
                                            <option value="rpg">RPG</option>
                                            <option value="fps">FPS</option>
                                            <option value="moba">MOBA</option>
                                            <option value="sports">Sports</option>
                                            <option value="racing">Racing</option>
                                        </select>
                                        <span class="error-message" id="error-category">Veuillez sélectionner une catégorie</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quiz-difficulty">Difficulty <span class="required">*</span></label>
                                        <select id="quiz-difficulty" name="quiz-difficulty" class="form-select">
                                            <option value="easy">Easy</option>
                                            <option value="medium" selected>Medium</option>
                                            <option value="hard">Hard</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Quiz Image Upload Section -->
                        <div class="form-section">
                            <h3><i class="fa fa-image"></i> Quiz Image (Optional)</h3>
                            <div class="form-group">
                                <label for="quiz-image">Upload Quiz Cover Image</label>
                                <div class="custom-file-upload">
                                    <input type="file" 
                                           id="quiz-image" 
                                           name="quiz-image"
                                           accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                                           style="display: none;">
                                    <button type="button" class="btn-upload" id="btn-upload">
                                        <i class="fa fa-cloud-upload"></i> Choose Image File
                                    </button>
                                    <span id="file-name" style="color: #999; margin-left: 15px;">No file chosen</span>
                                </div>
                                <small style="color: #666; display: block; margin-top: 10px;">
                                    <i class="fa fa-info-circle"></i> Accepted formats: JPG, PNG, GIF, WEBP (Max 5MB)
                                </small>
                                
                                <!-- Image Preview -->
                                <div id="image-preview" style="display: none; margin-top: 20px;">
                                    <p style="color: #999; margin-bottom: 10px;">Image Preview:</p>
                                    <div style="position: relative; display: inline-block;">
                                        <img id="preview-img" src="" alt="Preview" style="max-width: 400px; max-height: 300px; border-radius: 15px; border: 3px solid #e84057;">
                                        <button type="button" class="btn-remove-image" id="remove-image">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Questions Section -->
                        <div class="form-section">
                            <h3><i class="fa fa-question-circle"></i> Quiz Questions</h3>
                            
                            <div class="question-counter">
                                <span id="question-count">0</span>
                                <p style="color: #666; margin: 5px 0 0;">Questions added (Minimum: 5, Maximum: 20)</p>
                            </div>

                            <div id="questions-container"></div>

                            <button type="button" class="btn-add-question" id="btn-add-question">
                                <i class="fa fa-plus"></i> Add Question
                            </button>
                        </div>

                        <!-- Form Actions -->
                        <div style="text-align: center; margin-top: 40px; padding-top: 30px; border-top: 2px solid #333;">
                            <button type="submit" class="btn-submit">
                                <i class="fa fa-check"></i> Create Quiz
                            </button>
                            <a href="quiz_list.php?page=quiz_list" class="btn-cancel" style="margin-left: 20px;">
                                <i class="fa fa-times"></i> Cancel
                            </a>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>
</div>

<?php
$customJS = '
let questionCount = 0;
const MIN_QUESTIONS = 5;
const MAX_QUESTIONS = 20;

// ==========================================
// IMAGE UPLOAD HANDLERS
// ==========================================

// Click upload button to trigger file input
document.getElementById("btn-upload").addEventListener("click", function() {
    document.getElementById("quiz-image").click();
});

// Handle file selection
document.getElementById("quiz-image").addEventListener("change", function(e) {
    const file = e.target.files[0];
    if (file) {
        // Update file name display
        document.getElementById("file-name").textContent = file.name;
        
        // Validate file
        const validTypes = ["image/jpeg", "image/jpg", "image/png", "image/gif", "image/webp"];
        const maxSize = 5 * 1024 * 1024; // 5MB
        
        if (!validTypes.includes(file.type)) {
            alert("Format non valide! Veuillez choisir JPG, PNG, GIF ou WEBP.");
            this.value = "";
            document.getElementById("file-name").textContent = "No file chosen";
            return;
        }
        
        if (file.size > maxSize) {
            alert("Fichier trop volumineux! Maximum 5MB.");
            this.value = "";
            document.getElementById("file-name").textContent = "No file chosen";
            return;
        }
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("preview-img").src = e.target.result;
            document.getElementById("image-preview").style.display = "block";
        };
        reader.readAsDataURL(file);
    }
});

// Remove image
document.getElementById("remove-image").addEventListener("click", function() {
    document.getElementById("quiz-image").value = "";
    document.getElementById("file-name").textContent = "No file chosen";
    document.getElementById("image-preview").style.display = "none";
});

// ==========================================
// QUESTIONS MANAGEMENT
// ==========================================

// Add first question on page load
window.addEventListener("load", function() {
    addQuestion();
});

// Add Question
document.getElementById("btn-add-question").addEventListener("click", addQuestion);

function addQuestion() {
    if (questionCount >= MAX_QUESTIONS) {
        alert(`Maximum ${MAX_QUESTIONS} questions autorisées`);
        return;
    }

    questionCount++;
    updateQuestionCount();
    
    const questionCard = document.createElement("div");
    questionCard.className = "question-card";
    questionCard.setAttribute("data-question-id", questionCount);
    
    questionCard.innerHTML = `
        <div class="question-header">
            <span class="question-number">Question ${questionCount}</span>
            <button type="button" class="btn-remove-question" onclick="removeQuestion(${questionCount})">
                <i class="fa fa-trash"></i> Remove
            </button>
        </div>

        <div class="form-group">
            <label>Question Text <span class="required">*</span></label>
            <input type="text" name="question-text-${questionCount}" class="form-control" 
                   placeholder="Enter your question here..." maxlength="500" required>
        </div>

        <div class="form-group">
            <label>Answer Options <span class="required">*</span></label>
            
            <div class="option-input-group">
                <span class="option-label">A</span>
                <input type="text" name="option-a-${questionCount}" class="form-control" 
                       placeholder="Option A" maxlength="200" required>
            </div>
            
            <div class="option-input-group">
                <span class="option-label">B</span>
                <input type="text" name="option-b-${questionCount}" class="form-control" 
                       placeholder="Option B" maxlength="200" required>
            </div>
            
            <div class="option-input-group">
                <span class="option-label">C</span>
                <input type="text" name="option-c-${questionCount}" class="form-control" 
                       placeholder="Option C" maxlength="200" required>
            </div>
            
            <div class="option-input-group">
                <span class="option-label">D</span>
                <input type="text" name="option-d-${questionCount}" class="form-control" 
                       placeholder="Option D" maxlength="200" required>
            </div>
        </div>

        <div class="form-group">
            <label>Correct Answer <span class="required">*</span></label>
            <div class="radio-group">
                <div class="radio-option">
                    <input type="radio" id="correct-a-${questionCount}" name="correct-answer-${questionCount}" value="A" required>
                    <label for="correct-a-${questionCount}" style="margin: 0; color: #fff;">A</label>
                </div>
                <div class="radio-option">
                    <input type="radio" id="correct-b-${questionCount}" name="correct-answer-${questionCount}" value="B">
                    <label for="correct-b-${questionCount}" style="margin: 0; color: #fff;">B</label>
                </div>
                <div class="radio-option">
                    <input type="radio" id="correct-c-${questionCount}" name="correct-answer-${questionCount}" value="C">
                    <label for="correct-c-${questionCount}" style="margin: 0; color: #fff;">C</label>
                </div>
                <div class="radio-option">
                    <input type="radio" id="correct-d-${questionCount}" name="correct-answer-${questionCount}" value="D">
                    <label for="correct-d-${questionCount}" style="margin: 0; color: #fff;">D</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Explanation (Optional)</label>
            <textarea name="explanation-${questionCount}" class="form-control" 
                      placeholder="Explain why this is the correct answer..." maxlength="300"></textarea>
        </div>
    `;
    
    document.getElementById("questions-container").appendChild(questionCard);
    updateAddQuestionButton();
}

function removeQuestion(questionId) {
    const questionCard = document.querySelector(`[data-question-id="${questionId}"]`);
    if (questionCard) {
        questionCard.remove();
        questionCount--;
        updateQuestionCount();
        renumberQuestions();
        updateAddQuestionButton();
    }
}

function updateQuestionCount() {
    document.getElementById("question-count").textContent = questionCount;
}

function renumberQuestions() {
    const questions = document.querySelectorAll(".question-card");
    questions.forEach((question, index) => {
        question.setAttribute("data-question-id", index + 1);
        question.querySelector(".question-number").textContent = `Question ${index + 1}`;
    });
    questionCount = questions.length;
}

function updateAddQuestionButton() {
    const btn = document.getElementById("btn-add-question");
    if (questionCount >= MAX_QUESTIONS) {
        btn.disabled = true;
        btn.innerHTML = "<i class=\"fa fa-ban\"></i> Maximum Questions Reached";
        btn.style.background = "#666";
    } else {
        btn.disabled = false;
        btn.innerHTML = "<i class=\"fa fa-plus\"></i> Add Question";
        btn.style.background = "#28a745";
    }
}

// ==========================================
// IMAGE PREVIEW
// ==========================================
document.getElementById("quiz-image").addEventListener("change", function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("preview-img").src = e.target.result;
            document.getElementById("image-preview").style.display = "block";
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById("image-preview").style.display = "none";
    }
});

// ==========================================
// CONTRÔLES DE SAISIE (JavaScript Validation)
// ==========================================

// Real-time validation for title
document.getElementById("quiz-title").addEventListener("input", function() {
    const title = this.value.trim();
    const errorElement = document.getElementById("error-title");
    
    // Check length
    if (title.length > 0 && title.length < 5) {
        errorElement.textContent = "Le titre doit contenir au moins 5 caractères";
        errorElement.style.display = "block";
        this.style.borderColor = "#dc3545";
        return;
    }
    
    // Check if starts with letter (a-z, A-Z)
    if (title.length > 0 && !/^[a-zA-Z]/.test(title)) {
        errorElement.textContent = "Le titre doit commencer par une lettre (a-z, A-Z)";
        errorElement.style.display = "block";
        this.style.borderColor = "#dc3545";
        return;
    }
    
    // Valid
    errorElement.style.display = "none";
    this.style.borderColor = "#333";
});

document.getElementById("quiz-title").addEventListener("blur", function() {
    validateTitle();
});

// Helper function to check if text starts with a letter
function startsWithLetter(text) {
    // Must start with a letter (a-z or A-Z)
    return /^[a-zA-Z]/.test(text);
}

function validateTitle() {
    const title = document.getElementById("quiz-title").value.trim();
    
    if (title.length < 5) {
        document.getElementById("error-title").textContent = "Le titre doit contenir au moins 5 caractères";
        showError("title");
        return false;
    }
    
    if (!startsWithLetter(title)) {
        document.getElementById("error-title").textContent = "Le titre doit commencer par une lettre (a-z, A-Z)";
        showError("title");
        return false;
    }
    
    hideError("title");
    return true;
}


// Real-time validation for description
document.getElementById("quiz-description").addEventListener("input", function() {
    const description = this.value.trim();
    const errorElement = document.getElementById("error-description");
    
    // Check length
    if (description.length > 0 && description.length < 20) {
        errorElement.textContent = "La description doit contenir au moins 20 caractères";
        errorElement.style.display = "block";
        this.style.borderColor = "#dc3545";
        return;
    }
    
    // Check if starts with letter (a-z, A-Z)
    if (description.length > 0 && !/^[a-zA-Z]/.test(description)) {
        errorElement.textContent = "La description doit commencer par une lettre (a-z, A-Z)";
        errorElement.style.display = "block";
        this.style.borderColor = "#dc3545";
        return;
    }
    
    // Valid
    errorElement.style.display = "none";
    this.style.borderColor = "#333";
});

document.getElementById("quiz-description").addEventListener("blur", function() {
    validateDescription();
});

function validateDescription() {
    const description = document.getElementById("quiz-description").value.trim();
    
    if (description.length < 20) {
        document.getElementById("error-description").textContent = "La description doit contenir au moins 20 caractères";
        showError("description");
        return false;
    }
    
    if (!startsWithLetter(description)) {
        document.getElementById("error-description").textContent = "La description doit commencer par une lettre (a-z, A-Z)";
        showError("description");
        return false;
    }
    
    hideError("description");
    return true;
}

// Real-time validation for category
document.getElementById("quiz-category").addEventListener("change", function() {
    validateCategory();
});

function validateCategory() {
    const category = document.getElementById("quiz-category").value;
    if (!category || category === "") {
        showError("category");
        return false;
    }
    hideError("category");
    return true;
}

// Form validation on submit
let isSubmitting = false; // Prevent double submission

document.getElementById("create-quiz-form").addEventListener("submit", function(e) {
    // Prevent double submission
    if (isSubmitting) {
        e.preventDefault();
        return false;
    }
    
    let isValid = true;
    
    // Validate quiz info
    if (!validateTitle()) isValid = false;
    if (!validateDescription()) isValid = false;
    if (!validateCategory()) isValid = false;
    
    // Validate question count
    if (questionCount < MIN_QUESTIONS) {
        e.preventDefault();
        alert(`Veuillez ajouter au moins ${MIN_QUESTIONS} questions à votre quiz.`);
        return false;
    }
    
    // Validate each question
    const questions = document.querySelectorAll(".question-card");
    
    questions.forEach((question, index) => {
        const questionId = index + 1;
        
        // Validate question text - FIXED SELECTOR
const questionInput = question.querySelector(`input[name="question-text-${questionId}"]`);
if (!questionInput) {
    alert(`Question ${questionId}: Erreur - impossible de trouver le champ question`);
    isValid = false;
    return;
}

const questionText = questionInput.value.trim();
if (questionText.length < 10) {
    alert(`Question ${questionId}: Le texte doit contenir au moins 10 caractères`);
    isValid = false;
} else if (!startsWithLetter(questionText)) {
            alert(`Question ${questionId}: Le texte doit commencer par une lettre (a-z, A-Z)`);
            isValid = false;
        }
        
        // Validate options
        const optionA = question.querySelector(`[name="option-a-${questionId}"]`).value.trim();
        const optionB = question.querySelector(`[name="option-b-${questionId}"]`).value.trim();
        const optionC = question.querySelector(`[name="option-c-${questionId}"]`).value.trim();
        const optionD = question.querySelector(`[name="option-d-${questionId}"]`).value.trim();
        
        if (!optionA || !optionB || !optionC || !optionD || 
            optionA.length < 2 || optionB.length < 2 || 
            optionC.length < 2 || optionD.length < 2) {
            alert(`Question ${questionId}: Toutes les options doivent contenir au moins 2 caractères`);
            isValid = false;
        } else if (!startsWithLetter(optionA) || !startsWithLetter(optionB) ||
                   !startsWithLetter(optionC) || !startsWithLetter(optionD)) {
            alert(`Question ${questionId}: Les options doivent commencer par une lettre (a-z, A-Z)`);
            isValid = false;
        }
        
        // Validate correct answer selection
        const correctAnswer = question.querySelector(`input[name="correct-answer-${questionId}"]:checked`);
        if (!correctAnswer) {
            alert(`Question ${questionId}: Veuillez sélectionner la réponse correcte`);
            isValid = false;
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert("Veuillez corriger les erreurs dans le formulaire.");
        return false;
    }
    
    // Confirmation before submit
    if (!confirm("Êtes-vous sûr de vouloir créer ce quiz?")) {
        e.preventDefault();
        return false;
    }
    
    // If we get here, validation passed and user confirmed
    isSubmitting = true; // Set flag to prevent double submission
    // Form will submit naturally (do not preventDefault)
});

function showError(fieldName) {
    const errorElement = document.getElementById(`error-${fieldName}`);
    const inputElement = document.getElementById(`quiz-${fieldName}`);
    
    if (errorElement) {
        errorElement.style.display = "block";
    }
    if (inputElement) {
        inputElement.style.borderColor = "#dc3545";
    }
}

function hideError(fieldName) {
    const errorElement = document.getElementById(`error-${fieldName}`);
    const inputElement = document.getElementById(`quiz-${fieldName}`);
    
    if (errorElement) {
        errorElement.style.display = "none";
    }
    if (inputElement) {
        inputElement.style.borderColor = "#333";
    }
}
';

include 'view/footer.php';
?>