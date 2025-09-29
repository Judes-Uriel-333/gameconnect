document.addEventListener("DOMContentLoaded", () => {
  const scrollBtn = document.createElement("button");
  scrollBtn.textContent = "🔝";
  scrollBtn.className = "fixed bottom-6 right-6 bg-blue-600 text-white p-2 rounded-full shadow-md hidden";
  document.body.appendChild(scrollBtn);

  window.addEventListener("scroll", () => {
    if (window.scrollY > 300) {
      scrollBtn.classList.remove("hidden");
    } else {
      scrollBtn.classList.add("hidden");
    }
  });

  scrollBtn.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
});
