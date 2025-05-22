document.addEventListener('DOMContentLoaded', function() {
    const recipeAddBtn = document.querySelector('.recipe-add-btn');
    const recipePopupContainer = document.querySelector('.recipe-popup-container');
    const closeBtn = document.querySelector('#close-btn');
    const searchBtn = document.querySelector('#search-btn');
    const searchInput = document.querySelector('#search-input');
    const recipeContainer = document.querySelector('#recipe-container');

    recipeAddBtn.addEventListener('click', function() {
        recipePopupContainer.style.display = 'block';
    });

    closeBtn.addEventListener('click', function() {
        recipePopupContainer.style.display = 'none';
    });

    searchBtn.addEventListener('click', function() {
        const query = searchInput.value.toLowerCase();
        const recipes = recipeContainer.querySelectorAll('.recipe');
        recipes.forEach(function(recipe) {
            const recipeName = recipe.querySelector('h3').textContent.toLowerCase();
            if (recipeName.includes(query)) {
                recipe.style.display = 'block';
            } else {
                recipe.style.display = 'none';
            }
        });
    });

    searchInput.addEventListener('input', function() {
        const query = searchInput.value.toLowerCase();
        const recipes = recipeContainer.querySelectorAll('.recipe');
        recipes.forEach(function(recipe) {
            const recipeName = recipe.querySelector('h3').textContent.toLowerCase();
            if (recipeName.includes(query)) {
                recipe.style.display = 'block';
            } else {
                recipe.style.display = 'none';
            }
        });
    });
});

document.getElementById("search-btn").addEventListener("click", function() {
    let query = document.getElementById("search-input").value.toLowerCase();
    let recipes = document.querySelectorAll(".recipe");
    
    recipes.forEach(function(recipe) {
        let recipeName = recipe.querySelector("h3").textContent.toLowerCase();
        if (recipeName.includes(query)) {
            recipe.style.display = "block"; 
        } else {
            recipe.style.display = "none"; 
        }
    });
});