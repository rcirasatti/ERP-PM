<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Supplier;
use App\Models\Material;
use App\Models\Penawaran;
use App\Models\Proyek;
use App\Models\Tugas;
use App\Models\ProyekBudget;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ComprehensiveProjectTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $managerUser;
    protected $client;
    protected $supplier;
    protected $material;
    protected $penawaranDraft;
    protected $penawaranApproved;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users
        $this->adminUser = User::factory()->create([
            'email' => 'admin@erp.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->managerUser = User::factory()->create([
            'email' => 'manager@erp.com',
            'role' => 'manager',
        ]);

        // Create test client
        $this->client = Client::create([
            'nama' => 'Grand Indonesia',
            'email' => 'contact@grandindonesia.com',
            'telepon' => '021-23580001',
            'alamat' => 'Jl. MH Thamrin No.1, Jakarta',
            'kontak' => 'Budi Santoso',
        ]);

        // Create test supplier
        $this->supplier = Supplier::create([
            'nama' => 'PT Indocement Tunggal Prakarsa',
            'email' => 'sales@indocement.co.id',
            'telepon' => '021-2512211',
            'alamat' => 'Wisma Indocement, Jakarta',
            'kontak' => 'Anita Wijaya',
        ]);

        // Create test material
        $this->material = Material::create([
            'kode' => 'MAT-CON-01',
            'nama' => 'Semen Portland 50kg',
            'type' => 'BARANG',
            'satuan' => 'sak',
            'harga' => 65000,
            'deskripsi' => 'Semen kualitas premium untuk beton',
        ]);

        // Create a draft penawaran
        $this->penawaranDraft = Penawaran::create([
            'no_penawaran' => 'PNW/2026/001',
            'client_id' => $this->client->id,
            'tanggal' => now()->toDateString(),
            'status' => 'draft',
            'ai_status' => 'pending',
            'jenis_pekerjaan' => 'Instalasi Infrastruktur',
            'wilayah' => 'Semarang',
            'total_biaya' => 50000000,
            'total_margin' => 10000000,
            'ppn' => 6600000,
            'grand_total_with_ppn' => 66600000,
        ]);

        // Create an approved penawaran
        $this->penawaranApproved = Penawaran::create([
            'no_penawaran' => 'PNW/2026/002',
            'client_id' => $this->client->id,
            'tanggal' => now()->toDateString(),
            'status' => 'disetujui',
            'ai_status' => 'approved',
            'jenis_pekerjaan' => 'Project / Purchase Order',
            'wilayah' => 'Kendal',
            'total_biaya' => 100000000,
            'total_margin' => 20000000,
            'ppn' => 13200000,
            'grand_total_with_ppn' => 133200000,
        ]);
    }

    // ==========================================
    // 1. AUTHENTICATION TESTS
    // ==========================================

    public function test_guests_are_redirected_to_login()
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_login_validation_fails()
    {
        $response = $this->post('/login', [
            'email' => 'wrong@erp.com',
            'password' => 'wrongpass',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_success_and_logout()
    {
        $response = $this->post('/login', [
            'email' => 'admin@erp.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($this->adminUser);
    }

    // ==========================================
    // 2. USER MANAGEMENT TESTS (ADMIN ONLY)
    // ==========================================

    public function test_admin_can_manage_users()
    {
        $this->actingAs($this->adminUser);

        // 1. List
        $response = $this->get('/user');
        $response->assertStatus(200);

        // 2. Create
        $response = $this->post('/user', [
            'name' => 'New Engineer',
            'email' => 'engineer@erp.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'manager',
            'nama_depan' => 'New',
            'nama_belakang' => 'Engineer',
            'telepon' => '081234567890',
        ]);
        $response->assertRedirect('/user');
        $this->assertDatabaseHas('users', ['email' => 'engineer@erp.com']);

        // 3. Edit & Update
        $newUser = User::where('email', 'engineer@erp.com')->first();
        $response = $this->get("/user/{$newUser->id}/edit");
        $response->assertStatus(200);

        $response = $this->put("/user/{$newUser->id}", [
            'name' => 'Updated Engineer',
            'email' => 'engineer@erp.com',
            'role' => 'admin',
            'nama_depan' => 'Updated',
            'nama_belakang' => 'Engineer',
            'telepon' => '081234567890',
        ]);
        $response->assertRedirect('/user');
        $this->assertDatabaseHas('users', [
            'email' => 'engineer@erp.com',
            'name' => 'Updated Engineer',
            'role' => 'admin',
        ]);

        // 4. Delete
        $response = $this->delete("/user/{$newUser->id}");
        $response->assertRedirect('/user');
        $this->assertDatabaseMissing('users', ['email' => 'engineer@erp.com']);
    }

    public function test_manager_cannot_manage_users()
    {
        $this->actingAs($this->managerUser);

        $response = $this->get('/user');
        $response->assertStatus(403);

        $response = $this->post('/user', [
            'name' => 'Should Fail',
            'email' => 'fail@erp.com',
            'password' => 'password123',
            'role' => 'manager',
        ]);
        $response->assertStatus(403);
    }

    // ==========================================
    // 3. CLIENT & SUPPLIER MANAGEMENT TESTS
    // ==========================================

    public function test_client_crud_operations()
    {
        $this->actingAs($this->adminUser);

        // 1. List
        $response = $this->get('/client');
        $response->assertStatus(200);

        // 2. Store
        $response = $this->post('/client', [
            'nama' => 'New Client Ltd',
            'email' => 'info@newclient.com',
            'telepon' => '021-9999888',
            'alamat' => 'Jl. Kebon Jeruk No. 12',
            'kontak' => 'Rian Hidayat',
        ]);
        $response->assertRedirect('/client');
        $this->assertDatabaseHas('clients', ['nama' => 'New Client Ltd']);

        // 3. Update
        $newClient = Client::where('nama', 'New Client Ltd')->first();
        $response = $this->put("/client/{$newClient->id}", [
            'nama' => 'Updated Client Ltd',
            'email' => 'info@newclient.com',
            'telepon' => '021-9999888',
            'alamat' => 'Jl. Kebon Jeruk No. 12',
            'kontak' => 'Rian Hidayat',
        ]);
        $response->assertRedirect('/client');
        $this->assertDatabaseHas('clients', ['nama' => 'Updated Client Ltd']);

        // 4. Delete
        $response = $this->delete("/client/{$newClient->id}");
        $response->assertRedirect('/client');
        $this->assertDatabaseMissing('clients', ['nama' => 'Updated Client Ltd']);
    }

    public function test_supplier_crud_operations()
    {
        $this->actingAs($this->adminUser);

        // 1. List
        $response = $this->get('/supplier');
        $response->assertStatus(200);

        // 2. Store
        $response = $this->post('/supplier', [
            'nama' => 'Semen Sentosa Supplier',
            'email' => 'sentosa@cement.com',
            'telepon' => '024-555123',
            'alamat' => 'Jl. Kaligawe Km.5, Semarang',
            'kontak' => 'Hendra Wijaya',
        ]);
        $response->assertRedirect('/supplier');
        $this->assertDatabaseHas('suppliers', ['nama' => 'Semen Sentosa Supplier']);

        // 3. Update
        $newSupplier = Supplier::where('nama', 'Semen Sentosa Supplier')->first();
        $response = $this->put("/supplier/{$newSupplier->id}", [
            'nama' => 'Semen Sentosa Supplier Utama',
            'email' => 'sentosa@cement.com',
            'telepon' => '024-555123',
            'alamat' => 'Jl. Kaligawe Km.5, Semarang',
            'kontak' => 'Hendra Wijaya',
        ]);
        $response->assertRedirect('/supplier');
        $this->assertDatabaseHas('suppliers', ['nama' => 'Semen Sentosa Supplier Utama']);

        // 4. Delete
        $response = $this->delete("/supplier/{$newSupplier->id}");
        $response->assertRedirect('/supplier');
        $this->assertDatabaseMissing('suppliers', ['nama' => 'Semen Sentosa Supplier Utama']);
    }

    // ==========================================
    // 4. MATERIAL MANAGEMENT TESTS
    // ==========================================

    public function test_material_crud_and_template_download()
    {
        $this->actingAs($this->adminUser);

        // 1. List
        $response = $this->get('/material');
        $response->assertStatus(200);

        // 2. Template
        $response = $this->get(route('material.export-template'));
        $response->assertStatus(200);

        // 3. Store
        $response = $this->post('/material', [
            'kode' => 'MAT-WD-01',
            'nama' => 'Kayu Jati Grade A',
            'type' => 'BARANG',
            'satuan' => 'm3',
            'harga' => 4500000,
            'deskripsi' => 'Kayu jati perhutani kering oven',
            'supplier_id' => $this->supplier->id,
        ]);
        $response->assertRedirect('/material');
        $this->assertDatabaseHas('materials', ['kode' => 'MAT-WD-01']);

        // 4. Update
        $newMaterial = Material::where('kode', 'MAT-WD-01')->first();
        $response = $this->put("/material/{$newMaterial->id}", [
            'kode' => 'MAT-WD-01',
            'nama' => 'Kayu Jati Grade A Premium',
            'type' => 'BARANG',
            'satuan' => 'm3',
            'harga' => 4800000,
            'deskripsi' => 'Kayu jati premium oven kering',
            'supplier_id' => $this->supplier->id,
        ]);
        $response->assertRedirect('/material');
        $this->assertDatabaseHas('materials', ['nama' => 'Kayu Jati Grade A Premium']);

        // 5. Delete
        $response = $this->delete("/material/{$newMaterial->id}");
        $response->assertRedirect('/material');
        $this->assertDatabaseMissing('materials', ['kode' => 'MAT-WD-01']);
    }

    // ==========================================
    // 5. PROJECT & TASK MANAGEMENT TESTS
    // ==========================================

    public function test_project_and_task_lifecycle()
    {
        $this->actingAs($this->managerUser);

        // 1. Create project from approved penawaran
        $response = $this->post('/proyek', [
            'penawaran_id' => $this->penawaranApproved->id,
            'nama' => 'Proyek Kendal Smart Office',
            'deskripsi' => 'Instalasi jaringan optik office Kendal',
            'lokasi' => 'Kendal Industrial Park',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
        ]);

        $proyek = Proyek::where('penawaran_id', $this->penawaranApproved->id)->first();
        $this->assertNotNull($proyek);
        $response->assertRedirect(route('proyek.show', $proyek->id));

        // Verify budget automatically created
        $this->assertDatabaseHas('proyek_budget', [
            'proyek_id' => $proyek->id,
            'jumlah_rencana' => 133200000,
        ]);

        // 2. Project List & Search
        $response = $this->get('/proyek');
        $response->assertStatus(200);

        $response = $this->get(route('proyek.search', ['q' => 'Kendal']));
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'html', 'pagination']);

        // 3. Nested Tasks Management
        // Create Task
        $response = $this->post(route('proyek.tugas.store', $proyek->id), [
            'nama' => 'Survei Lapangan Lokasi',
        ]);
        $response->assertRedirect(route('proyek.show', $proyek->id));

        $task = Tugas::where('proyek_id', $proyek->id)->first();
        $this->assertNotNull($task);

        // Update task status
        $response = $this->postJson(route('tugas.updateStatus', [$proyek->id, $task->id]), [
            'selesai' => true,
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('tugas', ['id' => $task->id, 'selesai' => true]);

        // Delete task
        $response = $this->delete(route('proyek.tugas.destroy', [$proyek->id, $task->id]));
        $response->assertRedirect(route('proyek.show', $proyek->id));
        $this->assertDatabaseMissing('tugas', ['id' => $task->id]);
    }

    // ==========================================
    // 6. FINANCE & BUDGETS
    // ==========================================

    public function test_finance_budget_views()
    {
        $this->actingAs($this->managerUser);

        // Create a mock project with budget
        $proyek = Proyek::create([
            'penawaran_id' => $this->penawaranApproved->id,
            'client_id' => $this->client->id,
            'nama' => 'Dummy Project',
            'lokasi' => 'Kendal',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            'status' => 'baru',
            'persentase_progres' => 0,
        ]);

        $budget = ProyekBudget::create([
            'proyek_id' => $proyek->id,
            'jumlah_rencana' => 100000000,
            'jumlah_realisasi' => 15000000,
        ]);

        // Budget Dashboard List
        $response = $this->get('/finance/budget');
        $response->assertStatus(200);
        $response->assertViewHas('budgets');

        // Budget Detail View
        $response = $this->get("/finance/budget/{$budget->id}");
        $response->assertStatus(200);
        $response->assertViewHas('budget');
    }

    // ==========================================
    // 7. DSS COST OVERRUN & DECISIONS
    // ==========================================

    public function test_dss_analysis_and_decisions()
    {
        $this->actingAs($this->managerUser);

        // 1. Analyze Penawaran (mocking python result)
        $response = $this->postJson(route('dss.analyze'), [
            'penawaran_id' => $this->penawaranDraft->id,
            'grand_total' => 66600000,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'penawaran_id',
                'no_penawaran',
                'grand_total',
                'margin_status',
                'risk_level',
                'predictions',
            ]
        ]);

        // Verify state is analyzed
        $this->penawaranDraft->refresh();
        $this->assertEquals('analyzed', $this->penawaranDraft->ai_status);

        // 2. Approve Penawaran (Manager Decision)
        // Set all items with valid material first to bypass itemsWithoutMaterial validation
        $item = DB::table('item_penawaran')->insertGetId([
            'penawaran_id' => $this->penawaranDraft->id,
            'material_id' => $this->material->id,
            'nama' => 'Mock Item',
            'satuan' => 'pcs',
            'jumlah' => 1,
            'harga_asli' => 10000,
            'persentase_margin' => 10,
            'harga_jual' => 11000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->postJson(route('dss.approve'), [
            'penawaran_id' => $this->penawaranDraft->id,
            'user_decision' => 'approve',
            'notes' => 'Looking very cost-effective',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->penawaranDraft->refresh();
        $this->assertEquals('disetujui', $this->penawaranDraft->status);
        $this->assertEquals('approved', $this->penawaranDraft->ai_status);
    }

    // ==========================================
    // 8. DOCUMENT GENERATION TESTS
    // ==========================================

    public function test_document_exports()
    {
        $this->actingAs($this->adminUser);

        $penawaran = $this->penawaranApproved;

        // 1. Invoice GSB
        $response = $this->get(route('penawaran.document.invoice-gsb', $penawaran->id));
        $response->assertStatus(200);
        $response->assertViewHas('penawaran');

        // 2. Invoice Ritel
        $response = $this->get(route('penawaran.document.invoice-ritel', $penawaran->id));
        $response->assertStatus(200);

        // 3. Invoice Corporate
        $response = $this->get(route('penawaran.document.invoice-corporate', $penawaran->id));
        $response->assertStatus(200);

        // 4. Surat Jalan
        $response = $this->get(route('penawaran.document.surat-jalan', $penawaran->id));
        $response->assertStatus(200);

        // 5. BAS (Berita Acara Survey)
        $response = $this->get(route('penawaran.document.bas', $penawaran->id));
        $response->assertStatus(200);

        // 6. BAST (Berita Acara Serah Terima)
        $response = $this->get(route('penawaran.document.bast', $penawaran->id));
        $response->assertStatus(200);
    }

    // ==========================================
    // 9. DASHBOARD & PROFILE & FALLBACK TESTS
    // ==========================================

    public function test_dashboard_page_loads_with_statistics()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertViewHasAll([
            'totalProyek',
            'proyekInProgress',
            'proyekCompleted',
            'totalRevenue',
            'recentProyek',
            'proyekByStatus',
            'upcomingTugas',
            'recentPengeluaran',
            'totalPengeluaran',
            'lowStockItems',
            'pendingPenawaran',
            'onTimePercentage',
            'totalClients',
            'avgProjectDuration',
            'budgetVariance',
        ]);
    }

    public function test_profile_view_edit_and_update()
    {
        $this->actingAs($this->adminUser);

        // 1. Show profile page
        $response = $this->get(route('profile.show'));
        $response->assertStatus(200);
        $response->assertViewHas('profil');

        // 2. Edit profile page
        $response = $this->get(route('profile.edit'));
        $response->assertStatus(200);
        $response->assertViewHasAll(['profil', 'user']);

        // 3. Update profile fields
        $response = $this->put(route('profile.update'), [
            'nama_depan' => 'Supratman',
            'nama_belakang' => 'Kurniawan',
            'email' => 'supratman@erp.com',
            'telepon' => '081122334455',
        ]);

        $response->assertRedirect(route('profile.show'));
        $this->assertDatabaseHas('users', [
            'id' => $this->adminUser->id,
            'name' => 'Supratman Kurniawan',
            'email' => 'supratman@erp.com',
        ]);
        $this->assertDatabaseHas('profil', [
            'user_id' => $this->adminUser->id,
            'nama_depan' => 'Supratman',
            'nama_belakang' => 'Kurniawan',
            'telepon' => '081122334455',
        ]);
    }
}

