<?php

namespace Tests\Feature;

use App\Models\Destinasi;
use App\Services\SawService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pengujian fitur alur rekomendasi SAW, katalog destinasi, dan panel admin.
 */
class AlurRekomendasiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_beranda_tampil_dengan_form_profil(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Dapatkan Rekomendasi')
            ->assertSee('Snorkeling');
    }

    public function test_data_awal_lengkap(): void
    {
        $this->assertSame(14, Destinasi::count());
        $this->assertDatabaseCount('bobot_segmen', 10);
    }

    public function test_form_kosong_ditolak(): void
    {
        $this->post('/rekomendasi', [])
            ->assertSessionHasErrors(['minat', 'anggaran', 'jarak']);
    }

    public function test_nilai_tidak_sah_ditolak(): void
    {
        $this->post('/rekomendasi', [
            'minat' => 'Berenang',
            'anggaran' => 'Gratis',
            'jarak' => 'Sangat Jauh',
        ])->assertSessionHasErrors(['minat', 'anggaran', 'jarak']);
    }

    public function test_profil_snorkeling_rendah_dekat_merekomendasikan_kyoko_beach(): void
    {
        $this->post('/rekomendasi', [
            'minat' => 'Snorkeling',
            'anggaran' => 'Rendah',
            'jarak' => 'Dekat',
        ])->assertRedirect('/rekomendasi');

        $this->get('/rekomendasi')
            ->assertOk()
            ->assertSee('Kyoko Beach')
            ->assertSee('0,8577');
    }

    public function test_profil_diving_tinggi_jauh_merekomendasikan_kyoko_beach_dan_tegal_mas(): void
    {
        $this->post('/rekomendasi', [
            'minat' => 'Diving',
            'anggaran' => 'Tinggi',
            'jarak' => 'Jauh',
        ]);

        $this->get('/rekomendasi')
            ->assertOk()
            ->assertSee('Pulau Tegal Mas');
    }

    public function test_detail_destinasi_menampilkan_rincian_saw(): void
    {
        $this->post('/rekomendasi', [
            'minat' => 'Snorkeling',
            'anggaran' => 'Rendah',
            'jarak' => 'Dekat',
        ]);

        $this->get('/destinasi/A1')
            ->assertOk()
            ->assertSee('Rincian perhitungan SAW')
            ->assertSee('Pulau Pahawang');
    }

    public function test_katalog_destinasi_tampil_dengan_info_umum(): void
    {
        $this->get('/destinasi')
            ->assertOk()
            ->assertSee('Destinasi Wisata Bahari Pesawaran')
            ->assertSee('Pulau Pahawang')
            ->assertSee('Kyoko Beach')
            ->assertDontSee('Nilai Preferensi (Vi)');
    }

    public function test_tamu_diarahkan_ke_login(): void
    {
        $this->get('/admin/destinasi')->assertRedirect('/admin/login');
    }

    public function test_admin_bisa_login_dan_membuka_halaman(): void
    {
        $this->post('/admin/login', [
            'email' => 'admin@spk.test',
            'password' => 'admin12345',
        ])->assertRedirect('/admin/destinasi');

        $this->get('/admin/destinasi')->assertOk()->assertSee('Data Destinasi');
        $this->get('/admin/bobot')->assertOk()->assertSee('Bobot Kriteria per Segmen');
        $this->get('/admin/aturan')->assertOk()->assertSee('Aturan Kelayakan');
        $this->get('/admin/analisis')->assertOk()->assertSee('Analisis Sebaran Rekomendasi');
    }

    public function test_total_bobot_selalu_satu(): void
    {
        $saw = app(SawService::class);

        foreach (SawService::MINAT as $m) {
            foreach (SawService::ANGGARAN as $a) {
                foreach (SawService::JARAK as $j) {
                    $w = $saw->bobotProfil($m, $a, $j);
                    $this->assertEqualsWithDelta(1.0, array_sum($w), 1e-9,
                        "Bobot profil {$m}+{$a}+{$j} tidak berjumlah 1,0000");
                }
            }
        }
    }

    public function test_nilai_vi_cocok_dengan_perhitungan_manual(): void
    {
        $hasil = app(SawService::class)->hitung('Snorkeling', 'Rendah', 'Dekat');

        $this->assertSame('A11', $hasil['hasil'][0]['kode']);
        $this->assertEqualsWithDelta(0.8577, $hasil['hasil'][0]['v'], 0.001);
        $this->assertCount(14, $hasil['hasil'], 'Harus terdapat 14 destinasi yang dinilai');
    }
}
