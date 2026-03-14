<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class KasirTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed some data
        DB::table('barang')->insert([
            ['id_barang' => 'B001', 'nama' => 'Buku Laravel', 'harga' => 50000, 'id_kategori' => 1],
            ['id_barang' => 'B002', 'nama' => 'Buku Vue JS', 'harga' => 45000, 'id_kategori' => 1],
            ['id_barang' => 'A001', 'nama' => 'Alat Tulis', 'harga' => 5000, 'id_kategori' => 2],
        ]);

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    /** @test */
    public function it_can_search_barang_for_autocomplete()
    {
        $response = $this->getJson(route('kasir.search', ['query' => 'Buku']));

        $response->assertStatus(200);
        $response->assertJsonCount(2); // B001 and B002
        $response->assertJsonFragment(['id_barang' => 'B001']);
        $response->assertJsonFragment(['id_barang' => 'B002']);
    }

    /** @test */
    public function it_can_search_by_code_directly()
    {
        $response = $this->getJson(route('kasir.cari', ['kode' => 'B001']));

        $response->assertStatus(200);
        $response->assertJsonPath('data.nama', 'Buku Laravel');
    }

    /** @test */
    public function it_returns_error_if_barang_not_found()
    {
        $response = $this->getJson(route('kasir.cari', ['kode' => 'NONE']));

        $response->assertStatus(404);
        $response->assertJsonPath('status', 'error');
    }
}
