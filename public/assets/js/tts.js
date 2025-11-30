function readPost(id) {
    fetch("index.php?action=tts_read&id=" + id)
        .then(res => res.json())
        .then(data => {

            console.log(data); // DEBUG

            if (!data.audio) {
                alert("No audio received!");
                return;
            }

            let audio = new Audio("data:audio/mp3;base64," + data.audio);
            audio.play();
        })
        .catch(err => console.error(err));
}
