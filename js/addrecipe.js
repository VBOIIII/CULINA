const recipeAddBtn = document.querySelector(".recipe-add-btn");
const recipePopup = document.querySelector(".recipe-popup-container");
const closeRecipePopupBtn = document.querySelector("#close-btn");

recipeAddBtn.addEventListener("click", () => {
    document.body.classList.add("show-recipe-popup");
});

closeRecipePopupBtn.addEventListener("click", () => {
    document.body.classList.remove("show-recipe-popup");
});