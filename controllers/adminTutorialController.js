// controllers/adminTutorialController.js
console.log("AdminTutorialController chargé ✅");

document.addEventListener("DOMContentLoaded", () => {
  const tbody = document.querySelector("#tbody");

  const data = [
    { id: 1, titre: "Configurer un live", langue: "FR", duree: "8 min", statut: "Publié" },
    { id: 2, titre: "Create an overlay", langue: "EN", duree: "10 min", statut: "Publié" }
  ];

  tbody.innerHTML = data.map(d => `
    <tr>
      <td>${d.id}</td>
      <td>${d.titre}</td>
      <td>${d.langue}</td>
      <td>${d.duree}</td>
      <td>${d.statut}</td>
    </tr>`).join("");
});
