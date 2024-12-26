 
$(function () {
   // Modal logic
    var modal = $('#modal');
    $('#openModal').on('click', () => modal.removeClass('hidden'));
    $('#closeModal').on('click', () => modal.addClass('hidden'));

    $(window).on('click', (event) => {
        if (event.target === modal[0]) {
            $modal.addClass('hidden');
        }
    });
});