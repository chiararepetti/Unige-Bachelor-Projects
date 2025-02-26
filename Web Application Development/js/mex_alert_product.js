document.querySelector('button[name="add_to_cart"]' ).addEventListener('click', function() {
    window.localStorage.setItem('showNotification', 'true');
});

window.addEventListener('load', function() {
    if (window.localStorage.getItem('showNotification') === 'true') {
        var notification = document.getElementById('notification');
        notification.classList.add('show');

        setTimeout(function() {
            notification.classList.remove('show');
        }, 3000);

        window.localStorage.removeItem('showNotification');
    }
});