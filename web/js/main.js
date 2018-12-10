$(function() {
    $('a.confirmDeletion').on('click', function() {
        if (!confirm('Confirmation de suppression?'))
            return false;
    });

});