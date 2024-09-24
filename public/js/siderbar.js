$(document).ready(function() {
    // Toggle sidebar
    $('#toggleSidebar').on('click', function() {
        $('#sidebar').toggleClass('collapsed'); // Toggle kelas collapsed pada sidebar
        $('.content').toggleClass('collapsed'); // Toggle kelas collapsed pada konten
    });
});