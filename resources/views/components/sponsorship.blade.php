<div class="container mx-auto">
    <div
        class="marquee-wrappe md w-full bg-green-500 shadow-2xl border border-white/10 backdrop-blur-3xl overflow-hidden">
        <div class="marquee-track flex items-center h-15 whitespace-nowrap" id="marqueeTrack">
            {{-- item js --}}
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const track = document.getElementById('marqueeTrack');

        const items = [
            'UdinPOS',
            'KasirKita',
            'StokCepat',
            'Gudangin',
            'PayFlow',
            'NotaKu',
            'Jualin',
            'LapakPro',
            'RYPAY',
            'RidzzPOS',
            'TokoSmart',
            'ScanPay',
            'KasirX',
            'Stockify',
            'GudangPro',
            'Transaksiin',
            'POSinAja',
            'JualCepat',
            'KassaNow',
            'WarungDigital'
        ];

        const totalItems = [...Array(50)].flatMap(() => items);

        totalItems.forEach(text => {
            const item = document.createElement('div');
            item.className = `
                marquee-item inline-flex items-center px-6 py-4 md:px-8 md:py-5 mx-3 md:mx-4
                text-sm font-bold md:text-base lg:text-lg text-white whitespace-nowrap
            `;
            item.textContent = text;
            track.appendChild(item);
        });

        const trackWidth = track.scrollWidth;
        gsap.to(track, {
            x: `-${trackWidth / 2}px`,
            duration: 1000,
            ease: "none",
            repeat: -1,
            modifiers: {
                x: x => `${parseFloat(x) % (trackWidth / 2)}px`
            }
        });
    });
</script>
