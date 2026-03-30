<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Penawaran;
use App\Models\Client;
use App\Models\Material;
use App\Models\Inventory;
use App\Models\ItemPenawaran;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PenawaranWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $client;
    protected $materials;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user
        $this->user = User::factory()->create();

        // Create test client
        $this->client = Client::create([
            'nama' => 'PT Workflow Test',
            'email' => 'workflow@test.com',
            'telepon' => '081234567890',
            'alamat' => 'Jl. Test No. 1',
            'kontak' => 'John Doe',
        ]);

        // Create test materials with inventory
        $this->materials = [];
        for ($i = 1; $i <= 3; $i++) {
            $material = Material::create([
                'nama' => "Material $i",
                'type' => 'BARANG',
                'satuan' => $i === 1 ? 'pcs' : ($i === 2 ? 'meter' : 'kg'),
                'harga' => $i * 50000,
                'kode' => "MAT00$i",
            ]);

            Inventory::create([
                'material_id' => $material->id,
                'stok' => 100 * $i,
                'minimal_stok' => 10,
            ]);

            $this->materials[] = $material;
        }

        $this->actingAs($this->user);
    }

    // ========================
    // WORKFLOW 1: MANUAL CREATION
    // ========================

    /**
     * WORKFLOW 1: User creates penawaran manually
     * 1. Navigate to create page
     * 2. Fill form with client and items
     * 3. Analyze with AI
     * 4. Save penawaran
     */
    public function test_workflow_manual_creation_complete_flow()
    {
        // Step 1: Access form (implicit in API)
        
        // Step 2: Analyze before saving
        $analysisPayload = [
            'client_id' => $this->client->id,
            'tanggal' => now()->toDateString(),
            'items' => [
                [
                    'material_id' => $this->materials[0]->id,
                    'jumlah' => 5,
                    'harga_asli' => $this->materials[0]->harga,
                    'persentase_margin' => 10,
                ],
                [
                    'material_id' => $this->materials[1]->id,
                    'jumlah' => 10,
                    'harga_asli' => $this->materials[1]->harga,
                    'persentase_margin' => 15,
                ]
            ]
        ];

        $analysisResponse = $this->postJson(route('penawaran.analyze-manual'), $analysisPayload);

        $analysisResponse->assertStatus(200);
        $analysisResponse->assertJson(['success' => true]);

        // Verify analysis results structure
        $analysis = $analysisResponse->json();
        $this->assertNotEmpty($analysis['risk_level']);
        $this->assertNotEmpty($analysis['recommendation']);
        $this->assertArrayHasKey('lr', $analysis['predictions']);
        $this->assertArrayHasKey('ma', $analysis['predictions']);

        // Step 3: Store penawaran
        $storePayload = [
            'no_penawaran' => Penawaran::generateNoPenawaran(),
            'client_id' => $this->client->id,
            'tanggal' => now()->toDateString(),
            'status' => 'draft',
            'items' => [
                [
                    'material_id' => $this->materials[0]->id,
                    'jumlah' => 5,
                    'harga_asli' => $this->materials[0]->harga,
                    'persentase_margin' => 10,
                ],
                [
                    'material_id' => $this->materials[1]->id,
                    'jumlah' => 10,
                    'harga_asli' => $this->materials[1]->harga,
                    'persentase_margin' => 15,
                ]
            ]
        ];

        $storeResponse = $this->postJson(route('penawaran.store'), $storePayload);

        $storeResponse->assertStatus(302); // Redirect after successful store
        $this->assertDatabaseHas('penawaran', [
            'client_id' => $this->client->id,
            'status' => 'draft'
        ]);

        // Verify items were created
        $penawaran = Penawaran::where('client_id', $this->client->id)->first();
        $this->assertEquals(2, $penawaran->items()->count());
    }

    /**
     * WORKFLOW 1B: Analyze with high margins (should detect risk)
     */
    public function test_workflow_manual_high_margin_analysis()
    {
        $payload = [
            'client_id' => $this->client->id,
            'tanggal' => now()->toDateString(),
            'items' => [
                [
                    'material_id' => $this->materials[0]->id,
                    'jumlah' => 100,
                    'harga_asli' => $this->materials[0]->harga,
                    'persentase_margin' => 50, // High margin
                ]
            ]
        ];

        $response = $this->postJson(route('penawaran.analyze-manual'), $payload);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Verify risk level is calculated
        $this->assertNotNull($response->json('risk_level'));
    }

    // ========================
    // WORKFLOW 2: BOQ IMPORT
    // ========================

    /**
     * WORKFLOW 2: User imports penawaran via BoQ file
     * 1. Preview BoQ file
     * 2. Review items
     * 3. Store from BoQ
     * 4. Verify creation
     */
    public function test_workflow_boq_import_complete_flow()
    {
        // Step 1: Store from BoQ (simulating file import)
        $boqPayload = [
            'client_id' => $this->client->id,
            'tanggal_penawaran' => now()->toDateString(),
            'items' => [
                [
                    'kode' => 'MAT001',
                    'nama' => 'Material from BoQ 1',
                    'satuan' => 'pcs',
                    'jumlah' => 20,
                    'harga_asli' => 100000,
                    'persentase_margin' => 12,
                ],
                [
                    'kode' => 'MAT002',
                    'nama' => 'Material from BoQ 2',
                    'satuan' => 'meter',
                    'jumlah' => 50,
                    'harga_asli' => 150000,
                    'persentase_margin' => 18,
                ],
                [
                    'kode' => 'MAT003',
                    'nama' => 'Material from BoQ 3',
                    'satuan' => 'kg',
                    'jumlah' => 30,
                    'harga_asli' => 75000,
                    'persentase_margin' => 15,
                ]
            ]
        ];

        $response = $this->postJson(route('penawaran.storeFromBoq'), $boqPayload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Penawaran berhasil dibuat dengan status DRAFT'
        ]);

        // Step 2: Verify penawaran created
        $penawaran = Penawaran::where('client_id', $this->client->id)->first();
        $this->assertNotNull($penawaran);
        $this->assertEquals('draft', $penawaran->status);

        // Step 3: Verify all items created
        $this->assertEquals(3, $penawaran->items()->count());

        // Step 4: Verify item details
        $items = $penawaran->items()->get();
        $this->assertEquals('Material from BoQ 1', $items[0]->nama);
        $this->assertEquals(20, $items[0]->jumlah);
    }

    /**
     * WORKFLOW 2B: Calculate totals from BoQ correctly
     */
    public function test_workflow_boq_totals_calculation()
    {
        $boqPayload = [
            'client_id' => $this->client->id,
            'tanggal_penawaran' => now()->toDateString(),
            'items' => [
                [
                    'kode' => 'MAT001',
                    'nama' => 'Material 1',
                    'satuan' => 'pcs',
                    'jumlah' => 10,
                    'harga_asli' => 100000,
                    'persentase_margin' => 20,
                ]
            ]
        ];

        $response = $this->postJson(route('penawaran.storeFromBoq'), $boqPayload);

        $response->assertStatus(200);

        $penawaran = Penawaran::where('client_id', $this->client->id)->first();

        // Expected calculations:
        // Total cost: 100000 * 10 = 1000000
        // Margin: 100000 * 10 * 0.2 = 200000
        // Subtotal: 1200000
        // PPN (11%): 132000
        // Grand Total: 1332000

        $this->assertEquals(1000000, $penawaran->total_biaya);
        $this->assertEquals(200000, $penawaran->total_margin);
        $this->assertEquals(132000, $penawaran->ppn);
        $this->assertEquals(1332000, $penawaran->grand_total_with_ppn);
    }

    // ========================
    // WORKFLOW 3: EDIT & ANALYZE
    // ========================

    /**
     * WORKFLOW 3: User creates penawaran, then analyzes again with different margins
     */
    public function test_workflow_create_analyze_update_analyze()
    {
        // Step 1: Create initial penawaran
        $storePayload = [
            'no_penawaran' => Penawaran::generateNoPenawaran(),
            'client_id' => $this->client->id,
            'tanggal' => now()->toDateString(),
            'status' => 'draft',
            'items' => [
                [
                    'material_id' => $this->materials[0]->id,
                    'jumlah' => 5,
                    'harga_asli' => $this->materials[0]->harga,
                    'persentase_margin' => 10,
                ]
            ]
        ];

        $storeResponse = $this->postJson(route('penawaran.store'), $storePayload);
        $this->assertDatabaseHas('penawaran', ['client_id' => $this->client->id]);

        // Step 2: Analyze with higher margin (different scenario)
        $analysisPayload = [
            'client_id' => $this->client->id,
            'tanggal' => now()->toDateString(),
            'items' => [
                [
                    'material_id' => $this->materials[0]->id,
                    'jumlah' => 10,
                    'harga_asli' => $this->materials[0]->harga,
                    'persentase_margin' => 50, // Different margin
                ]
            ]
        ];

        $analysisResponse = $this->postJson(route('penawaran.analyze-manual'), $analysisPayload);
        $analysisResponse->assertStatus(200);
        $analysisResponse->assertJson(['success' => true]);
    }

    // ========================
    // WORKFLOW 4: ERROR HANDLING
    // ========================

    /**
     * WORKFLOW 4: Graceful error when invalid material is used
     */
    public function test_workflow_error_handling_invalid_material()
    {
        $payload = [
            'client_id' => $this->client->id,
            'tanggal' => now()->toDateString(),
            'items' => [
                [
                    'material_id' => 9999, // Non-existent material
                    'jumlah' => 5,
                    'harga_asli' => 100000,
                    'persentase_margin' => 10,
                ]
            ]
        ];

        $response = $this->postJson(route('penawaran.analyze-manual'), $payload);

        $response->assertStatus(422); // Validation error
    }

    /**
     * WORKFLOW 4B: Graceful error when invalid client is used
     */
    public function test_workflow_error_handling_invalid_client()
    {
        $payload = [
            'client_id' => 9999, // Non-existent client
            'tanggal' => now()->toDateString(),
            'items' => [
                [
                    'material_id' => $this->materials[0]->id,
                    'jumlah' => 5,
                    'harga_asli' => 100000,
                    'persentase_margin' => 10,
                ]
            ]
        ];

        $response = $this->postJson(route('penawaran.analyze-manual'), $payload);

        $response->assertStatus(422); // Validation error
    }

    // ========================
    // WORKFLOW 5: DATA INTEGRITY
    // ========================

    /**
     * WORKFLOW 5: Verify data integrity across multiple operations
     */
    public function test_workflow_data_integrity_multiple_penawaran()
    {
        // Create multiple penawaran for same client
        $penawaran1 = Penawaran::create([
            'no_penawaran' => Penawaran::generateNoPenawaran(),
            'client_id' => $this->client->id,
            'tanggal' => now()->toDateString(),
            'status' => 'draft',
            'total_biaya' => 1000000,
            'total_margin' => 200000,
            'ppn' => 132000,
            'grand_total_with_ppn' => 1332000,
        ]);

        $penawaran2 = Penawaran::create([
            'no_penawaran' => Penawaran::generateNoPenawaran(),
            'client_id' => $this->client->id,
            'tanggal' => now()->addDay()->toDateString(),
            'status' => 'draft',
            'total_biaya' => 2000000,
            'total_margin' => 400000,
            'ppn' => 264000,
            'grand_total_with_ppn' => 2664000,
        ]);

        // Verify both exist
        $penawaran = Penawaran::where('client_id', $this->client->id)->get();
        $this->assertEquals(2, $penawaran->count());

        // Verify totals are different
        $this->assertNotEquals(
            $penawaran1->grand_total_with_ppn,
            $penawaran2->grand_total_with_ppn
        );
    }

    /**
     * WORKFLOW 5B: Verify unique penawaran numbers
     */
    public function test_workflow_unique_penawaran_numbers()
    {
        $numbers = [];

        for ($i = 0; $i < 5; $i++) {
            $no = Penawaran::generateNoPenawaran();
            $this->assertNotContains($no, $numbers, "Penawaran number $no is not unique");
            $numbers[] = $no;
        }

        // All should be unique
        $this->assertEquals(5, count(array_unique($numbers)));
    }

    // ========================
    // WORKFLOW 6: PERFORMANCE
    // ========================

    /**
     * WORKFLOW 6: Analysis should complete in reasonable time
     */
    public function test_workflow_analysis_performance()
    {
        $payload = [
            'client_id' => $this->client->id,
            'tanggal' => now()->toDateString(),
            'items' => [
                [
                    'material_id' => $this->materials[0]->id,
                    'jumlah' => 5,
                    'harga_asli' => $this->materials[0]->harga,
                    'persentase_margin' => 10,
                ]
            ]
        ];

        $startTime = microtime(true);
        $response = $this->postJson(route('penawaran.analyze-manual'), $payload);
        $executionTime = microtime(true) - $startTime;

        $response->assertStatus(200);
        
        // Should complete within 1 second (1000ms)
        $this->assertLessThan(1, $executionTime, "Analysis took too long: {$executionTime}s");
    }
}
