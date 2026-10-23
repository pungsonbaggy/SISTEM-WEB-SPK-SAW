<?php

namespace Tests\Feature;

use App\Models\Destinasi;
use App\Services\SawService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * ==========================================================================
 *  Feature test: memastikan alur utama aplikasi berjalan.
 * ==========================================================================
 *
 * Jalankan dengan:  php artisan test
 *
 * Setiap fungsi yang diawali `test_` adalah satu skenario pengujian.
 * Nama fungsinya sengaja dibuat seperti kalimat agar mudah dibaca
 * saat ditunjukkan kepada penguji.
 */
class AlurRekomendasiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(); // isi 15 destinasi, 10 bobot, skor C6, aturan, admin
    }

    /** Halaman beranda tampil dan memuat form profil. */
    public function test_beranda_tampil_dengan_form_profil(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Dapatkan Rekomendasi')
            ->assertSee('Snorkeling');
    }

    /** Data hasil seeder lengkap: 15 destinasi dan 10 baris bobot. */
    public function test_data_awal_lengkap(): void
    {
        $this->assertSame(15, Destinasi::count());
        $this->assertDatabaseCount('bobot_segmen', 10);
        $this->assertDatabaseCount('skor_kesesuaian_aktivitas', 60); // 15 x 4 minat
    }

    /** Form kosong ditolak dengan pesan validasi (skenario uji 1). */
    public function test_form_kosong_ditolak(): void
    {
        $this->post('/rekomendasi', [])
            ->assertSessionHasErrors(['minat', 'anggaran', 'jarak']);
    }

    /** Nilai di luar pilihan ditolak (skenario uji 2). */
    public function test_nilai_tidak_sah_ditolak(): void
    {
        $this->post('/rekomendasi', [
            'minat' => 'Berenang',
            'anggaran' => 'Gratis',
            'jarak' => 'Sangat Jauh',
        ])->assertSessionHasErrors(['minat', 'anggaran', 'jarak']);
    }

    /** Profil snorkeling menghasilkan Pulau Pahawang di peringkat pertama. */
    public function test_profil_snorkeling_merekomendasikan_pahawang(): void
    {
        $this->post('/rekomendasi', [
            'minat' => 'Snorkeling',
            'anggaran' => 'Sedang',
            'jarak' => 'Sedang',
        ])->assertRedirect('/rekomendasi');

        $this->get('/rekomendasi')
            ->assertOk()
            ->assertSee('Pulau Pahawang')
            ->assertSee('0,7445');
    }

    /** Profil diving menghasilkan Pulau Tegal Mas, bukan Pahawang (bukti personalisasi). */
    public function test_profil_diving_merekomendasikan_tegal_mas(): void
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

    /** Halaman detail menampilkan rincian perhitungan. */
    public function test_detail_destinasi_menampilkan_rincian_saw(): void
    {
        $this->post('/rekomendasi', [
            'minat' => 'Snorkeling',
            'anggaran' => 'Sedang',
            'jarak' => 'Sedang',
        ]);

        $this->get('/destinasi/A1')
            ->assertOk()
            ->assertSee('Rincian perhitungan SAW')
            ->assertSee('Pulau Pahawang');
    }

    /** Destinasi yang tidak lolos penyaringan tetap bisa dibuka detailnya. */
    public function test_detail_destinasi_tersaring_tetap_dapat_dibuka(): void
    {
        $this->get('/destinasi/A4')->assertOk()->assertSee('Pantai Mutun Town');
    }

    /** Tamu yang membuka halaman admin diarahkan ke login (NF-06). */
    public function test_tamu_diarahkan_ke_login(): void
    {
        $this->get('/admin/destinasi')->assertRedirect('/admin/login');
    }

    /** Admin bisa login lalu membuka halaman pengelolaan. */
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

    /** Password salah tidak boleh masuk. */
    public function test_password_salah_ditolak(): void
    {
        $this->post('/admin/login', [
            'email' => 'admin@spk.test',
            'password' => 'salah',
        ])->assertSessionHasErrors('email');
    }

    /** Total bobot 36 kombinasi profil selalu tepat 1,0000 (skenario uji 6). */
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

    /** Nilai Vi destinasi pertama cocok dengan Tabel 3.15 skripsi. */
    public function test_nilai_vi_cocok_dengan_perhitungan_manual(): void
    {
        $hasil = app(SawService::class)->hitung('Snorkeling', 'Sedang', 'Sedang');

        $this->assertSame('A1', $hasil['hasil'][0]['kode']);
        $this->assertEqualsWithDelta(0.7445, $hasil['hasil'][0]['v'], 0.001);
        $this->assertCount(12, $hasil['hasil'], 'Minat snorkeling harus menyisakan 12 kandidat');
        $this->assertSame(['A4', 'A10', 'A12'], $hasil['dikeluarkan']);
    }
}
