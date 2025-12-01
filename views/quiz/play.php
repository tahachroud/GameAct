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
                                
                                <div class="timer-container" id="timer-container">
                                    <div class="timer-label">Time Remaining</div>
                                    <div class="timer-display" id="timer-display">3:00</div>
                                </div>
                                
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
const totalQuestions = quizData.length;

let currentQuestionIndex = 0;
let score = 0;
let correctAnswers = 0;
let userAnswers = {};
let startTime = Date.now();

let timeRemaining = 180;
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
                <a href="index.php?page=quiz_play&id=` + quizId + `" style="padding: 15px 40px; background: linear-gradient(145deg, #e75e8d, #d64077); border: 2px solid #c4356a; color: white; text-decoration: none; border-radius: 10px; font-weight: 600;">
                    <i class="fa fa-refresh"></i> Try Again
                </a>
                <a href="index.php?page=quiz_list" style="padding: 15px 40px; background: linear-gradient(145deg, #27292a, #1f2122); border: 2px solid #e75e8d; color: white; text-decoration: none; border-radius: 10px; font-weight: 600;">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    `;
    document.getElementById("result-container").innerHTML = resultHTML;
    document.getElementById("result-container").classList.add("show");
}

function displayQuestion() {
    if (currentQuestionIndex === 0) {
        startTimer();
    }
    
    const question = quizData[currentQuestionIndex];
    const container = document.getElementById("question-container");
    
    document.getElementById("current-question").textContent = currentQuestionIndex + 1;
    const progress = ((currentQuestionIndex + 1) / totalQuestions) * 100;
    document.getElementById("progress-bar").style.width = progress + "%";
    
    const questionHTML = `
        <div class="question-card active">
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
        
        <div class="result-actions">
            <a href="index.php?page=quiz_play&id=` + quizId + `">
                <i class="fa fa-refresh"></i> Try Again
            </a>
            <a href="index.php?page=quiz_list">
                <i class="fa fa-arrow-left"></i> Back to List
            </a>
        </div>
    `;
    
    document.getElementById("result-container").innerHTML = resultHTML;
    document.getElementById("result-container").classList.add("show");
    
    submitResultsToServer(timeTaken);
}

function submitResultsToServer(timeTaken) {
    const iframe = document.createElement("iframe");
    iframe.style.display = "none";
    iframe.name = "hiddenFrame";
    document.body.appendChild(iframe);
    
    const form = document.createElement("form");
    form.method = "POST";
    form.action = "index.php?page=submit_quiz_results";
    form.target = "hiddenFrame";
    
    const inputs = {
        id_quiz: quizId,
        score: score,
        time_taken: timeTaken
    };
    
    for (const key in inputs) {
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = key;
        input.value = inputs[key];
        form.appendChild(input);
    }
    
    document.body.appendChild(form);
    form.submit();
    
    setTimeout(() => {
        document.body.removeChild(form);
        document.body.removeChild(iframe);
    }, 1000);
}

function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
}
';

include 'views/footer.php';
?>