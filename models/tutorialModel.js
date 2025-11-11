// models/tutorialModel.js

const TutorialModel = (function() {

  // 🔹 Récupère tous les tutoriels depuis le fichier JSON
  async function getAll() {
    const response = await fetch("../../data/tutorials.json");
    return await response.json();
  }

  // 🔹 Récupère un tutoriel précis grâce à son ID
  async function getById(id) {
    const all = await getAll();
    return all.find(t => t.id == id);
  }

  // 🔹 Retourne les fonctions disponibles
  return { getAll, getById };

})();

export default TutorialModel;
