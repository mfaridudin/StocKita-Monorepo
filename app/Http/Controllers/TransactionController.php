<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stats = [
            'total' => Transaction::count(),
            'total_amount' => Transaction::sum('total'),
            'pending' => Transaction::where('status', '!=', 'paid')->count(),
            'items' => TransactionItem::count(),
        ];
        $transactions = Transaction::with('customer')
            ->latest()
            ->paginate(10);

        return view('transaksi.index', compact('transactions', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();

        return view('transaksi.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            if (empty($request->items)) {
                return response()->json(['error' => 'Keranjang kosong'], 400);
            }

            $invoice = 'INV-'.now()->format('YmdHis');

            $total = 0;

            foreach ($request->items as $item) {
                $total += $item['qty'] * $item['price'];
            }

            $paid = 1000000;
            $change = $paid - $total;

            if ($change < 0) {
                return response()->json([
                    'error' => 'Uang kurang!',
                ], 400);
            }

            $transaction = Transaction::create([
                'invoice_code' => $invoice,
                'customer_id' => $request->customer_id ?? null,
                'total' => $total,
                'paid' => $paid,
                'change' => $change,
                'status' => 'paid',
                'created_by' => auth()->id(),

            ]);

            foreach ($request->items as $item) {

                $productId = $item['product_id'];
                $qty = $item['qty'];
                $price = $item['price'];

                $totalStock = Stock::where('product_id', $productId)->sum('qty');

                if ($totalStock < $qty) {
                    throw new \Exception("Stok produk ID $productId tidak cukup");
                }

                $transactionItem = TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $productId,
                    'qty' => $qty,
                    'price' => $price,
                    'subtotal' => $qty * $price,
                ]);

                // $total += $qty * $price;

                $stocks = Stock::where('product_id', $productId)
                    ->where('qty', '>', 0)
                    ->orderByDesc('qty')
                    ->lockForUpdate()
                    ->get();

                $remaining = $qty;

                foreach ($stocks as $stock) {

                    if ($remaining <= 0) {
                        break;
                    }

                    $take = min($stock->qty, $remaining);

                    $stock->decrement('qty', $take);

                    $remaining -= $take;
                }
            }

            $transaction->update([
                'total' => $total,
            ]);

            DB::commit();

            // buat struk
            $transaction->load('items.product');

            $receiptPath = $this->generateReceipt($transaction);

            $transaction->update([
                'receipt' => $receiptPath,
            ]);

            return response()->json([
                'message' => 'Transaksi berhasil',
                'data' => $transaction,
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $transaction = Transaction::with(['items.product', 'customer'])
            ->findOrFail($id);

        return view('transaksi.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function generateReceipt($transaction)
    {
        $manager = new ImageManager(new Driver);

        $width = 350;
        $leftX = 35;
        $center = $width / 2;

        $tempHeight = 2000;

        $img = $manager->create($width, $tempHeight)->fill('white');

        $fontPath = public_path('fonts/RobotoMono-Regular.ttf');

        $y = 30;

        $img->text(strtoupper(setting('store_name') ?? 'TOKO'), $center, $y, function ($font) use ($fontPath) {
            $font->file($fontPath);
            $font->size(22);
            $font->align('center');
        });

        $y += 25;

        $address = strtoupper(setting('store_address') ?? 'ALAMAT TOKO');

        $charPerLine = floor(($width - ($leftX * 2)) / 7);
        $lines = explode("\n", wordwrap($address, $charPerLine, "\n", true));

        foreach ($lines as $line) {
            $img->text($line, $center, $y, function ($font) use ($fontPath) {
                $font->file($fontPath);
                $font->size(12);
                $font->align('center');
            });

            $y += 16;
        }

        $y += 5;

        $img->text(receipt_line(), $leftX, $y, fn ($font) => $font->file($fontPath)->size(12));
        $y += 20;

        $img->text($transaction->invoice_code, $leftX, $y, fn ($font) => $font->file($fontPath)->size(12));
        $y += 18;

        $img->text($transaction->created_at->format('d/m/Y H:i'), $leftX, $y, fn ($font) => $font->file($fontPath)->size(12));
        $y += 20;

        $img->text(receipt_line(), $leftX, $y, fn ($font) => $font->file($fontPath)->size(12));
        $y += 20;

        foreach ($transaction->items as $item) {

            $name = strtoupper($item->product->name);
            $lines = receipt_wrap($name, 20);

            foreach ($lines as $i => $lineText) {

                if ($i === 0) {
                    $left = $lineText.' x'.$item->qty;
                    $right = 'Rp '.number_format($item->subtotal, 0, ',', '.');

                    $img->text(
                        receipt_format($left, $right),
                        $leftX,
                        $y,
                        fn ($font) => $font->file($fontPath)->size(12)
                    );
                } else {
                    $img->text(
                        $lineText,
                        $leftX,
                        $y,
                        fn ($font) => $font->file($fontPath)->size(12)
                    );
                }

                $y += 18;
            }
        }

        $y += 10;

        $img->text(receipt_line(), $leftX, $y, fn ($font) => $font->file($fontPath)->size(12));
        $y += 20;

        $img->text(
            receipt_format('TOTAL', 'Rp '.number_format($transaction->total, 0, ',', '.')),
            $leftX,
            $y,
            fn ($font) => $font->file($fontPath)->size(12)
        );

        $y += 18;

        $img->text(
            receipt_format('TUNAI', 'Rp '.number_format($transaction->paid, 0, ',', '.')),
            $leftX,
            $y,
            fn ($font) => $font->file($fontPath)->size(12)
        );

        $y += 18;

        $img->text(
            receipt_format('KEMBALI', 'Rp '.number_format($transaction->change, 0, ',', '.')),
            $leftX,
            $y,
            fn ($font) => $font->file($fontPath)->size(12)
        );

        $y += 25;

        $img->text(receipt_line(), $leftX, $y, fn ($font) => $font->file($fontPath)->size(12));
        $y += 25;

        $img->text('TERIMA KASIH', $center, $y, function ($font) use ($fontPath) {
            $font->file($fontPath);
            $font->size(12);
            $font->align('center');
        });

        $y += 18;

        $img->text('SELAMAT BERBELANJA KEMBALI', $center, $y, function ($font) use ($fontPath) {
            $font->file($fontPath);
            $font->size(11);
            $font->align('center');
        });

        $finalHeight = $y + 20;

        $img->crop($width, $finalHeight, 0, 0);

        $dir = storage_path('app/public/receipts');

        if (! file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $path = $dir.'/receipt-'.$transaction->id.'.png';

        $img->save($path);

        return 'receipts/receipt-'.$transaction->id.'.png';
    }
}
