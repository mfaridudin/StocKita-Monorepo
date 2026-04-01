@php
    $plans = App\Models\Plan::get();
@endphp

<section id="offer" class="py-24 bg-gradient-to-b from-white to-emerald-50 overflow-hidden">
    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-10">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight">
                Kelola Toko Tanpa Ribet
            </h2>
            <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                Semua kebutuhan inventory, transaksi, dan laporan dalam satu dashboard simpel.
            </p>
        </div>

        <div class="flex justify-center mb-12">
            <div class="bg-gray-100 p-1 rounded-full flex items-center gap-2">
                <button id="monthlyBtn" class="px-5 py-2 rounded-full text-sm font-semibold bg-white shadow">
                    Monthly
                </button>
                <button id="yearlyBtn" class="px-5 py-2 rounded-full text-sm font-semibold text-gray-500">
                    Yearly
                </button>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-8">

            @foreach ($plans as $plan)
                <div
                    class="offer-card 
                            {{ $plan->name == 'Pro' ? 'bg-emerald-500 text-white p-10 shadow-2xl scale-105 relative' : 'bg-white p-8 border shadow-sm hover:shadow-xl' }} 
                            rounded-3xl transition">

                    @if ($plan->name == 'Pro')
                        <span class="absolute top-4 right-4 bg-white text-emerald-600 text-xs px-3 py-1 rounded-full">
                            POPULER
                        </span>
                    @endif

                    <h3 class="text-xl font-semibold mb-2">{{ $plan->name }}</h3>

                    <p class="{{ $plan->name == 'Pro' ? 'opacity-90' : 'text-gray-500' }} mb-6">
                        @if ($plan->name == 'Starter')
                            Untuk bisnis kecil
                        @elseif ($plan->name == 'Business')
                            Untuk skala besar
                        @else
                            Untuk bisnis berkembang
                        @endif
                    </p>

                    <div class="text-4xl font-bold mb-6 price" data-monthly="{{ $plan->price }}"
                        data-yearly="{{ $plan->yearly_price }}">
                        Rp {{ $plan->price }}
                    </div>

                    <ul class="space-y-3 mb-8">
                        @foreach ($plan->features as $feature)
                            <li>✔ {{ $feature }}</li>
                        @endforeach
                    </ul>

                    @if ($plan->name == 'Starter')
                        <button class="w-full py-3 rounded-xl bg-gray-900 text-white pay-btn"
                            data-plan-id="{{ $plan->id }}">
                            Mulai Gratis
                        </button>
                    @elseif($plan->name == 'Pro')
                        <button class="w-full py-3 rounded-xl bg-white text-emerald-600 font-semibold pay-btn"
                            data-plan-id="{{ $plan->id }}">
                            Upgrade Sekarang
                        </button>
                    @else
                        <button class="w-full py-3 rounded-xl bg-gray-900 text-white pay-btn"
                            data-plan-id="{{ $plan->id }}">
                            Pilih Paket
                        </button>
                    @endif

                </div>
            @endforeach

        </div>
    </div>
    </div>
</section>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        let isYearly = false;

        const monthlyBtn = document.getElementById('monthlyBtn');
        const yearlyBtn = document.getElementById('yearlyBtn');
        const prices = document.querySelectorAll('.price');

        function formatRupiah(num) {
            if (num == 0) return 'Rp 0';
            return 'Rp ' + (num / 1000) + 'K';
        }

        function updatePrice() {
            prices.forEach(el => {
                const monthly = el.dataset.monthly;
                const yearly = el.dataset.yearly;

                let value = isYearly ? yearly : monthly;

                el.innerHTML = formatRupiah(value) +
                    (value != 0 ? `<span class="text-lg">/${isYearly ? 'tahun' : 'bulan'}</span>` : '');
            });
        }

        monthlyBtn.onclick = () => {
            isYearly = false;
            monthlyBtn.classList.add('bg-white', 'shadow');
            yearlyBtn.classList.remove('bg-white', 'shadow');
            updatePrice();
        };

        yearlyBtn.onclick = () => {
            isYearly = true;
            yearlyBtn.classList.add('bg-white', 'shadow');
            monthlyBtn.classList.remove('bg-white', 'shadow');
            updatePrice();
        };


        document.querySelectorAll('.pay-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const plan_id = btn.dataset.planId;
                const interval = isYearly ? 'yearly' : 'monthly';

                try {
                    const res = await fetch(`/pay`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            plan_id,
                            interval
                        })
                    });

                    const data = await res.json();

                    // FREE PLAN
                    if (data.free) {
                        alert('Paket gratis aktif!');
                        return;
                    }

                    // MIDTRANS POPUP
                    snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            alert('Pembayaran sukses');
                            location.reload();
                        },
                        onPending: function(result) {
                            alert('Menunggu pembayaran');
                        },
                        onError: function(result) {
                            alert('Pembayaran gagal');
                        }
                    });

                } catch (err) {
                    console.error(err);
                    alert('Terjadi error, cek console');
                }
            });
        });
        updatePrice();
    });
</script>
