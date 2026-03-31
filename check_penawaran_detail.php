<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ItemPenawaran;
use App\Models\Penawaran;

try {
    echo "📝 PENAWARAN #1 DETAILS:\n\n";
    
    $p = Penawaran::first();
    echo "No: {$p->no_penawaran}\n";
    echo "Status: {$p->status}\n";
    echo "Client: {$p->client->nama}\n";
    echo "Date: {$p->tanggal}\n";
    echo "Items Count: " . $p->items->count() . "\n\n";
    
    echo "ITEMS IN THIS PENAWARAN:\n";
    $p->items->each(function($item) {
        echo "  - Material #{$item->material_id}: {$item->material->nama}\n";
        echo "    Qty: {$item->jumlah}, Harga Asli: {$item->harga_asli}, Harga Jual: {$item->harga_jual}\n";
    });
    
    echo "\n\nQUERY TEST - Get items for Material ID 1:\n";
    $itemsForMat1 = ItemPenawaran::where('material_id', 1)
        ->whereHas('penawaran', fn($q) => $q->where('status', 'disetujui'))
        ->get();
    echo "Result: " . $itemsForMat1->count() . " items\n";
    
    echo "\nQUERY TEST - Get items for Material ID 3:\n";
    $itemsForMat3 = ItemPenawaran::where('material_id', 3)
        ->whereHas('penawaran', fn($q) => $q->where('status', 'disetujui'))
        ->get();
    echo "Result: " . $itemsForMat3->count() . " items\n";
    if ($itemsForMat3->count() > 0) {
        $item = $itemsForMat3->first();
        echo "  - Harga Asli: {$item->harga_asli}\n";
        echo "  - Created: {$item->created_at}\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    dd($e);
}
