// Efek scroll pada navbar
const navbar = document.querySelector('.navbar');
window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled'); // Tambahkan class saat discroll
    } else {
        navbar.classList.remove('scrolled'); // Hapus class jika kembali ke atas
    }
});

// Toggle Menu Mobile
const mobileMenuBtn = document.querySelector('.mobile-menu-btn'); // Tombol menu mobile
const mobileMenu = document.querySelector('.mobile-menu'); // Menu mobile

mobileMenuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('left-[-100%]'); // Toggle posisi keluar masuk
    mobileMenu.classList.toggle('left-0');
});

// Tutup menu mobile jika link diklik
const mobileMenuLinks = document.querySelectorAll('.mobile-menu a');
mobileMenuLinks.forEach(link => {
    link.addEventListener('click', () => {
        mobileMenu.classList.add('left-[-100%]'); // Sembunyikan menu
        mobileMenu.classList.remove('left-0');
    });
});

// Scroll halus untuk anchor link (tentang kami, produk, outlet, kontak)
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        // Abaikan tombol order
        if (this.getAttribute('href') === '#' || this.classList.contains('order-btn')) {
            return;
        }

        e.preventDefault(); // Cegah scroll default
        const targetId = this.getAttribute('href'); // Ambil ID target
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - 80, // Scroll ke posisi target dikurangi offset navbar
                behavior: 'smooth' // Efek scroll halus
            });
        }
    });
});

// Fungsi Modal order
const orderButtons = document.querySelectorAll('a[href="#"], .mobile-menu a[href="#"]'); // Tombol pemicu modal
const orderModal = document.getElementById('orderModal'); // Elemen modal
const closeModal = document.getElementById('closeModal'); // Tombol close (X) pada modal
const outletBtn = document.getElementById('outletBtn'); // Tombol menuju outlet
const contactBtn = document.getElementById('contactBtn'); // Tombol menuju contact


// Tampilkan modal saat tombol order diklik
orderButtons.forEach(button => {
    button.addEventListener('click', (e) => {
        e.preventDefault(); // Mencegah aksi default link
        orderModal.classList.remove('hidden'); // Tampilkan modal
        document.body.style.overflow = 'hidden'; // Nonaktifkan scroll di body
    });
}); 

// Fungsi untuk menutup modal
function closeOrderModal() {
    orderModal.classList.add('hidden'); // Sembunyikan modal
    document.body.style.overflow = 'auto'; // Kembalikan scroll body
}


// Tutup modal saat tombol X diklik
closeModal.addEventListener('click', closeOrderModal);

// Tutup modal saat mengklik area luar modal
orderModal.addEventListener('click', (e) => {
    if (e.target === orderModal) {
        closeOrderModal(); // Tutup modal jika area luar yang diklik
    }
});

// Tutup modal dengan tombol Escape (X)
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !orderModal.classList.contains('hidden')) {
        closeOrderModal(); // Tutup jika Escape ditekan dan modal sedang terbuka
    }
});

// Fungsi Filter Outlet
document.addEventListener('DOMContentLoaded', function() {
    const regionBtns = document.querySelectorAll('.region-btn'); // Tombol filter wilayah
    const outletCards = document.querySelectorAll('.location-card'); // Kartu outlet

    // Filter outlet berdasarkan wilayah
    regionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Perbarui tombol aktif
            regionBtns.forEach(b => b.classList.remove('active', 'bg-[#60B5FF]', 'text-white'));
            this.classList.add('active', 'bg-[#60B5FF]', 'text-white');

            const region = this.dataset.region; // Ambil data wilayah dari tombol

            // Tampilkan/sembunyikan outlet berdasarkan wilayah
            outletCards.forEach(card => {
                if (region === 'all' || card.dataset.region === region) {
                    card.style.display = 'block'; // Tampilkan
                } else {
                    card.style.display = 'none'; // Sembunyikan
                }
            });
        });
    });
});

// Tutup modal jika tombol outlet atau contact diklik
outletBtn.addEventListener('click', closeOrderModal);
contactBtn.addEventListener('click', closeOrderModal);

// Pengiriman form contact dengan notifikasi
const contactForm = document.querySelector('.contact-form form');
if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Cegah reload form

        // Tampilkan notifikasi
        const notification = document.getElementById('notification');
        notification.classList.remove('hidden');
        notification.classList.add('notification');
        notification.style.bottom = '30px';

        // Reset form
        this.reset();

        // Sembunyikan notifikasi setelah 5 detik
        setTimeout(() => {
            hideNotification();
        }, 5000);
    });
}

// Fungsi untuk menyembunyikan notifikasi
function hideNotification() {
    const notification = document.getElementById('notification');
    notification.classList.add('hide'); // Animasi sembunyi
    setTimeout(() => {
        notification.classList.remove('notification', 'hide');
        notification.style.bottom = '-100px';
        notification.classList.add('hidden');
    }, 500);
}

// Tombol close notifikasi
const closeNotification = document.getElementById('closeNotification');
if (closeNotification) {
    closeNotification.addEventListener('click', hideNotification);
}
