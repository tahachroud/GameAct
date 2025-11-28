<style>
    .results-container {
        margin-top: 40px;
        color: white;
        font-family: 'Poppins', sans-serif;
    }

    .result-card {
        background: #1a1a1a;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        border: 1px solid #333;
        box-shadow: 0 0 15px rgba(136, 0, 255, 0.25);
        transition: 0.2s;
    }

    .result-card:hover {
        transform: scale(1.02);
        box-shadow: 0 0 20px rgba(180, 0, 255, 0.35);
    }

    .result-username {
        color: #cc66ff;
        font-weight: bold;
        font-size: 18px;
        text-shadow: 0 0 6px #9900ff;
    }

    .result-content {
        margin-top: 10px;
        font-size: 15px;
        line-height: 1.5;
        color: #f1e6ff;
    }

    .result-date {
        margin-top: 8px;
        font-size: 12px;
        color: #bbb;
    }

    .result-image {
        margin-top: 12px;
        max-width: 250px;
        border-radius: 10px;
        border: 1px solid #444;
        box-shadow: 0 0 10px rgba(136, 0, 255, 0.4);
    }

    .back-btn {
        display: inline-block;
        padding: 10px 20px;
        background: linear-gradient(90deg, #7300ff, #cc00ff);
        border-radius: 25px;
        text-decoration: none;
        color: white;
        font-weight: bold;
        box-shadow: 0 0 12px #8800ff;
        margin-bottom: 25px;
    }

    .back-btn:hover {
        box-shadow: 0 0 20px #cc33ff;
    }

    .title-search {
        color: #bb33ff;
        font-size: 28px;
        margin-bottom: 20px;
        text-shadow: 0px 0px 10px #bb33ff;
    }
</style>


<div class="container results-container">

    <a href="index.php?action=search_form" class="back-btn">⬅ Back to Search</a>

    <h2 class="title-search">Search Results</h2>

    <?php if (empty($posts)): ?>
        
        <p>No posts match your search.</p>

    <?php else: ?>

        <?php foreach ($posts as $p): ?>
            <div class="result-card">

                <div class="result-username">
                    <?= htmlspecialchars($p['username']) ?>
                </div>

                <div class="result-content">
                    <?= nl2br(htmlspecialchars($p['content'])) ?>
                </div>

                <?php if (!empty($p['image'])): ?>
                    <img src="public/uploads/posts/<?= $p['image'] ?>" class="result-image">
                <?php endif; ?>

                <div class="result-date">
                    Posted on <?= $p['created_at'] ?>
                </div>

            </div>
        <?php endforeach; ?>

    <?php endif; ?>

</div>
