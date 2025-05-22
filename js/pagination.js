let currentCategory = 'home';
let currentPage = 1;
const recipesPerPage = 6;

function createPagination(totalPages, currentPage) {
    let paginationHTML = '<ul class="pagination">';
    if (currentPage > 1) {
        paginationHTML += `<li class="page-item previous"><a href="#" onclick="navigatePage(${currentPage - 1})">Previous</a></li>`;
    }
    for (let i = 1; i <= totalPages; i++) {
        paginationHTML += `
            <li class="page-item ${i === currentPage ? 'active' : ''}">
                <a href="#" onclick="navigatePage(${i})">${i}</a>
            </li>
        `;
    }
    if (currentPage < totalPages) {
        paginationHTML += `<li class="page-item next"><a href="#" onclick="navigatePage(${currentPage + 1})">Next</a></li>`;
    }
    paginationHTML += '</ul>';
    document.getElementById('pagination').innerHTML = paginationHTML;
}

function navigatePage(page) {
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    updateContentForPage(page);
    createPagination(totalPages, currentPage);
}

function updateContentForPage(page) {
    const startIndex = (page - 1) * recipesPerPage;
    const endIndex = page * recipesPerPage;
    const selectedRecipes = recipes[currentCategory].slice(startIndex, endIndex);
    const container = document.getElementById(`${currentCategory}-recipe-container`);
    container.innerHTML = '';
    selectedRecipes.forEach(recipe => {
        const recipeElement = document.createElement("div");
        recipeElement.classList.add("recipe");
        recipeElement.innerHTML = `
            <img src="${recipe.image}" alt="${recipe.name}" />
            <div class="recipe-info">
                <h3>${recipe.name}</h3>
                <p><strong>Time to Cook:</strong> ${recipe.time}</p>
                <p><strong>Ingredients:</strong> ${recipe.ingredients}</p>
                <p><strong>Instructions:</strong> ${recipe.instructions}</p>
            </div>
        `;
        container.appendChild(recipeElement);
    });
}

function showCategory(category) {
    currentCategory = category;
    currentPage = 1;
    totalPages = pages_by_category[category]; 
    updateContentForPage(currentPage);
    createPagination(totalPages, currentPage);
}

document.addEventListener('DOMContentLoaded', () => {
    showCategory('home');
});
