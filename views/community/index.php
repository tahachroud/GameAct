<?php $default_user = 5; ?> 

<?php
require_once __DIR__ . '/../../models/Share.php';
$shareModel = new Share(); 
?>

<div class="container mt-4">
    <div class="card composer mb-5">

        <form id="composerForm" action="index.php?action=posts_store_front" method="POST" enctype="multipart/form-data">

            <div class="composer-top d-flex">
                <img class="avatar" src="public/assets/images/avatar-01.jpg" alt="">
                <textarea name="content" class="form-control" placeholder="Share something..."></textarea>
            </div>

            <div class="composer-actions mt-3">
                <input type="file" name="image" id="postImage" accept="image/*" hidden>
                <button type="button" id="attachBtn" class="btn small">Attach Image</button>
                <button type="submit" id="postBtn" class="btn primary float-end">Post</button>
            </div>

            <img id="previewImg" style="display:none; width:100%; border-radius:10px; margin-top:15px;">
            <div class="error-box"></div>

        </form>

    </div>
</div>


<div class="container feed-container mb-5">
    <div class="row">

        <div class="col-lg-3"></div>

        <div class="col-lg-6">

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
                            <p><?= nl2br(htmlspecialchars($item['content'])) ?></p>

                            <?php if (!empty($item['image'])): ?>
                                <img src="public/uploads/posts/<?= $item['image'] ?>" class="post-img">
                            <?php endif; ?>

                            <!-- Actions -->
                            <div class="actions">
                                <button class="action-btn like-btn" data-post-id="<?= $item['id'] ?>">
                                    ❤️ <span class="like-count">0</span>
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
                                <form method="POST" action="index.php?action=comments_store_front" class="comment-form mt-2">
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

                                <?php if (!empty($original['image'])): ?>
                                    <img src="public/uploads/posts/<?= $original['image'] ?>" class="post-img">
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

                                    <form method="POST" action="index.php?action=comments_store_front" class="comment-form mt-2">
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

        <div class="col-lg-3"></div>

    </div>
</div>
