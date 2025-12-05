$(document).ready(function () {

    $(".share-btn").on("click", function () {

        let btn = $(this);
        let postId = btn.data("post-id");

        // animation
        btn.css("transform", "scale(1.4)");
        setTimeout(() => btn.css("transform", "scale(1)"), 120);

        // 1) Update share counter
        $.post("index.php?action=share_update_ajax", {
            post_id: postId
        }, function (response) {
            let res = JSON.parse(response);
            if (res.success) {
                btn.find(".share-count").text(res.post_shares);
            }
        });

        // 2) Ask user for message
        setTimeout(() => {
            let msg = prompt("Add a message to your share (optional):");

            $.post("index.php?action=share_post", {
                post_id: postId,
                message: msg
            }, function (response) {

                let res = JSON.parse(response);
                if (res.success) {
                    alert("Publication partagée !");
                    location.reload();
                }
            });

        }, 300);

    });

});
