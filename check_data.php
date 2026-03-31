<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ItemPenawaran;
use App\Models\Penawaran;
use App\Models\Material;

try {
    echo "📊 CHECKING DATABASE DATA:\n\n";
    
    // Check penawaran
    $penawaran = Penawaran::count();
    echo "Total Penawaran: {$penawaran}\n";
    
    $approvedPenawaran = Penawaran::where('status', 'disetujui')->count();
    echo "Approved Penawaran: {$approvedPenawaran}\n\n";
    
    // Check item penawaran
    $items = ItemPenawaran::count();
    echo "Total ItemPenawaran: {$items}\n";
    
    $approvedItems = ItemPenawaran::whereHas('penawaran', fn($q) => $q->where('status', 'disetujui'))->count();
    echo "Items in Approved Penawaran: {$approvedItems}\n\n";
    
    // Check material
    $materials = Material::count();
    echo "Total Materials: {$materials}\n\n";
    
    // Show details of approved penawaran
    if ($approvedPenawaran > 0) {
        echo "APPROVED PENAWARAN DETAILS:\n";
        Penawaran::where('status', 'disetujui')->limit(3)->get()->each(function($p) {
            echo "  - {$p->no_penawaran} (Client: {$p->client->nama}, Items: " . $p->items->count() . ")\n";
        });
    }
    
    // Check if any material has price history
    echo "\nCHECKING PRICE HISTORY FOR EACH MATERIAL:\n";
    Material::limit(5)->get()->each(function($m) {
        $count = ItemPenawaran::where('material_id', $m->id)
            ->whereHas('penawaran', fn($q) => $q->where('status', 'disetujui'))
            ->count();
        echo "  Material: {$m->nama} → {$count} approved items\n";
    });
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
