<section id="blog" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900">
                Panduan & Tips Bisnis
            </h2>
            <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                Pelajari cara menggunakan sistem dan tingkatkan penjualan tokomu.
            </p>
        </div>

        <div class="space-y-6">
            <a href="/blog/dashboard-pos"
                class="blog-card flex flex-col md:flex-row gap-5 group bg-white border border-gray-200 rounded-2xl p-4 hover:shadow-md transition">

                <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=800"
                    class="w-full md:w-48 h-32 object-cover rounded-xl group-hover:scale-105 transition">

                <div class="flex flex-col justify-center">
                    <span class="text-sm text-emerald-600 font-semibold">
                        Getting Started
                    </span>

                    <h3 class="text-lg font-bold mt-1 group-hover:text-emerald-600 transition">
                        Cara Menggunakan Dashboard POS untuk Pertama Kali
                    </h3>

                    <p class="text-gray-600 text-sm mt-2">
                        Pelajari langkah awal mulai dari login hingga melakukan transaksi pertama.
                    </p>
                </div>
            </a>

            <a href="/blog/kelola-produk"
                class="blog-card flex flex-col md:flex-row gap-5 group bg-white border border-gray-200 rounded-2xl p-4 hover:shadow-md transition">

                <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800"
                    class="w-full md:w-48 h-32 object-cover rounded-xl group-hover:scale-105 transition">

                <div class="flex flex-col justify-center">
                    <span class="text-sm text-emerald-600 font-semibold">
                        Produk & Stok
                    </span>

                    <h3 class="text-lg font-bold mt-1 group-hover:text-emerald-600 transition">
                        Cara Menambahkan dan Mengelola Produk
                    </h3>

                    <p class="text-gray-600 text-sm mt-2">
                        Tambahkan produk baru, atur stok, dan kelola data barang dengan mudah.
                    </p>
                </div>
            </a>

            <a href="/blog/cara-transaksi"
                class="blog-card flex flex-col md:flex-row gap-5 group bg-white border border-gray-200 rounded-2xl p-4 hover:shadow-md transition">

                <img src="https://images.unsplash.com/photo-1556740772-1a741367b93e?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OHx8dHJhbnNhY3Rpb258ZW58MHx8MHx8fDA%3D"
                    class="w-full md:w-48 h-32 object-cover rounded-xl group-hover:scale-105 transition">

                <div class="flex flex-col justify-center">
                    <span class="text-sm text-emerald-600 font-semibold">
                        Transaksi
                    </span>

                    <h3 class="text-lg font-bold mt-1 group-hover:text-emerald-600 transition">
                        Cara Melakukan Transaksi di Sistem POS
                    </h3>

                    <p class="text-gray-600 text-sm mt-2">
                        Lakukan transaksi penjualan dengan cepat melalui sistem kasir yang sederhana dan efisien.
                    </p>
                </div>
            </a>

        </div>
    </div>
</section>

<script>
    gsap.registerPlugin(ScrollTrigger);

    gsap.set(".blog-card", {
        willChange: "transform, opacity"
    });

    gsap.utils.toArray(".blog-card").forEach((el, i) => {
        gsap.fromTo(el, {
            opacity: 0,
            y: 100,
            scale: 0.96
        }, {
            opacity: 1,
            y: 0,
            scale: 1,
            duration: 0.2,
            ease: "power3.out",
            delay: i * 0.08,
            scrollTrigger: {
                trigger: el,
                start: "top 90%",
                end: "top 60%",
                scrub: 0.8,
            }
        });
    });
</script>
