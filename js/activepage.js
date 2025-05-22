document.addEventListener("DOMContentLoaded", function() {
    let currentUrl = window.location.href;
    const pages = {
        "index.php": "home-link",
        "about.php": "about-link",
        "recipe.php": "recipe-link",
        "blog.php": "blog-link",
        "contact.php": "contact-link"
    };
    for (let page in pages) {
        if (currentUrl.includes(page)) {
            document.getElementById(pages[page]).classList.add("active");
        }
    }
});
