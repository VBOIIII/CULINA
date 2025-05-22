$(document).ready(function() {
    let currentIndex = 0;
    const items = $('.carousel-item');
    const totalItems = items.length;
    const visibleItems = 1;  

    function showItems() {
        const offset = -(currentIndex * 100); 
        $('.carousel').css('transform', 'translateX(' + offset + '%)');
        items.removeClass('center');
        items.eq(currentIndex).addClass('center');
    }

    showItems();

    $('.carousel-next').click(function() {
        currentIndex = (currentIndex + 1) % totalItems;
        showItems();
    });

    $('.carousel-prev').click(function() {
        currentIndex = (currentIndex - 1 + totalItems) % totalItems;
        showItems();
    });
});
