<?php

namespace Tests\Feature;

use App\Models\Penawaran;
use App\Models\ItemPenawaran;
use App\Models\Material;
use App\Models\Inventory;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * End-to-End Test: Critical Fixes Verification
 * Tests the 3 critical fixes applied:
 * 1. Inventory negative stock prevention
 * 2. DSS method complete
 * 3. Material nullable + transaction wrapper
 */
class CriticalFixesTest extends TestCase
{
    use RefreshDatabase;

    private $client;
    private $user;
    private $material;
    private $inventory;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        $this->user = User::factory()->create(['role' => 'admin']);
        $this->client = Client::factory()->create();
        $this->material = Material::create([
            'kode' => 'TEST-001',
            'nama' => 'Test Material',
            'satuan' => 'pcs',
            'harga' => 1000,
            'supplier_id' => null,
            'type' => 'BARANG',
            'track_inventory' => true,
        ]);
        
        $this->inventory = Inventory::create([
            'material_id' => $this->material->id,
            'stok' => 100, // Only 100 pcs available
        ]);
        
        $this->actingAs($this->user);
    }

    /**
     * TEST #1: Verify negative stock prevention
     * Should FAIL when trying to reduce stok below available
     */
    public function test_cannot_reduce_inventory_below_available_stock()
    {
        // Create penawaran with 150 items (more than available 100)
        $penawaran = Penawaran::create([
            'no_penawaran' => 'TEST-001',
            'client_id' => $this->client->id,
            'tanggal' => now()->toDateString(),
            'status' => 'draft',
            'total_biaya' => 100000,
            'total_margin' => 10000,
            'ppn' => 12100,
            'grand_total_with_ppn' => 122100,
        ]);

        ItemPenawaran::create([
            'penawaran_id' => $penawaran->id,
            'material_id' => $this->material->id,
            'nama' => 'Test Item',
            'satuan' => 'pcs',
            'jumlah' => 150, // MORE than available!
            'harga_asli' => 1000,
            'persentase_margin' => 10,
            'harga_jual' => 1100,
        ]);

        // Try to approve - should fail with our new validation
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Stok tidak cukup');

        // Simulate approval which calls reduceInventory
        $penawaran->update(['status' => 'disetujui']);
        
        // Get fresh instance and manually call reduceInventory via reflection
        // In real scenario, updateStatus() calls this, but we can test directly
        $controller = new \App\Http\Controllers\PenawaranController();
        $reflectionMethod = new \ReflectionMethod($controller, 'reduceInventory');
        $reflectionMethod->setAccessible(true);
        
        // This should throw exception
        $reflectionMethod->invoke($controller, $penawaran->fresh());
    }

    /**
     * TEST #2: Verify transaction rollback on material creation failure
     */
    public function test_transaction_rollback_on_material_creation_failure()
    {
        // Create penawaran with BoQ items (no material_id)
        $penawaran = Penawaran::create([
            'no_penawaran' => 'TEST-BOQ-001',
            'client_id' => $this->client->id,
            'tanggal' => now()->toDateString(),
            'status' => 'draft',
            'ai_status' => 'analyzed',
            'total_biaya' => 50000,
            'total_margin' => 5000,
            'ppn' => 6050,
            'grand_total_with_ppn' => 61050,
        ]);

        ItemPenawaran::create([
            'penawaran_id' => $penawaran->id,
            'material_id' => null, // BoQ item without material
            'nama' => 'BoQ Item 1',
            'satuan' => 'pcs',
            'jumlah' => 50,
            'harga_asli' => 1000,
            'persentase_margin' => 10,
            'harga_jual' => 1100,
        ]);

        // Call DSS approvePenawaran - should validate material_id
        $response = $this->postJson(route('dss.approve'), [
            'penawaran_id' => $penawaran->id,
            'user_decision' => 'approve',
            'notes' => '',
        ]);

        // Should fail validation
        $response->assertJson([
            'success' => false,
        ]);

        // Verify penawaran status NOT changed
        $this->assertEquals('draft', $penawaran->fresh()->status);
    }

    /**
     * TEST #3: Verify successful approval with sufficient stock
     */
    public function test_successful_approval_with_sufficient_stock()
    {
        // Create penawaran with 50 items (less than available 100)
        $penawaran = Penawaran::create([
            'no_penawaran' => 'TEST-APPROVE-001',
            'client_id' => $this->client->id,
            'tanggal' => now()->toDateString(),
            'status' => 'draft',
            'ai_status' => 'analyzed',
            'ai_prediksi_lr' => 55000,
            'ai_prediksi_ma' => 54000,
            'margin_status' => 'aman',
            'total_biaya' => 50000,
            'total_margin' => 5000,
            'ppn' => 6050,
            'grand_total_with_ppn' => 61050,
        ]);

        ItemPenawaran::create([
            'penawaran_id' => $penawaran->id,
            'material_id' => $this->material->id, // Has material
            'nama' => 'Test Item',
            'satuan' => 'pcs',
            'jumlah' => 50, // Less than available 100
            'harga_asli' => 1000,
            'persentase_margin' => 10,
            'harga_jual' => 1100,
        ]);

        // Update status to disetujui
        $penawaran->update(['status' => 'disetujui']);
        
        // Manually reduce inventory (what normally happens on approval)
        $controller = new \App\Http\Controllers\PenawaranController();
        $reflectionMethod = new \ReflectionMethod($controller, 'reduceInventory');
        $reflectionMethod->setAccessible(true);
        $reflectionMethod->invoke($controller, $penawaran->fresh());

        // Verify stok reduced
        $updatedInventory = Inventory::find($this->inventory->id);
        $this->assertEquals(50, $updatedInventory->stok); // 100 - 50 = 50

        // Verify log entry created
        $this->assertDatabaseHas('log_inventory', [
            'material_id' => $this->material->id,
            'jenis' => 'keluar',
            'jumlah' => 50,
        ]);
    }

    /**
     * TEST #4: Verify restoration on status change
     */
    public function test_inventory_restored_when_status_reverted()
    {
        // First reduce stok
        $penawaran = Penawaran::create([
            'no_penawaran' => 'TEST-RESTORE-001',
            'client_id' => $this->client->id,
            'tanggal' => now()->toDateString(),
            'status' => 'disetujui', // Already approved
            'total_biaya' => 50000,
            'total_margin' => 5000,
            'ppn' => 6050,
            'grand_total_with_ppn' => 61050,
        ]);

        ItemPenawaran::create([
            'penawaran_id' => $penawaran->id,
            'material_id' => $this->material->id,
            'nama' => 'Test Item',
            'satuan' => 'pcs',
            'jumlah' => 50,
            'harga_asli' => 1000,
            'persentase_margin' => 10,
            'harga_jual' => 1100,
        ]);

        // Manually reduce stok
        $inventory = Inventory::find($this->inventory->id);
        $inventory->stok -= 50;
        $inventory->save();

        // Verify reduced
        $this->assertEquals(50, Inventory::find($this->inventory->id)->stok);

        // Now restore
        $controller = new \App\Http\Controllers\PenawaranController();
        $reflectionMethod = new \ReflectionMethod($controller, 'restoreInventory');
        $reflectionMethod->setAccessible(true);
        $reflectionMethod->invoke($controller, $penawaran, 'dibatalkan');

        // Verify restored
        $this->assertEquals(100, Inventory::find($this->inventory->id)->stok);

        // Verify log entry created
        $this->assertDatabaseHas('log_inventory', [
            'material_id' => $this->material->id,
            'jenis' => 'masuk',
            'jumlah' => 50,
        ]);
    }
}
