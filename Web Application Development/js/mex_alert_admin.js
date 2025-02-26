function setLocalStorage(notificationType) {
    window.localStorage.setItem(notificationType, 'true');
}

window.addEventListener('load', function() {
    var notification = document.getElementById('notification');

    if (window.localStorage.getItem('allShipped') === 'true') {
        notification.textContent = 'Tutti gli ordini sono stati spediti';
        notification.classList.add('show');
        setTimeout(function() {
            notification.classList.remove('show');
        }, 3000);
        window.localStorage.removeItem('allShipped');
    }

    if (window.localStorage.getItem('orderShipped') === 'true') {
        notification.textContent = 'Ordine spedito';
        notification.classList.add('show');
        setTimeout(function() {
            notification.classList.remove('show');
        }, 3000);
        window.localStorage.removeItem('orderShipped');
    }

    if (window.localStorage.getItem('orderDeleted') === 'true') {
        notification.textContent = 'Ordine eliminato';
        notification.classList.add('show');
        setTimeout(function() {
            notification.classList.remove('show');
        }, 3000);
        window.localStorage.removeItem('orderDeleted');
    }

    if (window.localStorage.getItem('oneRemoved') === 'true') {
        notification.textContent = 'Rimosso uno';
        notification.classList.add('show');
        setTimeout(function() {
            notification.classList.remove('show');
        }, 3000);
        window.localStorage.removeItem('oneRemoved');
    }
});
