<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Pengeluaran;
use App\Models\Proyek;
use App\Models\Client;
use App\Models\Penawaran;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PengeluaranControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $proyek;
    protected $pengeluaran;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user
        $this->user = User::factory()->create();

        // Create client
        $client = Client::create([
            'nama' => 'Test Client',
            'email' => 'client@test.com',
            'telepon' => '081234567890',
            'alamat' => 'Jl. Test No. 1',
        ]);

        // Create penawaran
        $penawaran = Penawaran::create([
            'nama' => 'Test Penawaran',
            'deskripsi' => 'Test Description',
            'harga' => 1000000,
            'tanggal_terima' => now(),
            'status' => 'approved',
        ]);

        // Create proyek
        $this->proyek = Proyek::create([
            'nama' => 'Test Proyek',
            'lokasi' => 'Jl. Test',
            'client_id' => $client->id,
            'penawaran_id' => $penawaran->id,
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addMonth(),
            'status' => 'baru',
            'persentase_progres' => 0,
        ]);

        // Create pengeluaran
        $this->pengeluaran = Pengeluaran::create([
            'proyek_id' => $this->proyek->id,
            'tanggal' => now(),
            'kategori' => 'material',
            'deskripsi' => 'Test Pengeluaran',
            'jumlah' => 500000,
            'dibuat_oleh' => $this->user->id,
        ]);

        $this->actingAs($this->user);
    }

    /**
     * Test index method - display all pengeluaran
     */
    public function test_pengeluaran_index_page_loads()
    {
        $response = $this->get('/pengeluaran');

        $response->assertStatus(200);
        $response->assertViewHas('pengeluaran');
    }

    /**
     * Test create method - show form to create new pengeluaran
     */
    public function test_pengeluaran_create_page_loads()
    {
        $response = $this->get('/pengeluaran/create');

        $response->assertStatus(200);
        $response->assertViewHas('projects');
    }

    /**
     * Test store method - create new pengeluaran
     */
    public function test_pengeluaran_can_be_stored()
    {
        $data = [
            'proyek_id' => $this->proyek->id,
            'tanggal' => now()->format('Y-m-d'),
            'kategori' => 'gaji',
            'deskripsi' => 'Pembayaran gaji karyawan bulan ini',
            'jumlah' => 2000000,
        ];

        $response = $this->post('/pengeluaran', $data);

        $response->assertRedirect('/pengeluaran');
        $this->assertDatabaseHas('pengeluaran', [
            'proyek_id' => $this->proyek->id,
            'kategori' => 'gaji',
            'jumlah' => 2000000,
        ]);
    }

    /**
     * Test edit method - show form to edit pengeluaran
     */
    public function test_pengeluaran_edit_page_loads()
    {
        $response = $this->get("/pengeluaran/{$this->pengeluaran->id}/edit");

        $response->assertStatus(200);
        $response->assertViewHas('pengeluaran', $this->pengeluaran);
        $response->assertViewHas('projects');
    }

    /**
     * Test update method - update existing pengeluaran
     */
    public function test_pengeluaran_can_be_updated()
    {
        $data = [
            'proyek_id' => $this->proyek->id,
            'tanggal' => now()->format('Y-m-d'),
            'kategori' => 'bahan_bakar',
            'deskripsi' => 'Pembelian bahan bakar kendaraan',
            'jumlah' => 750000,
        ];

        $response = $this->put("/pengeluaran/{$this->pengeluaran->id}", $data);

        $response->assertRedirect('/pengeluaran');
        $this->assertDatabaseHas('pengeluaran', [
            'id' => $this->pengeluaran->id,
            'kategori' => 'bahan_bakar',
            'jumlah' => 750000,
        ]);
    }

    /**
     * Test destroy method - delete pengeluaran
     */
    public function test_pengeluaran_can_be_deleted()
    {
        $pengeluaranId = $this->pengeluaran->id;

        $response = $this->delete("/pengeluaran/{$pengeluaranId}");

        $response->assertRedirect('/pengeluaran');
        $this->assertDatabaseMissing('pengeluaran', ['id' => $pengeluaranId]);
    }

    /**
     * Test validation - missing required fields
     */
    public function test_pengeluaran_validation_fails_with_missing_fields()
    {
        $data = [
            'kategori' => 'material',
            // Missing required fields
        ];

        $response = $this->post('/pengeluaran', $data);

        $response->assertSessionHasErrors(['proyek_id', 'tanggal', 'deskripsi', 'jumlah']);
    }

    /**
     * Test validation - invalid kategori
     */
    public function test_pengeluaran_validation_fails_with_invalid_kategori()
    {
        $data = [
            'proyek_id' => $this->proyek->id,
            'tanggal' => now()->format('Y-m-d'),
            'kategori' => 'invalid_kategori',
            'deskripsi' => 'Test',
            'jumlah' => 500000,
        ];

        $response = $this->post('/pengeluaran', $data);

        $response->assertSessionHasErrors(['kategori']);
    }

    /**
     * Test validation - negative jumlah
     */
    public function test_pengeluaran_validation_fails_with_negative_amount()
    {
        $data = [
            'proyek_id' => $this->proyek->id,
            'tanggal' => now()->format('Y-m-d'),
            'kategori' => 'material',
            'deskripsi' => 'Test',
            'jumlah' => -500000,
        ];

        $response = $this->post('/pengeluaran', $data);

        $response->assertSessionHasErrors(['jumlah']);
    }

    /**
     * Test kategori color mapping
     */
    public function test_kategori_color_mapping()
    {
        $pengeluaran = Pengeluaran::create([
            'proyek_id' => $this->proyek->id,
            'tanggal' => now(),
            'kategori' => 'gaji',
            'deskripsi' => 'Test',
            'jumlah' => 500000,
            'dibuat_oleh' => $this->user->id,
        ]);

        $this->assertEquals('bg-green-100 text-green-800', $pengeluaran->getKategoriColor());
    }

    /**
     * Test kategori label mapping
     */
    public function test_kategori_label_mapping()
    {
        $pengeluaran = Pengeluaran::create([
            'proyek_id' => $this->proyek->id,
            'tanggal' => now(),
            'kategori' => 'peralatan',
            'deskripsi' => 'Test',
            'jumlah' => 500000,
            'dibuat_oleh' => $this->user->id,
        ]);

        $this->assertEquals('Peralatan', $pengeluaran->getKategoriLabel());
    }
}
