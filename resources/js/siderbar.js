document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleButton = document.getElementById('sidebarToggle');
    const app = document.getElementById('app');

    toggleButton.addEventListener('click', function() {
        sidebar.classList.toggle('active');

        // Mengubah margin konten berdasarkan status sidebar
        if (sidebar.classList.contains('active')) {
            app.style.marginLeft = '0'; // Konten tanpa margin saat sidebar tertutup
        } else {
            app.style.marginLeft = '250px'; // Mengembalikan margin saat sidebar terbuka
        }
    });
});
