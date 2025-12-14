<?php
$pageTitle = "Play Quiz - " . htmlspecialchars($quiz['titre']);
$page = 'quiz_play';

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
    position: relative;
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

.voice-controls {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-bottom: 20px;
}

.voice-btn {
    background: linear-gradient(145deg, #27292a, #1f2122);
    border: 2px solid #e75e8d;
    color: #fff;
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.voice-btn:hover {
    background: linear-gradient(145deg, #e75e8d, #d64077);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(231, 94, 141, 0.4);
}

.voice-btn.speaking {
    background: linear-gradient(145deg, #e75e8d, #d64077);
    animation: pulse-voice 1s infinite;
}

@keyframes pulse-voice {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.voice-btn i {
    font-size: 16px;
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

.timer-container {
    position: fixed;
    top: 100px;
    right: 30px;
    background: linear-gradient(145deg, #27292a, #1f2122);
    border: 3px solid #e75e8d;
    border-radius: 15px;
    padding: 20px 30px;
    z-index: 1000;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
    transition: all 0.3s ease;
}

.timer-container.warning {
    border-color: #ff4444;
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.timer-label {
    color: #aaa;
    font-size: 14px;
    margin-bottom: 5px;
    text-align: center;
}

.timer-display {
    font-size: 48px;
    font-weight: bold;
    color: #4CAF50;
    text-align: center;
    font-family: monospace;
}

.timer-display.warning {
    color: #ff4444;
}

.powerup-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin: 20px 0;
}

.powerup-btn {
    background: linear-gradient(145deg, #27292a, #1f2122);
    border: 2px solid #e75e8d;
    color: #fff;
    padding: 12px 25px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 15px;
}

.powerup-btn:hover:not(.used) {
    background: linear-gradient(145deg, #e75e8d, #d64077);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(231, 94, 141, 0.4);
}

.powerup-btn.used {
    background: #1a1a1a;
    border-color: #444;
    color: #666;
    cursor: not-allowed;
    opacity: 0.5;
}

.powerup-btn i {
    margin-right: 5px;
}
';

include 'view/header.php';
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
                                
                                <?php
                                // Calculate duration based on number of questions
                                $questionCount = count($questions);
                                $durationSeconds = 180 + (max(0, $questionCount - 8) * 15);
                                $durationMinutes = ceil($durationSeconds / 60);
                                $displayMinutes = floor($durationSeconds / 60);
                                $displaySeconds = $durationSeconds % 60;
                                ?>
                                
                                <div class="timer-container" id="timer-container">
                                    <div class="timer-label">Time Remaining</div>
                                    <div class="timer-display" id="timer-display"><?php echo $displayMinutes . ':' . str_pad($displaySeconds, 2, '0', STR_PAD_LEFT); ?></div>
                                </div>
                                
                                <div class="quiz-progress">
                                    <span class="progress-info">
                                        Question <span id="current-question">1</span> of <span id="total-questions"><?php echo count($questions); ?></span> | Duration: <?php echo $durationMinutes; ?> min
                                    </span>
                                </div>
                                <div class="progress-bar-container">
                                    <div class="progress-bar-fill" id="progress-bar" style="width: <?php echo (1 / count($questions)) * 100; ?>%;"></div>
                                </div>
                            </div>

                            <div id="question-container">
                            </div>

                            <div style="text-align: center;">
                                <button class="btn-next" id="btn-next">
                                    Next Question <i class="fa fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <div class="result-container" id="result-container">
                        </div>

                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
$customJS = '
const quizData = ' . $questionsJSON . ';
const quizId = ' . $quiz['id_quiz'] . ';
const userId = ' . (isset($_SESSION['user_id']) && $_SESSION['user_id'] ? intval($_SESSION['user_id']) : 13) . ';
const totalQuestions = quizData.length;

// Web Speech API
let speechSynthesis = window.speechSynthesis;
let currentSpeech = null;

// Calculate duration based on number of questions
function calculateQuizDuration(questionCount) {
    const seconds = 180 + (Math.max(0, questionCount - 8) * 15);
    return seconds;
}

const quizDurationSeconds = calculateQuizDuration(totalQuestions);

let currentQuestionIndex = 0;
let score = 0;
let correctAnswers = 0;
let userAnswers = {};
let startTime = Date.now();

let timeRemaining = quizDurationSeconds;
let timerInterval = null;
let quizFailed = false;
let fiftyFiftyUsed = false;
let hintUsed = false;

document.addEventListener("DOMContentLoaded", function() {
    if (quizData && quizData.length > 0) {
        displayQuestion();
    }
});

function startTimer() {
    timerInterval = setInterval(function() {
        if (quizFailed) {
            clearInterval(timerInterval);
            return;
        }
        
        timeRemaining--;
        
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        const display = minutes + ":" + (seconds < 10 ? "0" : "") + seconds;
        
        document.getElementById("timer-display").textContent = display;
        
        if (timeRemaining <= 60 && timeRemaining > 0) {
            document.getElementById("timer-display").classList.add("warning");
            document.getElementById("timer-container").classList.add("warning");
        }
        
        if (timeRemaining <= 0) {
            clearInterval(timerInterval);
            quizFailed = true;
            timeUp();
        }
    }, 1000);
}

function timeUp() {
    stopSpeech();
    document.getElementById("quiz-content").style.display = "none";
    const resultHTML = `
        <div style="text-align: center; padding: 40px;">
            <div style="font-size: 80px; margin-bottom: 20px;">⏰</div>
            <h2 style="color: #ff4444; font-size: 36px; margin-bottom: 15px;">Time is Up!</h2>
            <p style="color: #aaa; font-size: 18px; margin-bottom: 30px;">You ran out of time. Quiz failed.</p>
            <div style="background: linear-gradient(145deg, #ff4444, #cc0000); padding: 30px; border-radius: 15px; margin: 30px auto; max-width: 300px;">
                <div style="font-size: 72px; font-weight: bold; color: white;">0%</div>
                <div style="font-size: 18px; color: #ffcccc; margin-top: 10px;">Final Score</div>
            </div>
            <div style="margin-top: 40px; display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
                <a href="quiz_list.php?page=quiz_play&id=` + quizId + `" style="padding: 15px 40px; background: linear-gradient(145deg, #e75e8d, #d64077); border: 2px solid #c4356a; color: white; text-decoration: none; border-radius: 10px; font-weight: 600;">
                    <i class="fa fa-refresh"></i> Try Again
                </a>
                <a href="quiz_list.php?page=quiz_list" style="padding: 15px 40px; background: linear-gradient(145deg, #27292a, #1f2122); border: 2px solid #e75e8d; color: white; text-decoration: none; border-radius: 10px; font-weight: 600;">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    `;
    document.getElementById("result-container").innerHTML = resultHTML;
    document.getElementById("result-container").classList.add("show");
}

// Voice Reading Functions
function stopSpeech() {
    if (speechSynthesis.speaking) {
        speechSynthesis.cancel();
    }
    // Remove speaking class from all buttons
    document.querySelectorAll(".voice-btn").forEach(btn => {
        btn.classList.remove("speaking");
    });
}

function speakText(text, button) {
    // Stop any current speech
    stopSpeech();
    
    // Create speech
    const utterance = new SpeechSynthesisUtterance(text);
    utterance.lang = "en-US";
    utterance.rate = 0.9;
    utterance.pitch = 1;
    
    // Add speaking class
    button.classList.add("speaking");
    
    // Remove speaking class when done
    utterance.onend = function() {
        button.classList.remove("speaking");
    };
    
    utterance.onerror = function() {
        button.classList.remove("speaking");
    };
    
    speechSynthesis.speak(utterance);
}

function readQuestion() {
    const question = quizData[currentQuestionIndex];
    const button = document.getElementById("btn-read-question");
    speakText(question.texte_question, button);
}

function readAnswers() {
    const question = quizData[currentQuestionIndex];
    const button = document.getElementById("btn-read-answers");
    
    const answersText = "Option A: " + question.option_a + ". Option B: " + question.option_b + ". Option C: " + question.option_c + ". Option D: " + question.option_d;
    
    speakText(answersText, button);
}

function displayQuestion() {
    if (currentQuestionIndex === 0) {
        startTimer();
    }
    
    // Stop any speech when changing question
    stopSpeech();
    
    const question = quizData[currentQuestionIndex];
    const container = document.getElementById("question-container");
    
    document.getElementById("current-question").textContent = currentQuestionIndex + 1;
    const progress = ((currentQuestionIndex + 1) / totalQuestions) * 100;
    document.getElementById("progress-bar").style.width = progress + "%";
    
    const questionHTML = `
        <div class="question-card active">
            <div class="voice-controls">
                <button class="voice-btn" id="btn-read-question" onclick="readQuestion()">
                    <i class="fa fa-volume-up"></i> Read Question
                </button>
                <button class="voice-btn" id="btn-read-answers" onclick="readAnswers()">
                    <i class="fa fa-volume-up"></i> Read Answers
                </button>
            </div>
            
            <div class="question-text">
                <strong>Question ` + (currentQuestionIndex + 1) + `:</strong><br>
                ` + escapeHtml(question.texte_question) + `
            </div>

            <div class="powerup-buttons">
                <button class="powerup-btn" id="btn-fifty-fifty" onclick="useFiftyFifty()">
                    <i class="fa fa-random"></i> 50/50
                </button>
                <button class="powerup-btn" id="btn-hint" onclick="useHint()">
                    <i class="fa fa-lightbulb"></i> Hint
                </button>
            </div>

            <div class="answers-container">
                <div class="answer-option" data-answer="A" onclick="selectAnswer(\'A\')">
                    <span class="option-letter">A</span>
                    <span class="option-text">` + escapeHtml(question.option_a) + `</span>
                </div>
                <div class="answer-option" data-answer="B" onclick="selectAnswer(\'B\')">
                    <span class="option-letter">B</span>
                    <span class="option-text">` + escapeHtml(question.option_b) + `</span>
                </div>
                <div class="answer-option" data-answer="C" onclick="selectAnswer(\'C\')">
                    <span class="option-letter">C</span>
                    <span class="option-text">` + escapeHtml(question.option_c) + `</span>
                </div>
                <div class="answer-option" data-answer="D" onclick="selectAnswer(\'D\')">
                    <span class="option-letter">D</span>
                    <span class="option-text">` + escapeHtml(question.option_d) + `</span>
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
    
    if (fiftyFiftyUsed) {
        const btn = document.getElementById("btn-fifty-fifty");
        btn.classList.add("used");
        btn.disabled = true;
        btn.innerHTML = \'<i class="fa fa-random"></i> 50/50 (Used)\';
    }
    if (hintUsed) {
        const btn = document.getElementById("btn-hint");
        btn.classList.add("used");
        btn.disabled = true;
        btn.innerHTML = \'<i class="fa fa-lightbulb"></i> Hint (Used)\';
    }
}

function selectAnswer(answer) {
    // Stop speech when user selects answer
    stopSpeech();
    
    const question = quizData[currentQuestionIndex];
    const correct = question.reponse_correcte.toUpperCase();
    
    userAnswers[question.id_question] = answer;
    
    const allOptions = document.querySelectorAll(".answer-option");
    allOptions.forEach(option => {
        option.style.pointerEvents = "none";
        option.classList.add("disabled");
    });
    
    const selectedOption = document.querySelector(`[data-answer="` + answer + `"]`);
    selectedOption.classList.add("selected");
    
    if (answer === correct) {
        selectedOption.classList.add("correct");
        selectedOption.innerHTML += \'<i class="fa fa-check answer-icon correct"></i>\';
        correctAnswers++;
        score += parseInt(question.points);
    } else {
        selectedOption.classList.add("incorrect");
        selectedOption.innerHTML += \'<i class="fa fa-times answer-icon incorrect"></i>\';
        
        const correctOption = document.querySelector(`[data-answer="` + correct + `"]`);
        correctOption.classList.add("correct");
        correctOption.innerHTML += \'<i class="fa fa-check answer-icon correct"></i>\';
    }
    
    if (question.explication && question.explication.trim() !== "") {
        document.getElementById("explanation-text").textContent = question.explication;
        document.getElementById("explanation-box").classList.add("show");
    }
    
    document.getElementById("btn-next").classList.add("show");
}

function useFiftyFifty() {
    if (fiftyFiftyUsed) return;
    
    const question = quizData[currentQuestionIndex];
    const correct = question.reponse_correcte.toUpperCase();
    const allOptions = ["A", "B", "C", "D"];
    const wrongOptions = allOptions.filter(opt => opt !== correct);
    
    const optionsToRemove = [];
    while (optionsToRemove.length < 2) {
        const randomIndex = Math.floor(Math.random() * wrongOptions.length);
        const option = wrongOptions[randomIndex];
        if (!optionsToRemove.includes(option)) {
            optionsToRemove.push(option);
        }
    }
    
    optionsToRemove.forEach(option => {
        const optionElement = document.querySelector(\'[data-answer="\' + option + \'"]\');
        if (optionElement) {
            optionElement.style.opacity = "0.3";
            optionElement.style.pointerEvents = "none";
            optionElement.style.textDecoration = "line-through";
        }
    });
    
    fiftyFiftyUsed = true;
    const btn = document.getElementById("btn-fifty-fifty");
    btn.classList.add("used");
    btn.disabled = true;
    btn.innerHTML = \'<i class="fa fa-random"></i> 50/50 (Used)\';
}

function useHint() {
    if (hintUsed) return;
    
    const question = quizData[currentQuestionIndex];
    
    if (question.explication && question.explication.trim() !== "") {
        document.getElementById("explanation-text").textContent = question.explication;
        document.getElementById("explanation-box").classList.add("show");
    } else {
        document.getElementById("explanation-text").textContent = "No hint available for this question.";
        document.getElementById("explanation-box").classList.add("show");
    }
    
    hintUsed = true;
    const btn = document.getElementById("btn-hint");
    btn.classList.add("used");
    btn.disabled = true;
    btn.innerHTML = \'<i class="fa fa-lightbulb"></i> Hint (Used)\';
}

document.getElementById("btn-next").addEventListener("click", function() {
    currentQuestionIndex++;
    
    if (currentQuestionIndex < totalQuestions) {
        displayQuestion();
    } else {
        submitQuiz();
    }
});

function submitQuiz() {
    // Stop any speech
    stopSpeech();
    clearInterval(timerInterval);
    
    const endTime = Date.now();
    const timeTaken = Math.floor((endTime - startTime) / 1000);
    const maxScore = quizData.reduce((sum, q) => sum + parseInt(q.points), 0);
    const percentage = Math.round((score / maxScore) * 100);
    
    let message = "";
    if (percentage >= 90) {
        message = "Outstanding! 🎉";
    } else if (percentage >= 70) {
        message = "Great Job! 👏";
    } else if (percentage >= 50) {
        message = "Good Effort! 👍";
    } else {
        message = "Keep Practicing! 💪";
    }
    
    document.getElementById("quiz-content").style.display = "none";
    
    // Build certificate buttons HTML if score >= 50%
    let certificateHTML = "";
    if (percentage >= 50) {
        const certParams = "quiz_id=" + quizId + "&percentage=" + percentage + "&correct=" + correctAnswers + "&total=" + totalQuestions + "&time=" + timeTaken;
        certificateHTML = `
            <div style="margin: 30px 0; padding: 25px; background: linear-gradient(145deg, #27292a, #1f2122); border: 2px solid #e75e8d; border-radius: 15px;">
                <h3 style="color: #e75e8d; margin-bottom: 15px; font-size: 20px;">
                    <i class="fa fa-certificate"></i> Certificate Available!
                </h3>
                <p style="color: #aaa; margin-bottom: 20px; font-size: 14px;">
                    Congratulations! You have earned a certificate for completing this quiz.
                </p>
                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                    <a href="quiz_list.php?page=generate_certificate&action=view&` + certParams + `" 
                       target="_blank"
                       style="padding: 12px 30px; background: linear-gradient(145deg, #e75e8d, #d64077); border: none; color: white; text-decoration: none; border-radius: 10px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fa fa-eye"></i> View Certificate
                    </a>
                    <a href="quiz_list.php?page=generate_certificate&action=download&` + certParams + `" 
                       style="padding: 12px 30px; background: linear-gradient(145deg, #27292a, #1f2122); border: 2px solid #e75e8d; color: white; text-decoration: none; border-radius: 10px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fa fa-download"></i> Download Certificate
                    </a>
                </div>
            </div>
        `;
    }
    
    const resultHTML = `
        <div style="font-size: 80px; margin-bottom: 20px;">🎮</div>
        <div class="result-score">` + percentage + `%</div>
        <div class="result-message">` + message + `</div>
        
        <div class="result-stats">
            <div class="stat-box">
                <h4>Correct Answers</h4>
                <p>` + correctAnswers + ` / ` + totalQuestions + `</p>
            </div>
            <div class="stat-box">
                <h4>Time Taken</h4>
                <p>` + Math.floor(timeTaken / 60) + `m ` + (timeTaken % 60) + `s</p>
            </div>
            <div class="stat-box">
                <h4>Score</h4>
                <p>` + score + ` / ` + maxScore + `</p>
            </div>
        </div>
        
        ` + certificateHTML + `
        
        <div class="result-actions">
            <a href="quiz_list.php?page=quiz_play&id=` + quizId + `">
                <i class="fa fa-refresh"></i> Try Again
            </a>
            <a href="quiz_list.php?page=quiz_list">
                <i class="fa fa-arrow-left"></i> Back to List
            </a>
        </div>
    `;
    
    document.getElementById("result-container").innerHTML = resultHTML;
    document.getElementById("result-container").classList.add("show");
    
    submitResultsToServer(timeTaken);
}

function submitResultsToServer(timeTaken) {
    console.log("🚀 Starting quiz submission...");
    console.log("Quiz ID:", quizId);
    console.log("Time Taken:", timeTaken);
    console.log("User Answers:", userAnswers);
    
    const iframe = document.createElement("iframe");
    iframe.style.display = "none";
    iframe.name = "hiddenFrame";
    document.body.appendChild(iframe);
    
    const form = document.createElement("form");
    form.method = "POST";
    form.action = "quiz_list.php?page=submit_quiz_results";
    form.target = "hiddenFrame";
    
    // Add basic quiz info
    const basicInputs = {
        id_quiz: quizId,
        id_user: userId,
        temps_ecoule: timeTaken
    };
    
    for (const key in basicInputs) {
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = key;
        input.value = basicInputs[key];
        form.appendChild(input);
        console.log("Added input:", key, "=", basicInputs[key]);
    }
    
    // Add all user answers
    let answerCount = 0;
    for (const questionId in userAnswers) {
        const answerInput = document.createElement("input");
        answerInput.type = "hidden";
        answerInput.name = "answer_" + questionId;
        answerInput.value = userAnswers[questionId];
        form.appendChild(answerInput);
        console.log("Added answer:", "answer_" + questionId, "=", userAnswers[questionId]);
        answerCount++;
    }
    
    console.log("Total answers submitted:", answerCount);
    console.log("Form action:", form.action);
    console.log("Form method:", form.method);
    console.log("Form target:", form.target);
    
    // Store user ID in localStorage so updateStatistics can retrieve it
    localStorage.setItem(\'userId\', userId);
    
    document.body.appendChild(form);
    console.log("📤 Submitting form...");
    form.submit();
    console.log("✓ Form submitted");
    
    setTimeout(() => {
        document.body.removeChild(form);
        document.body.removeChild(iframe);
        console.log("Cleaned up form and iframe");
    }, 1000);
}

function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
}
';

include 'view/footer.php';
?>