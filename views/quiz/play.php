<?php
$pageTitle = "Play Quiz - " . htmlspecialchars($quiz['titre']);
$page = 'quiz_play';

// Serialize questions to JavaScript
$questionsJSON = json_encode($questions);

$customCSS = '
.quiz-container {
    background: linear-gradient(to bottom, #1f2122 0%, #27292a 100%);
    border-radius: 23px;
    padding: 40px;
    margin: 30px 0;
    min-height: 500px;
}

.quiz-header {
    text-align: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #333;
}

.quiz-header h2 {
    color: #fff;
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 15px;
}

.quiz-progress {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.progress-info {
    color: #e84057;
    font-size: 18px;
    font-weight: 600;
}

.progress-bar-container {
    width: 100%;
    height: 10px;
    background: #333;
    border-radius: 10px;
    overflow: hidden;
    margin: 10px 0;
}

.progress-bar-fill {
    height: 100%;
    background: linear-gradient(to right, #e84057 0%, #e75e3d 100%);
    transition: width 0.5s ease;
}

.question-card {
    background: #27292a;
    border-radius: 23px;
    padding: 30px;
    margin: 30px 0;
    display: none;
}

.question-card.active {
    display: block;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.question-text {
    color: #fff;
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 30px;
    line-height: 1.6;
}

.answer-option {
    background: #1f2122;
    border: 2px solid #333;
    border-radius: 23px;
    padding: 20px 25px;
    margin: 15px 0;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}

.answer-option:hover {
    border-color: #e84057;
    transform: translateX(10px);
}

.answer-option.selected {
    border-color: #e84057;
    background: rgba(232, 64, 87, 0.1);
}

.answer-option.correct {
    border-color: #28a745;
    background: rgba(40, 167, 69, 0.1);
    animation: correctPulse 0.5s ease;
}

.answer-option.incorrect {
    border-color: #dc3545;
    background: rgba(220, 53, 69, 0.1);
    animation: shake 0.5s ease;
}

.answer-option.disabled {
    cursor: not-allowed;
    opacity: 0.6;
}

@keyframes correctPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.02); }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.option-letter {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: #e84057;
    border-radius: 50%;
    color: #fff;
    font-weight: 700;
    margin-right: 15px;
    flex-shrink: 0;
}

.option-text {
    color: #fff;
    font-size: 16px;
    flex-grow: 1;
}

.answer-icon {
    margin-left: auto;
    font-size: 24px;
}

.answer-icon.correct {
    color: #28a745;
}

.answer-icon.incorrect {
    color: #dc3545;
}

.explanation-box {
    background: #1f2122;
    border-left: 4px solid #e84057;
    border-radius: 10px;
    padding: 20px;
    margin-top: 20px;
    display: none;
}

.explanation-box.show {
    display: block;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.explanation-box h5 {
    color: #e84057;
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 10px;
}

.explanation-box p {
    color: #ccc;
    font-size: 14px;
    line-height: 1.6;
}

.btn-next {
    background: linear-gradient(to right, #e84057 0%, #e75e3d 100%);
    border: none;
    color: #fff;
    padding: 15px 40px;
    border-radius: 23px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: none;
    margin: 30px auto 0;
}

.btn-next.show {
    display: block;
}

.btn-next:hover {
    transform: scale(1.05);
}

.result-container {
    text-align: center;
    padding: 40px;
    display: none;
}

.result-container.show {
    display: block;
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.result-score {
    font-size: 80px;
    font-weight: 700;
    color: #e84057;
    margin: 20px 0;
}

.result-message {
    font-size: 24px;
    color: #fff;
    margin: 20px 0;
}

.result-stats {
    display: flex;
    justify-content: center;
    gap: 40px;
    margin: 30px 0;
    flex-wrap: wrap;
}

.stat-box {
    background: #1f2122;
    border-radius: 23px;
    padding: 20px 30px;
}

.stat-box h4 {
    color: #666;
    font-size: 14px;
    margin-bottom: 10px;
}

.stat-box p {
    color: #fff;
    font-size: 24px;
    font-weight: 700;
}

.result-actions {
    margin-top: 30px;
}

.result-actions a {
    background: linear-gradient(to right, #e84057 0%, #e75e3d 100%);
    border: none;
    color: #fff;
    padding: 15px 30px;
    border-radius: 23px;
    font-weight: 600;
    text-decoration: none;
    margin: 0 10px;
    display: inline-block;
    transition: all 0.3s ease;
}

.result-actions a:hover {
    transform: scale(1.05);
    color: #fff;
}
';

include 'views/header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-content">

                <div class="quiz-container">
                    
                    <?php if(empty($questions)): ?>
                        <div class="alert alert-warning">No questions available for this quiz.</div>
                    <?php else: ?>
                        
                        <div id="quiz-content">
                            <div class="quiz-header">
                                <h2><?php echo htmlspecialchars($quiz['titre']); ?></h2>
                                <p style="color: #666;"><?php echo htmlspecialchars($quiz['description']); ?></p>
                                <div class="quiz-progress">
                                    <span class="progress-info">
                                        Question <span id="current-question">1</span> of <span id="total-questions"><?php echo count($questions); ?></span>
                                    </span>
                                </div>
                                <div class="progress-bar-container">
                                    <div class="progress-bar-fill" id="progress-bar" style="width: <?php echo (1 / count($questions)) * 100; ?>%;"></div>
                                </div>
                            </div>

                            <div id="question-container">
                                <!-- Questions will be displayed here one by one -->
                            </div>

                            <div style="text-align: center;">
                                <button class="btn-next" id="btn-next">
                                    Next Question <i class="fa fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <div class="result-container" id="result-container">
                            <h2 style="color: #fff; margin-bottom: 20px;">Quiz Completed!</h2>
                            <div class="result-score" id="final-score">0%</div>
                            <div class="result-message" id="result-message">Great Job!</div>
                            
                            <div class="result-stats">
                                <div class="stat-box">
                                    <h4>Correct Answers</h4>
                                    <p id="correct-count">0/0</p>
                                </div>
                                <div class="stat-box">
                                    <h4>Total Points</h4>
                                    <p id="total-points">0</p>
                                </div>
                                <div class="stat-box">
                                    <h4>Time Taken</h4>
                                    <p id="time-taken">0:00</p>
                                </div>
                            </div>

                            <!-- Buttons will be added dynamically by JavaScript -->
                        </div>
                        
                    <?php endif; ?>

                </div>

            </div>
        </div>
    </div>
</div>

<?php
$customJS = '
// Quiz data from PHP
const quizData = ' . $questionsJSON . ';
const quizId = ' . $quiz['id_quiz'] . ';
const totalQuestions = quizData.length;

let currentQuestionIndex = 0;
let score = 0;
let correctAnswers = 0;
let userAnswers = {};
let startTime = Date.now();

// Initialize quiz
document.addEventListener("DOMContentLoaded", function() {
    if (quizData && quizData.length > 0) {
        displayQuestion();
    }
});

// Display current question
function displayQuestion() {
    const question = quizData[currentQuestionIndex];
    const container = document.getElementById("question-container");
    
    // Update progress
    document.getElementById("current-question").textContent = currentQuestionIndex + 1;
    const progress = ((currentQuestionIndex + 1) / totalQuestions) * 100;
    document.getElementById("progress-bar").style.width = progress + "%";
    
    // Build question HTML
    const questionHTML = `
        <div class="question-card active">
            <div class="question-text">
                <strong>Question ${currentQuestionIndex + 1}:</strong><br>
                ${escapeHtml(question.texte_question)}
            </div>

            <div class="answers-container">
                <div class="answer-option" data-answer="A" onclick="selectAnswer(\'A\')">
                    <span class="option-letter">A</span>
                    <span class="option-text">${escapeHtml(question.option_a)}</span>
                </div>
                <div class="answer-option" data-answer="B" onclick="selectAnswer(\'B\')">
                    <span class="option-letter">B</span>
                    <span class="option-text">${escapeHtml(question.option_b)}</span>
                </div>
                <div class="answer-option" data-answer="C" onclick="selectAnswer(\'C\')">
                    <span class="option-letter">C</span>
                    <span class="option-text">${escapeHtml(question.option_c)}</span>
                </div>
                <div class="answer-option" data-answer="D" onclick="selectAnswer(\'D\')">
                    <span class="option-letter">D</span>
                    <span class="option-text">${escapeHtml(question.option_d)}</span>
                </div>
            </div>

            <div class="explanation-box" id="explanation-box">
                <h5><i class="fa fa-lightbulb"></i> Explanation</h5>
                <p id="explanation-text"></p>
            </div>
        </div>
    `;
    
    container.innerHTML = questionHTML;
    document.getElementById("btn-next").classList.remove("show");
}

// Select answer
function selectAnswer(answer) {
    const question = quizData[currentQuestionIndex];
    const correct = question.reponse_correcte.toUpperCase();
    
    // Store user answer
    userAnswers[question.id_question] = answer;
    
    // Disable all options
    const allOptions = document.querySelectorAll(".answer-option");
    allOptions.forEach(option => {
        option.style.pointerEvents = "none";
        option.classList.add("disabled");
    });
    
    // Get selected option
    const selectedOption = document.querySelector(`[data-answer="${answer}"]`);
    selectedOption.classList.add("selected");
    
    // Check if correct
    if (answer === correct) {
        selectedOption.classList.add("correct");
        selectedOption.innerHTML += \'<i class="fa fa-check answer-icon correct"></i>\';
        correctAnswers++;
        score += parseInt(question.points);
    } else {
        selectedOption.classList.add("incorrect");
        selectedOption.innerHTML += \'<i class="fa fa-times answer-icon incorrect"></i>\';
        
        // Show correct answer
        const correctOption = document.querySelector(`[data-answer="${correct}"]`);
        correctOption.classList.add("correct");
        correctOption.innerHTML += \'<i class="fa fa-check answer-icon correct"></i>\';
    }
    
    // Show explanation if exists
    if (question.explication && question.explication.trim() !== "") {
        document.getElementById("explanation-text").textContent = question.explication;
        document.getElementById("explanation-box").classList.add("show");
    }
    
    // Show next button
    document.getElementById("btn-next").classList.add("show");
}

// Next question button
document.getElementById("btn-next").addEventListener("click", function() {
    currentQuestionIndex++;
    
    if (currentQuestionIndex < totalQuestions) {
        displayQuestion();
    } else {
        showResults();
    }
});

// Show final results
function showResults() {
    const percentage = Math.round((correctAnswers / totalQuestions) * 100);
    const timeTaken = Math.floor((Date.now() - startTime) / 1000);
    const minutes = Math.floor(timeTaken / 60);
    const seconds = timeTaken % 60;
    
    // Hide quiz content
    document.getElementById("quiz-content").style.display = "none";
    
    // Show results
    const resultContainer = document.getElementById("result-container");
    resultContainer.classList.add("show");
    
    document.getElementById("final-score").textContent = percentage + "%";
    document.getElementById("correct-count").textContent = correctAnswers + "/" + totalQuestions;
    document.getElementById("total-points").textContent = score;
    document.getElementById("time-taken").textContent = minutes + ":" + (seconds < 10 ? "0" : "") + seconds;
    
    // Set message based on score
    let message = "";
    if (percentage >= 90) {
        message = "Outstanding! You are a Gaming Legend! 🏆";
    } else if (percentage >= 70) {
        message = "Great Job! You know your games! 🎮";
    } else if (percentage >= 50) {
        message = "Good effort! Keep learning! 📚";
    } else {
        message = "Keep practicing! You will get better! 💪";
    }
    document.getElementById("result-message").textContent = message;
    
    // Add navigation buttons with enhanced styling
    const buttonsHTML = `
        <div style="margin-top: 40px; text-align: center; display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
            <a href="index.php?page=quiz_play&id=${quizId}" 
               class="nav-button replay-button" 
               style="padding: 18px 45px; 
                      font-size: 18px; 
                      text-decoration: none; 
                      display: inline-flex; 
                      align-items: center; 
                      gap: 10px;
                      background: linear-gradient(145deg, #e75e8d, #d64077); 
                      border: 3px solid #c4356a; 
                      color: white; 
                      border-radius: 15px; 
                      font-weight: 700;
                      box-shadow: 0 8px 20px rgba(231, 94, 141, 0.4);
                      transition: all 0.3s ease;
                      cursor: pointer;">
                <i class="fa fa-refresh"></i> Replay Quiz
            </a>
            <a href="index.php?page=quiz_list" 
               class="nav-button back-button" 
               style="padding: 18px 45px; 
                      font-size: 18px; 
                      text-decoration: none; 
                      display: inline-flex; 
                      align-items: center; 
                      gap: 10px;
                      background: linear-gradient(145deg, #27292a, #1f2122); 
                      border: 3px solid #e75e8d; 
                      color: white; 
                      border-radius: 15px; 
                      font-weight: 700;
                      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
                      transition: all 0.3s ease;
                      cursor: pointer;">
                <i class="fa fa-arrow-left"></i> Back to Quiz List
            </a>
        </div>
        <style>
            .nav-button:hover {
                transform: translateY(-3px);
                box-shadow: 0 12px 25px rgba(231, 94, 141, 0.6);
            }
            .replay-button:hover {
                background: linear-gradient(145deg, #ff6b9d, #e75e8d);
                border-color: #ff6b9d;
            }
            .back-button:hover {
                background: linear-gradient(145deg, #e75e8d, #d64077);
                border-color: #ff6b9d;
            }
        </style>
    `;
    
    document.getElementById("result-container").insertAdjacentHTML("beforeend", buttonsHTML);
    
    // Submit results to server in background (using hidden iframe - NO AJAX!)
    submitResultsToServer(timeTaken);
}

// Submit results to server WITHOUT leaving page (using iframe technique)
function submitResultsToServer(timeTaken) {
    // Create hidden iframe for form submission
    const iframe = document.createElement("iframe");
    iframe.name = "hidden_iframe";
    iframe.style.display = "none";
    document.body.appendChild(iframe);
    
    // Create form that targets the iframe
    const form = document.createElement("form");
    form.method = "POST";
    form.action = "index.php?page=quiz_submit";
    form.target = "hidden_iframe"; // Submit to iframe, not main window
    form.style.display = "none";
    
    // Add quiz ID
    const quizInput = document.createElement("input");
    quizInput.type = "hidden";
    quizInput.name = "id_quiz";
    quizInput.value = quizId;
    form.appendChild(quizInput);
    
    // Add time
    const timeInput = document.createElement("input");
    timeInput.type = "hidden";
    timeInput.name = "temps_ecoule";
    timeInput.value = timeTaken;
    form.appendChild(timeInput);
    
    // Add all answers
    for (const questionId in userAnswers) {
        const answerInput = document.createElement("input");
        answerInput.type = "hidden";
        answerInput.name = "answer_" + questionId;
        answerInput.value = userAnswers[questionId];
        form.appendChild(answerInput);
    }
    
    // Add form to page and submit to iframe
    document.body.appendChild(form);
    form.submit();
    
    // Note: Form submits to iframe, page stays on results screen
    console.log("✓ Results submitted to database in background");
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
}
';

include 'views/footer.php';
?>