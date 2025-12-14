$(document).ready(function () {

    
    let likes = JSON.parse(localStorage.getItem("likes")) || {};

  
    $(".like-btn").each(function () {
        let postId = $(this).data("post-id");

        if (!likes[postId]) {
            likes[postId] = { liked: false, count: 0 };
        }

        updateLikeButton($(this), likes[postId]);
    });

    
    $(".like-btn").on("click", function () {

        let postId = $(this).data("post-id");

        if (!likes[postId].liked) {
            likes[postId].liked = true;
            likes[postId].count++;
        } else {
            likes[postId].liked = false;
            likes[postId].count--;
        }

        localStorage.setItem("likes", JSON.stringify(likes));

        updateLikeButton($(this), likes[postId]);


      
        $.post("index-community.php?action=likes_update_ajax", {
            post_id: postId,
            liked: likes[postId].liked ? "true" : "false"
        }, function (response) {
            console.log("Server updated:", response);
        });

    });

    
    function updateLikeButton(btn, data) {

        let countSpan = btn.find(".like-count");
        countSpan.text(data.count);

        if (data.liked) {

            btn.addClass("liked");
            btn.css("color", "#ff0077");

            btn.css("transform", "scale(1.4)");
            setTimeout(() => btn.css("transform", "scale(1)"), 150);

        } else {

            btn.removeClass("liked");
            btn.css("color", "var(--muted)");

        }
    }

});
