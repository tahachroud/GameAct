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
        $.post("index-community.php?action=share_post", {
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
    // =============================
// ATTACH IMAGE BUTTON HANDLER
// =============================
document.addEventListener("DOMContentLoaded", () => {
    const attachBtn = document.getElementById("attachBtn");
    const postImage = document.getElementById("postImage");
    const previewImg = document.getElementById("previewImg");

    if (attachBtn && postImage) {

        // Open file dialog when clicking Attach Image
        attachBtn.addEventListener("click", () => {
            postImage.click();
        });

        // Preview image after selecting it
        postImage.addEventListener("change", function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = "block";
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
});


});
