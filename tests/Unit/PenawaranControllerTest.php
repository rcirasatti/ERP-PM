<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Penawaran;
use App\Models\Client;
use App\Models\Material;
use App\Models\Inventory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PenawaranControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $client;
    protected $material;
    protected $inventory;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user
        $this->user = User::factory()->create();

        // Create test client
        $this->client = Client::create([
            'nama' => 'Test Client',
            'email' => 'client@test.com',
            'telepon' => '081234567890',
            'alamat' => 'Jl. Test No. 1',
            'kontak' => 'John Doe',
        ]);

        // Create test material with inventory
        $this->material = Material::create([
            'nama' => 'Test Material',
            'type' => 'BARANG',
            'satuan' => 'pcs',
            'harga' => 100000,
            'deskripsi' => 'Test Description',
            'kode' => 'MAT001',
        ]);

        // Create inventory
        $this->inventory = Inventory::create([
            'material_id' => $this->material->id,
            'stok' => 100,
            'minimal_stok' => 10,
        ]);

        $this->actingAs($this->user);
    }

    // ================================
    // ANALYZE MANUAL TESTS
    // ================================

    /**
     * Test analyzing manual penawaran successfully
     */
    public function test_analyze_manual_penawaran_success()
    {
        $payload = [
            'client_id' => $this->client->id,
            'tanggal' => now()->toDateString(),
            'items' => [
                [
                    'material_id' => $this->material->id,
                    'jumlah' => 5,
                    'harga_asli' => 100000,
                    'persentase_margin' => 10,
                ]
            ]
        ];

        $response = $this->postJson(route('penawaran.analyze-manual'), $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Analisis berhasil',
        ]);
        $response->assertJsonStructure([
            'risk_level',
            'recommendation',
            'predictions' => [
                'lr',
                'ma'
            ],
            'data' => [
                'grand_total',
                'total_biaya',
                'total_margin',
                'ppn',
                'item_count'
            ]
        ]);
    }

    /**
     * Test analyze manual with multiple items
     */
    public function test_analyze_manual_multiple_items()
    {
        // Create additional material
        $material2 = Material::create([
            'nama' => 'Material 2',
            'type' => 'BARANG',
            'satuan' => 'meter',
            'harga' => 50000,
            'kode' => 'MAT002',
        ]);

        Inventory::create([
            'material_id' => $material2->id,
            'stok' => 200,
            'minimal_stok' => 20,
        ]);

        $payload = [
            'client_id' => $this->client->id,
            'tanggal' => now()->toDateString(),
            'items' => [
                [
                    'material_id' => $this->material->id,
                    'jumlah' => 5,
                    'harga_asli' => 100000,
                    'persentase_margin' => 10,
                ],
                [
                    'material_id' => $material2->id,
                    'jumlah' => 10,
                    'harga_asli' => 50000,
                    'persentase_margin' => 15,
                ]
            ]
        ];

        $response = $this->postJson(route('penawaran.analyze-manual'), $payload);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        // Verify calculations
        $data = $response->json('data');
        $this->assertEquals(2, $data['item_count']);
        
        // Total biaya: (100000*5) + (50000*10) = 500000 + 500000 = 1000000
        // Total margin: (100000*5*0.1) + (50000*10*0.15) = 50000 + 75000 = 125000
        // Subtotal: 1125000, PPN: 123750, Grand Total: 1248750
        $expectedGrandTotal = 1248750;
        $this->assertEquals($expectedGrandTotal, $data['grand_total']);
    }

    /**
     * Test analyze manual fails with missing client
     */
    public function test_analyze_manual_fails_without_client()
    {
        $payload = [
            'client_id' => null,
            'tanggal' => now()->toDateString(),
            'items' => [
                [
                    'material_id' => $this->material->id,
                    'jumlah' => 5,
                    'harga_asli' => 100000,
                    'persentase_margin' => 10,
                ]
            ]
        ];

        $response = $this->postJson(route('penawaran.analyze-manual'), $payload);

        $response->assertStatus(422);
    }

    /**
     * Test analyze manual fails with no items
     */
    public function test_analyze_manual_fails_with_empty_items()
    {
        $payload = [
            'client_id' => $this->client->id,
            'tanggal' => now()->toDateString(),
            'items' => []
        ];

        $response = $this->postJson(route('penawaran.analyze-manual'), $payload);

        $response->assertStatus(422);
    }

    /**
     * Test analyze manual fails with invalid margin
     */
    public function test_analyze_manual_fails_with_invalid_margin()
    {
        $payload = [
            'client_id' => $this->client->id,
            'tanggal' => now()->toDateString(),
            'items' => [
                [
                    'material_id' => $this->material->id,
                    'jumlah' => 5,
                    'harga_asli' => 100000,
                    'persentase_margin' => 150, // Invalid: > 100
                ]
            ]
        ];

        $response = $this->postJson(route('penawaran.analyze-manual'), $payload);

        $response->assertStatus(422);
    }

    /**
     * Test analyze manual fails with invalid quantity
     */
    public function test_analyze_manual_fails_with_zero_quantity()
    {
        $payload = [
            'client_id' => $this->client->id,
            'tanggal' => now()->toDateString(),
            'items' => [
                [
                    'material_id' => $this->material->id,
                    'jumlah' => 0, // Invalid: must be > 0
                    'harga_asli' => 100000,
                    'persentase_margin' => 10,
                ]
            ]
        ];

        $response = $this->postJson(route('penawaran.analyze-manual'), $payload);

        $response->assertStatus(422);
    }

    /**
     * Test risk level calculation for low risk
     */
    public function test_risk_level_low()
    {
        $payload = [
            'client_id' => $this->client->id,
            'tanggal' => now()->toDateString(),
            'items' => [
                [
                    'material_id' => $this->material->id,
                    'jumlah' => 2,
                    'harga_asli' => 100000,
                    'persentase_margin' => 5,
                ]
            ]
        ];

        $response = $this->postJson(route('penawaran.analyze-manual'), $payload);

        $response->assertStatus(200);
        // Small grand total should result in low risk
        $riskLevel = $response->json('risk_level');
        $this->assertNotNull($riskLevel);
    }

    /**
     * Test analyze manual returns proper structure
     */
    public function test_analyze_manual_response_structure()
    {
        $payload = [
            'client_id' => $this->client->id,
            'tanggal' => now()->toDateString(),
            'items' => [
                [
                    'material_id' => $this->material->id,
                    'jumlah' => 5,
                    'harga_asli' => 100000,
                    'persentase_margin' => 10,
                ]
            ]
        ];

        $response = $this->postJson(route('penawaran.analyze-manual'), $payload);

        $response->assertJsonStructure([
            'success',
            'message',
            'risk_level',
            'recommendation',
            'predictions' => [
                'lr',
                'ma'
            ],
            'data' => [
                'grand_total',
                'total_biaya',
                'total_margin',
                'ppn',
                'item_count'
            ]
        ]);
    }

    // ================================
    // STORE FROM BOQ TESTS
    // ================================

    /**
     * Test storing penawaran from BoQ successfully
     */
    public function test_store_from_boq_success()
    {
        $payload = [
            'client_id' => $this->client->id,
            'tanggal_penawaran' => now()->toDateString(),
            'items' => [
                [
                    'kode' => 'MAT001',
                    'nama' => 'Test Material',
                    'satuan' => 'pcs',
                    'jumlah' => 5,
                    'harga_asli' => 100000,
                    'persentase_margin' => 10,
                ]
            ]
        ];

        $response = $this->postJson(route('penawaran.storeFromBoq'), $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Penawaran berhasil dibuat dengan status DRAFT'
        ]);
        
        // Verify penawaran was created
        $this->assertDatabaseHas('penawaran', [
            'client_id' => $this->client->id,
            'status' => 'draft',
            'ai_status' => 'pending'
        ]);
    }

    /**
     * Test store from BoQ creates items
     */
    public function test_store_from_boq_creates_items()
    {
        $payload = [
            'client_id' => $this->client->id,
            'tanggal_penawaran' => now()->toDateString(),
            'items' => [
                [
                    'kode' => 'MAT001',
                    'nama' => 'Material 1',
                    'satuan' => 'pcs',
                    'jumlah' => 5,
                    'harga_asli' => 100000,
                    'persentase_margin' => 10,
                ],
                [
                    'kode' => 'MAT002',
                    'nama' => 'Material 2',
                    'satuan' => 'meter',
                    'jumlah' => 10,
                    'harga_asli' => 50000,
                    'persentase_margin' => 15,
                ]
            ]
        ];

        $response = $this->postJson(route('penawaran.storeFromBoq'), $payload);

        $response->assertStatus(200);

        // Get created penawaran
        $penawaran = Penawaran::where('client_id', $this->client->id)->first();

        // Verify 2 items were created
        $this->assertEquals(2, $penawaran->items()->count());
    }

    /**
     * Test store from BoQ calculates totals correctly
     */
    public function test_store_from_boq_calculates_totals()
    {
        $payload = [
            'client_id' => $this->client->id,
            'tanggal_penawaran' => now()->toDateString(),
            'items' => [
                [
                    'kode' => 'MAT001',
                    'nama' => 'Test Material',
                    'satuan' => 'pcs',
                    'jumlah' => 10,
                    'harga_asli' => 100000,
                    'persentase_margin' => 20,
                ]
            ]
        ];

        $response = $this->postJson(route('penawaran.storeFromBoq'), $payload);

        $response->assertStatus(200);

        // Get created penawaran
        $penawaran = Penawaran::where('client_id', $this->client->id)->first();

        // Verify totals
        // Total biaya: 100000 * 10 = 1000000
        // Total margin: 100000 * 10 * 0.2 = 200000
        // PPN: (1000000 + 200000) * 0.11 = 132000
        // Grand total: 1200000 + 132000 = 1332000
        $this->assertEquals(1000000, $penawaran->total_biaya);
        $this->assertEquals(200000, $penawaran->total_margin);
        $this->assertEquals(132000, $penawaran->ppn);
        $this->assertEquals(1332000, $penawaran->grand_total_with_ppn);
    }

    /**
     * Test store from BoQ generates unique pen awaran number
     */
    public function test_store_from_boq_unique_penawaran_number()
    {
        $payload1 = [
            'client_id' => $this->client->id,
            'tanggal_penawaran' => now()->toDateString(),
            'items' => [
                [
                    'kode' => 'MAT001',
                    'nama' => 'Material',
                    'satuan' => 'pcs',
                    'jumlah' => 5,
                    'harga_asli' => 100000,
                    'persentase_margin' => 10,
                ]
            ]
        ];

        $response1 = $this->postJson(route('penawaran.storeFromBoq'), $payload1);
        $noPenawaran1 = $response1->json('data.no_penawaran');

        $response2 = $this->postJson(route('penawaran.storeFromBoq'), $payload1);
        $noPenawaran2 = $response2->json('data.no_penawaran');

        // Verify different penawaran numbers
        $this->assertNotEquals($noPenawaran1, $noPenawaran2);
    }

    /**
     * Test store from BoQ fails without client
     */
    public function test_store_from_boq_fails_without_client()
    {
        $payload = [
            'client_id' => null,
            'tanggal_penawaran' => now()->toDateString(),
            'items' => [
                [
                    'kode' => 'MAT001',
                    'nama' => 'Material',
                    'satuan' => 'pcs',
                    'jumlah' => 5,
                    'harga_asli' => 100000,
                    'persentase_margin' => 10,
                ]
            ]
        ];

        $response = $this->postJson(route('penawaran.storeFromBoq'), $payload);

        $response->assertStatus(422);
    }

    /**
     * Test store from BoQ fails with empty items
     */
    public function test_store_from_boq_fails_with_no_items()
    {
        $payload = [
            'client_id' => $this->client->id,
            'tanggal_penawaran' => now()->toDateString(),
            'items' => []
        ];

        $response = $this->postJson(route('penawaran.storeFromBoq'), $payload);

        $response->assertStatus(422);
    }

    // ================================
    // UPLOAD BOQ PREVIEW TESTS
    // ================================

    /**
     * Test upload BoQ preview with empty file
     */
    public function test_upload_boq_preview_failing_empty_file()
    {
        $response = $this->post(route('penawaran.uploadBoqPreview'), [
            'boq_file' => UploadedFile::fake()->create('empty.xlsx', 0)
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test upload BoQ preview with invalid file type
     */
    public function test_upload_boq_preview_fails_with_invalid_type()
    {
        $response = $this->post(route('penawaran.uploadBoqPreview'), [
            'boq_file' => UploadedFile::fake()->create('file.txt', 100)
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test upload BoQ preview file size limit
     */
    public function test_upload_boq_preview_fails_file_too_large()
    {
        // Create a file larger than 5MB
        $response = $this->post(route('penawaran.uploadBoqPreview'), [
            'boq_file' => UploadedFile::fake()->create('large.xlsx', 6000) // 6MB in KB
        ]);

        $response->assertStatus(422);
    }
}
