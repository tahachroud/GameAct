$(document).ready(function () {

    // -------------------------------
    // LOAD LOCALSTORAGE LIKES
    // -------------------------------
    let likes = JSON.parse(localStorage.getItem("likes")) || {};

    // -------------------------------
    // INITIALIZE LIKE BUTTONS
    // -------------------------------
    $(".like-btn").each(function () {
        let postId = $(this).data("post-id");

        // Initialize entry if missing
        if (!likes[postId]) {
            likes[postId] = { liked: false, count: 0 };
        }

        updateLikeButton($(this), likes[postId]);
    });

    // -------------------------------
    // CLICK EVENT
    // -------------------------------
    $(".like-btn").on("click", function () {

        let postId = $(this).data("post-id");

        // Toggle like state
        if (!likes[postId].liked) {
            likes[postId].liked = true;
            likes[postId].count++;
        } else {
            likes[postId].liked = false;
            likes[postId].count--;
        }

        // Save to localStorage
        localStorage.setItem("likes", JSON.stringify(likes));

        // Update UI immediately
        updateLikeButton($(this), likes[postId]);


        // -------------------------------
        // SEND AJAX TO BACKEND (MVC)
        // -------------------------------
        $.post("index.php?action=likes_update_ajax", {
            post_id: postId,
            liked: likes[postId].liked ? "true" : "false"
        }, function (response) {
            // Optional: show debug
            console.log("Server updated:", response);
        });

    });

    // -------------------------------
    // UI UPDATE FUNCTION
    // -------------------------------
    function updateLikeButton(btn, data) {

        let countSpan = btn.find(".like-count");
        countSpan.text(data.count);

        if (data.liked) {

            btn.addClass("liked");
            btn.css("color", "#ff0077");

            // Small animation
            btn.css("transform", "scale(1.4)");
            setTimeout(() => btn.css("transform", "scale(1)"), 150);

        } else {

            btn.removeClass("liked");
            btn.css("color", "var(--muted)");

        }
    }

});
