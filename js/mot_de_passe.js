document.addEventListener("DOMContentLoaded", () => {
  const passwordInput = document.getElementById("mot_de_passe");
  const criteria = {
    longueur: document.getElementById("longueur"),
    majuscule: document.getElementById("majuscule"),
    minuscule: document.getElementById("minuscule"),
    chiffre: document.getElementById("chiffre"),
    special: document.getElementById("special"),
  };

  passwordInput.addEventListener("input", () => {
    const val = passwordInput.value;

    // Vérifie chaque critère
    criteria.longueur.classList.toggle("text-green-400", val.length >= 10);
    criteria.majuscule.classList.toggle("text-green-400", /[A-Z]/.test(val));
    criteria.minuscule.classList.toggle("text-green-400", /[a-z]/.test(val));
    criteria.chiffre.classList.toggle("text-green-400", /\d/.test(val));
    criteria.special.classList.toggle("text-green-400", /[^a-zA-Z0-9]/.test(val));
  });
});
