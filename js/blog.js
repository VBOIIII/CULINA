document.addEventListener('DOMContentLoaded', function () {
    const addBlogBtn = document.querySelector('.add-blog-btn');
    const blogPopupContainer = document.querySelector('.blog-popup-container');
    const closeBtn = document.querySelector('#close-btn');

    addBlogBtn?.addEventListener('click', function () {
        blogPopupContainer.style.display = 'block';
        document.body.classList.add('show-blog-popup');
    });

    closeBtn?.addEventListener('click', function () {
        blogPopupContainer.style.display = 'none';
        document.body.classList.remove('show-blog-popup');
    });

    blogPopupContainer?.addEventListener('click', function (e) {
        if (e.target === blogPopupContainer) {
            blogPopupContainer.style.display = 'none';
            document.body.classList.remove('show-blog-popup');
        }
    });
});