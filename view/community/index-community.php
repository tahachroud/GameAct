<?php $default_user = 5; ?> 

<?php
require_once __DIR__ . '/../../model/Share.php';
$shareModel = new Share(); 
?>

<div class="container mt-4">
    <div class="card composer mb-5">

        <form id="composerForm" action="index-community.php?action=posts_store_front" method="POST" enctype="multipart/form-data">

            <div class="composer-top d-flex">
                <img class="avatar" src="public/assets/images/avatar-01.jpg" alt="">
                <textarea name="content" class="form-control" placeholder="Share something..."></textarea>
            </div>

                <div class="composer-actions mt-3">

                    <!-- IMAGE -->
                    <input type="file" name="images[]" id="postImages" accept="image/*" multiple hidden>
<button type="button" id="attachImageBtn" class="neon-attach-btn img">
      Image
</button>
                    <!-- PDF -->
                    <input type="file" name="pdf" id="postPdf" accept="application/pdf" hidden>
<button type="button" id="attachPdfBtn" class="neon-attach-btn pdf">
    📄 PDF
</button>

                    <!-- LINK -->
<button type="button" id="attachLinkBtn" class="neon-attach-btn link">
    🔗 Link
</button>
                    <input type="text" name="link" id="postLink" 
                        placeholder="Paste a link..." class="form-control mt-2" style="display:none;">

                    <button type="submit" id="postBtn" class="btn primary float-end">Post</button>
                </div>

                <img id="previewImg" style="display:none; width:100%; border-radius:10px; margin-top:15px;">
                <div id="pdfPreview" style="display:none; margin-top:10px; color:#0ff;">📄 PDF attached</div>
                <div id="linkPreview" style="display:none; margin-top:10px; color:#0f0;"></div>
                <div id="multiPreview" style="display:flex; gap:10px; flex-wrap:wrap; margin-top:10px;"></div>

            <div class="error-box"></div>

        </form>

    </div>
</div>


<div class="container feed-container mb-5">
    <div class="row">

        <div class="col-lg-3">
            <?php if (!empty($topContributors)): ?>
                <?php include __DIR__ . '/top_contributors.php'; ?>
            <?php else: ?>
                <div class="card p-3">
                    <strong>Top Contributors</strong>
                    <div class="text-muted">No contributors yet</div>
                </div>
            <?php endif; ?>

            <?php $activePoll = $GLOBALS['activePoll'] ?? null; ?>
            <?php if (!empty($activePoll)): ?>
                <?php include __DIR__ . '/poll.php'; ?>
            <?php endif; ?>
        </div>

        <div class="col-lg-6">
            <?php if (!empty($selectedTag)): ?>
    <div class="trending-card mb-4">
        <h5>
            Showing posts for 
            <span class="hashtag">#<?= htmlspecialchars($selectedTag) ?></span>
        </h5>
        <small>
            <?= count($feed) ?> post(s) found
        </small>
    </div>
<?php endif; ?>


            <div id="feedList">

                <?php foreach ($feed as $item): ?>

                    <?php  
                        $commentModel = new Comment($this->db);
                        $postComments = $commentModel->getByPost($item['id']);
                        $commentCount = count($postComments);
                    ?>

                    <?php if ($item['parent_id'] === null): ?>

                        <!-- 🟦 NORMAL POST -->
                        <div class="feed-post">

                            <!-- Header -->
                            <div class="d-flex align-items-center mb-2">
                                <img class="avatar" src="public/assets/images/avatar-01.jpg">
                                <div>
                                    <strong><?= htmlspecialchars($item['username']) ?></strong>
                                    <div class="text-muted"><?= htmlspecialchars($item['created_at']) ?></div>
                                </div>
                            </div>

                            <!-- Content -->
<?php
$formatted = htmlspecialchars($item['content']);
$formatted = preg_replace(
    '/#([a-zA-Z0-9_]+)/',
    '<a href="index-community.php?action=community&tag=$1" class="hashtag">#$1</a>',
    $formatted
);
?>
<p><?= nl2br($formatted) ?></p>

          <?php 
if (!empty($item['images'])) {
    $imgs = json_decode($item['images'], true);
    foreach ($imgs as $img) { ?>
        <img src="public/uploads/posts/<?= $img ?>" class="post-img">
<?php }
}
?>


<!-- PDF BUTTON -->
<?php if (!empty($item['pdf'])): ?>
    <a href="public/uploads/posts/<?= $item['pdf'] ?>" target="_blank" class="neon-file pdf">
        📄 View PDF
    </a>
<?php endif; ?>

<!-- LINK BUTTON -->
<?php if (!empty($item['link'])): ?>
    <a href="<?= htmlspecialchars($item['link']) ?>" target="_blank" class="neon-file link">
        🔗 Open Link
    </a>
<?php endif; ?>


                            <!-- Actions -->
                            <div class="actions">
                                <button class="action-btn like-btn" data-post-id="<?= $item['id'] ?>">
                                    ❤️ <span class="like-count">0</span>
                                </button>
                                <button class="action-btn" onclick="readPost(<?= $item['id'] ?>)">
                                    🔊 Listen
                                </button>


                                <button class="action-btn comment-btn">
                                    💬 <span><?= $commentCount ?></span>
                                </button>

                                <button class="action-btn share-btn" data-post-id="<?= $item['id'] ?>">
                                    🔁 <span class="share-count"><?= $shareModel->getPostShares($item['id']) ?></span>
                                </button>
                            </div>

                            <!-- COMMENTS SECTION -->
                            <div class="comment-section mt-2" style="display:none;">

                                <?php foreach ($postComments as $c): ?>
                                    <div class="comment-box mb-2">
                                        <strong><?= htmlspecialchars($c['username']) ?>:</strong>
                                        <?= nl2br(htmlspecialchars($c['content'])) ?>
                                    </div>
                                <?php endforeach; ?>

                                <!-- Comment Form -->
                                <form method="POST" action="index-community.php?action=comments_store_front" class="comment-form mt-2">
                                    <input type="hidden" name="post_id" value="<?= $item['id'] ?>">
                                    <textarea name="content" class="form-control" rows="1" placeholder="Write a comment..."></textarea>
                                    <button class="btn btn-sm btn-primary mt-1">Send</button>
                                </form>

                            </div>

                        </div>

                    <?php else: ?>

                        <!-- 🟧 SHARED POST -->
                        <?php 
                            $original = $postModel->find($item['parent_id']);
                            if (!$original) continue;

                            $originalComments = $commentModel->getByPost($original['id']);
                            $originalCount = count($originalComments);
                        ?>

                        <div class="feed-post">

                            <!-- Header shared -->
                            <div class="d-flex align-items-center mb-2">
                                <img class="avatar" src="public/assets/images/avatar-01.jpg">
                                <div>
                                    <strong><?= htmlspecialchars($item['username']) ?></strong>
                                    <div class="text-muted"><?= htmlspecialchars($item['created_at']) ?> — a partagé une publication</div>
                                </div>
                            </div>

                            <!-- Message -->
                            <?php if (!empty($item['content'])): ?>
                                <p><?= nl2br(htmlspecialchars($item['content'])) ?></p>
                            <?php endif; ?>

                            <!-- Original Post -->
                            <div class="shared-original" style="border-left:3px solid #666;padding-left:10px;margin-top:10px;">

                                <strong><?= htmlspecialchars($original['username']) ?></strong>
                                <div class="text-muted"><?= htmlspecialchars($original['created_at']) ?></div>

                                <p><?= nl2br(htmlspecialchars($original['content'])) ?></p>

                               <?php 
$origImages = json_decode($original['images'], true);
if ($origImages && count($origImages) > 0):
    foreach ($origImages as $img):
?>
        <img src="public/uploads/posts/<?= $img ?>" class="post-img">
<?php 
    endforeach;
endif;
?>


<?php if (!empty($original['pdf'])): ?>
    <a href="public/uploads/posts/<?= $original['pdf'] ?>" target="_blank" class="pdf-link">
        📄 Download PDF
    </a>
<?php endif; ?>

<?php if (!empty($original['link'])): ?>
    <a href="<?= htmlspecialchars($original['link']) ?>" target="_blank" class="post-link">
        🔗 <?= htmlspecialchars($original['link']) ?>
    </a>
<?php endif; ?>


                                <!-- Actions inside shared post -->
                                <div class="actions">
                                    <button class="action-btn like-btn" data-post-id="<?= $original['id'] ?>">
                                        ❤️ <span class="like-count">0</span>
                                    </button>

                                    <button class="action-btn comment-btn">
                                        💬 <span><?= $originalCount ?></span>
                                    </button>

                                    <button class="action-btn share-btn" data-post-id="<?= $original['id'] ?>">
                                        🔁 <span class="share-count"><?= $shareModel->getPostShares($original['id']) ?></span>
                                    </button>
                                </div>

                                <!-- COMMENT SECTION for shared post -->
                                <div class="comment-section mt-2" style="display:none;">

                                    <?php foreach ($originalComments as $c): ?>
                                        <div class="comment-box mb-2">
                                            <strong><?= htmlspecialchars($c['username']) ?>:</strong>
                                            <?= nl2br(htmlspecialchars($c['content'])) ?>
                                        </div>
                                    <?php endforeach; ?>

                                    <form method="POST" action="index-community.php?action=comments_store_front" class="comment-form mt-2">
                                        <input type="hidden" name="post_id" value="<?= $original['id'] ?>">
                                        <textarea name="content" class="form-control" rows="1" placeholder="Write a comment..."></textarea>
                                        <button class="btn btn-sm btn-primary mt-1">Send</button>
                                    </form>

                                </div>

                            </div>

                        </div>

                    <?php endif; ?>

                <?php endforeach; ?>

            </div>

        </div>

        <div class="col-lg-3">
<?php if (!empty($topHashtags)): ?>
        <?php include __DIR__ . '/trending_hashtags.php'; ?>
    <?php endif; ?>
    <?php if (!empty($trending)): ?>
        <?php include __DIR__ . '/trending.php'; ?>
    <?php endif; ?>

    
</div>


    </div>
<script>
document.addEventListener("DOMContentLoaded", function () {

    // BUTTONS
    const imgBtn = document.getElementById("attachImageBtn");
    const pdfBtn = document.getElementById("attachPdfBtn");
    const linkBtn = document.getElementById("attachLinkBtn");

    // INPUTS
    const imgInput = document.getElementById("postImages"); // UPDATED
    const pdfInput = document.getElementById("postPdf");
    const linkInput = document.getElementById("postLink");

    // PREVIEW AREAS
    const previewImg = document.getElementById("previewImg");
    const pdfPreview = document.getElementById("pdfPreview");
    const linkPreview = document.getElementById("linkPreview");
    const multiPreview = document.getElementById("multiPreview");

    // -----------------------------
    // IMAGE BUTTON (OPEN FILE DIALOG)
    // -----------------------------
    imgBtn.addEventListener("click", () => imgInput.click());

    // -----------------------------
    // MULTIPLE IMAGES PREVIEW
    // -----------------------------
    imgInput.addEventListener("change", function () {
        multiPreview.innerHTML = "";
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.createElement("img");
                img.src = e.target.result;
                img.style.width = "80px";
                img.style.borderRadius = "8px";
                img.style.boxShadow = "0 0 12px #ff00ff";
                multiPreview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });

    // -----------------------------
    // PDF PREVIEW
    // -----------------------------
    pdfBtn.addEventListener("click", () => pdfInput.click());
    pdfInput.addEventListener("change", function () {
        if (this.files.length > 0) {
            pdfPreview.style.display = "block";
            pdfPreview.innerHTML = "📄 PDF attached: " + this.files[0].name;
        }
    });

    // -----------------------------
    // LINK PREVIEW
    // -----------------------------
    linkBtn.addEventListener("click", () => linkInput.style.display = "block");
    linkInput.addEventListener("input", () => {
        linkPreview.style.display = "block";
        linkPreview.innerHTML = "🔗 " + linkInput.value;
    });
});
</script>




</div>
