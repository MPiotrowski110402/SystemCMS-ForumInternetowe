$(document).ready(function() {
    // Obsługuje kliknięcia w linki w menu
    $('.load-content').on('click', function(e) {
        e.preventDefault(); // Zatrzymuje domyślne działanie linku

        var page = $(this).data('page'); // Pobiera wartość data-page

        if (page) {
            $.ajax({
                url: 'adminPanel.php',
                type: 'GET',
                data: { page: page },
                success: function(response) {
                    $('#content').html(response);
                },
                error: function() {
                    alert('Błąd ładowania strony.');
                }
            });
        } else {
            console.log('Brak parametru data-page!');
        }
    });

    // Obsługa kliknięcia w przycisk Edytuj
    $(document).on('click', '.edit-btn', function() {
        var userId = $(this).data('id'); // Pobiera ID użytkownika

        if (!userId) {
            alert('Błąd: brak ID użytkownika!');
            return;
        }

        $.ajax({
            url: 'adminPanel.php',
            type: 'POST',
            data: { id: userId },
            success: function(response) {
                $('#content').html(response); // Wyświetla formularz edycji
            },
            error: function() {
                alert('Błąd podczas edycji użytkownika.');
            }
        });
    });
});
