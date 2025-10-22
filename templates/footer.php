<?php
// File: templates/footer.php (FINAL + Tombol Scroll)
?>

    <footer class="footer">
        <p>
            Copyright &copy; <?php echo date("Y"); ?> Created by Andre Wijaya. 221011400791. 07TTPLP019. All rights reserved.
        </p>
    </footer>

    <div class="scroll-button-container">
        <button id="scrollToTopBtn" title="Kembali ke atas">&uarr;</button>
        <button id="scrollToBottomBtn" title="Pergi ke bawah">&darr;</button>
    </div>

    <script>
        // Ambil elemen-elemen yang kita butuhkan
        const scroller = document.querySelector('.container'); // PENTING: Target kita adalah .container
        const topBtn = document.getElementById('scrollToTopBtn');
        const bottomBtn = document.getElementById('scrollToBottomBtn');

        if (scroller) {
            // 1. Logika untuk tombol "Ke Atas"
            topBtn.addEventListener('click', () => {
                scroller.scrollTo({
                    top: 0,
                    behavior: 'smooth' // Animasi scroll halus
                });
            });

            // 2. Logika untuk tombol "Ke Bawah"
            bottomBtn.addEventListener('click', () => {
                scroller.scrollTo({
                    top: scroller.scrollHeight, // Scroll ke paling bawah
                    behavior: 'smooth'
                });
            });

            // 3. Logika untuk menampilkan/menyembunyikan tombol "Ke Atas"
            scroller.addEventListener('scroll', () => {
                if (scroller.scrollTop > 150) { // Jika sudah scroll ke bawah 150px
                    topBtn.style.display = 'block'; // Tampilkan tombol
                } else {
                    topBtn.style.display = 'none'; // Sembunyikan lagi
                }
            });
        }
    </script>
    </body>
</html>