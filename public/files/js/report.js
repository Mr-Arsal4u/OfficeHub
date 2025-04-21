$(document).ready(function () {
    // Show the modal
    $('#openModal').on('click', function () {
        $('#myModal').modal('show');
    });

    // Hide the modal
    $('#closeModal').on('click', function () {
        $('#myModal').modal('hide');
    });
});
