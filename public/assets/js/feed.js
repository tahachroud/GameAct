$(document).ready(function () {

    $(".share-btn").on("click", function () {

        let btn = $(this);
        let postId = btn.data("post-id");

        // small animation
        btn.css("transform", "scale(1.4)");
        setTimeout(() => btn.css("transform", "scale(1)"), 120);

        // ask optional message
        let msg = prompt("Add a message (optional):");

        // AJAX SHARE
        $.post("index.php?action=share_post", {
            post_id: postId,
            message: msg
        }, function (response) {

            let res = JSON.parse(response);

            if (res.success) {
                alert("Post shared successfully!");
                location.reload();
            }
        });
    });


    // ============================
    // COMMENT TOGGLE SYSTEM
    // ============================
    $(document).on("click", ".comment-btn", function () {
        const post = $(this).closest(".feed-post");
        const commentSection = post.find(".comment-section");

        commentSection.slideToggle(200);
    });

});
