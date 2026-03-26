<x-app-layout title="Transaksi Baru">

    <div class="grid grid-cols-12 gap-6">

        <!-- LEFT: PRODUK -->
        <div class="col-span-7 bg-white p-4 rounded-xl shadow space-y-4">

            <input type="text" id="search" placeholder="Cari produk..." class="w-full px-4 py-2 border rounded-lg">

            <div class="grid grid-cols-3 gap-3 max-h-[500px] overflow-y-auto">
                @foreach ($products as $product)
                    <div class="border rounded-lg p-3 cursor-pointer hover:shadow"
                        onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})">

                        <img src="{{ asset('storage/' . $product->image) }}"
                            class="w-full h-24 object-cover rounded mb-2"
                            onerror="this.src='https://via.placeholder.com/100'">

                        <p class="text-sm font-semibold">{{ $product->name }}</p>
                        <p class="text-xs text-gray-500">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    </div>
                @endforeach
            </div>

        </div>

        <!-- RIGHT: CART -->
        <div class="col-span-5 bg-white p-4 rounded-xl shadow flex flex-col">

            <!-- CUSTOMER -->
            <div class="mb-3">
                <input type="text" placeholder="Nama Customer (opsional)" class="w-full px-3 py-2 border rounded-lg">
            </div>

            <!-- WAREHOUSE -->
            <div class="mb-3">
                <select id="warehouse" class="w-full px-3 py-2 border rounded-lg">
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">
                            {{ $warehouse->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- CART LIST -->
            <div id="cart" class="flex-1 overflow-y-auto space-y-2"></div>

            <!-- TOTAL -->
            <div class="border-t pt-3 mt-3 space-y-2">
                <p class="flex justify-between font-semibold">
                    <span>Total</span>
                    <span id="total">Rp 0</span>
                </p>

                <button onclick="submitTransaction()"
                    class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                    Bayar
                </button>
            </div>

        </div>

    </div>

    <script>
        let cart = [];

        function addToCart(id, name, price) {

            let existing = cart.find(item => item.id === id);

            if (existing) {
                existing.qty++;
            } else {
                cart.push({
                    id,
                    name,
                    price,
                    qty: 1
                });
            }

            renderCart();
        }

        function renderCart() {
            const cartEl = document.getElementById('cart');
            cartEl.innerHTML = '';

            let total = 0;

            cart.forEach((item, index) => {

                let subtotal = item.price * item.qty;
                total += subtotal;

                cartEl.innerHTML += `
            <div class="flex justify-between items-center border p-2 rounded">
                <div>
                    <p class="text-sm font-semibold">${item.name}</p>
                    <p class="text-xs text-gray-500">
                        Rp ${item.price.toLocaleString()} x ${item.qty}
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button onclick="changeQty(${index}, -1)">-</button>
                    <span>${item.qty}</span>
                    <button onclick="changeQty(${index}, 1)">+</button>
                </div>
            </div>
        `;
            });

            document.getElementById('total').innerText =
                'Rp ' + total.toLocaleString();
        }

        function changeQty(index, change) {
            cart[index].qty += change;

            if (cart[index].qty <= 0) {
                cart.splice(index, 1);
            }

            renderCart();
        }
        async function submitTransaction() {

            if (cart.length === 0) {
                alert('Keranjang kosong');
                return;
            }

            try {
                // 🔥 loading state
                const btn = event.target;
                btn.innerText = "Memproses...";
                btn.disabled = true;

                let res = await fetch('/transactions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        customer_id: null,
                        items: cart
                    })
                });

                let data = await res.json();

                if (!res.ok) {
                    throw new Error(data.error || 'Terjadi kesalahan');
                }

                // 🔥 SUCCESS UX
                alert('✅ Transaksi berhasil!');

                // redirect ke detail
                window.location.href = `/transactions/${data.data.id}`;

            } catch (err) {
                alert('❌ ' + err.message);
            }
        }
    </script>

</x-app-layout>
