/* Poll voting widget */
$(document).ready(function () {
    $(".poll-vote-btn").on("click", function () {
        const pollId = $(this).data("poll-id");
        const option = $(this).data("option");

        $.post("index-community.php?action=poll_vote_ajax", {
            poll_id: pollId,
            option: option
        }, function (response) {
            try {
                const res = (typeof response === 'string') ? JSON.parse(response) : response;
                if (res.success && res.poll) {
                    location.reload();
                }
            } catch (e) {
                console.error("Poll vote error:", e, response);
            }
        });
    });
});
