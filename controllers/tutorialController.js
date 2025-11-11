// controllers/tutorialController.js
import TutorialModel from "../models/tutorialModel.js";

const TutorialController = (function() {
  async function list() {
    const tutorials = await TutorialModel.getAll();
    const grid = document.getElementById("tutorials");
    grid.innerHTML = tutorials.map(t => `
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="item">
          <div class="thumb">
            <iframe src="${t.videoUrl}" allowfullscreen></iframe>
          </div>
          <h4>${t.title}</h4>
          <p>${t.contentMd.substring(0, 60)}...</p>
          <a href="tutorial.html?id=${t.id}" class="btn btn-primary btn-sm mt-2">Voir plus</a>
        </div>
      </div>`).join("");
  }

  async function detail() {
    const id = new URLSearchParams(window.location.search).get("id");
    const t = await TutorialModel.getById(id);
    if (!t) return;
    document.getElementById("title").textContent = t.title;
    document.getElementById("video").src = t.videoUrl;
    document.getElementById("desc").textContent = t.contentMd;
  }

  return { list, detail };
})();

export default TutorialController;
