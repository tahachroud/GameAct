<?php
session_start();

if (!$item) {
    echo '<h2 style="color:white; text-align:center; margin-top:50px;">❌ Tutoriel introuvable</h2>';
    exit;
}

require_once "config/database.php";
require_once "models/Feedback.php";

$db = (new Database())->connect();
$fbModel = new Feedback($db);

$feedbacks = $fbModel->getByTutorial($item["id"]);

$errors = $_SESSION["feedback_errors"] ?? [];
$old    = $_SESSION["feedback_old"] ?? [];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($item['title']) ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="../../public/assets/templatemo-cyborg-gaming.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
    body {
        background:#0d0d12;
        font-family: 'Poppins', sans-serif;
        color:white;
    }

    h2, h3, h4 {
        color:#ff11ff;
        text-shadow:0 0 10px #ff11ff, 0 0 20px #ff11ff;
        font-weight: bold;
    }

    .feedback-card {
        background:#111;
        border:1px solid #222;
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 18px;
        transition:0.3s;
    }
    .feedback-card:hover {
        transform:scale(1.01);
        box-shadow:0 0 15px #ff11ff55;
    }

    .feedback-name {
        color:#ff33ff;
        font-size:18px;
        font-weight:600;
    }

    .feedback-date {
        color:#aaa;
        font-size:13px;
    }

    .btn-like {
        background:#00ff88;
        border:none;
        color:#000;
        font-weight:600;
        padding:6px 12px;
        border-radius:8px;
        transition:0.3s;
    }
    .btn-like:hover {
        box-shadow:0 0 10px #00ff88aa;
        transform:scale(1.1);
    }

    .btn-dislike {
        background:#ff0048;
        border:none;
        color:white;
        font-weight:600;
        padding:6px 12px;
        border-radius:8px;
        transition:0.3s;
    }
    .btn-dislike:hover {
        box-shadow:0 0 10px #ff0048aa;
        transform:scale(1.1);
    }

    input, textarea {
        background:#0d0d0d;
        border:1px solid #333;
        color:white;
        border-radius:8px;
    }
    input:focus, textarea:focus {
        border-color:#ff11ff;
        box-shadow:0 0 10px #ff11ff;
    }

    .btn-primary {
        background:linear-gradient(45deg, #ff11ff, #ff0077);
        border:none;
        padding:10px 20px;
        border-radius:8px;
        font-weight:bold;
        transition:0.3s;
    }
    .btn-primary:hover {
        box-shadow:0 0 12px #ff11ff;
        transform:scale(1.05);
    }
    </style>

</head>

<body>

<!-- 🔥 Header -->
<?php include __DIR__ . "/header.php"; ?>


<div class="container mt-5">

    <h2><?= htmlspecialchars($item["title"]) ?></h2>

    <iframe src="<?= $item['videoUrl'] ?>" width="100%" height="420" allowfullscreen style="border-radius:12px;"></iframe>

    <p class="mt-3"><?= nl2br(htmlspecialchars($item['content'])) ?></p>

    <!-- 🔥 ERREURS -->
    <?php if (!empty($errors)) : ?>
        <div class="alert alert-danger mt-4">
            <ul class="mb-0">
                <?php foreach ($errors as $err) : ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>


    <h3 class="mt-5">Feedback des utilisateurs</h3>

    <?php foreach ($feedbacks as $f): ?>
        <div class="feedback-card">

            <div class="feedback-name"><?= htmlspecialchars($f['username']) ?></div>

            <p><?= htmlspecialchars($f['message']) ?></p>

            <div class="feedback-date"><?= $f['created_at'] ?></div>

            <div class="mt-3">

                <!-- LIKE AJAX -->
                <button class="btn-like"
                        onclick="sendFeedback(<?= $f['id'] ?>, 'like')">
                    👍 <span id="like-<?= $f['id'] ?>"><?= $f['likes'] ?></span>
                </button>

                <!-- DISLIKE AJAX -->
                <button class="btn-dislike"
                        onclick="sendFeedback(<?= $f['id'] ?>, 'dislike')">
                    👎 <span id="dislike-<?= $f['id'] ?>"><?= $f['dislikes'] ?></span>
                </button>

            </div>

        </div>
    <?php endforeach; ?>


    <h4 class="mt-4">Ajouter un feedback</h4>

    <form method="POST" action="router.php?action=feedbackStore" class="mt-3">

        <input type="hidden" name="tutorial_id" value="<?= $item['id'] ?>">

        <label>Nom</label>
        <input type="text" name="username" class="form-control mb-3"
               value="<?= htmlspecialchars($old["username"] ?? "") ?>" required>

        <label>Message</label>
        <textarea name="message" class="form-control mb-3"
                  required><?= htmlspecialchars($old["message"] ?? "") ?></textarea>

        <button class="btn btn-primary">Envoyer</button>
    </form>

</div>

<!-- Footer -->
<?php include __DIR__ . "/footer.php"; ?>


<!-- 🔥 AJAX -->
<script>
function sendFeedback(id, type) {
    let formData = new FormData();
    formData.append("id", id);
    formData.append("type", type);

    fetch("ajax/feedback.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {

        if (data.status === "success") {

            let counter = document.getElementById(type + "-" + id);
            counter.innerText = parseInt(counter.innerText) + 1;

            counter.style.transform = "scale(1.4)";
            setTimeout(() => counter.style.transform = "scale(1)", 150);
        }
    });
}
</script>

</body>
</html>
