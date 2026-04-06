<?php

namespace App\Livewire;

use App\Models\Santri;
use App\Models\WaMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class InformasiSantriBaru extends Component
{
    use WithPagination;
    public $paginate = 10;
    public $search = '';
    public $showChatModal = false;
    public $chatHistories = [];
    public $selectedSantri = null;
    public $replyMessage = '';
    protected $paginationTheme = 'tailwind';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $datas = Santri::query()
            ->select([
                'santris.id_santri',
                'santris.nama',
                'santris.desa',
                'santris.kec',
                'santris.kab',
                'santris.hp',
                'santris.lembaga',
                DB::raw('COALESCE(t.total_tanggungan, 0) as total_tanggungan'),
                DB::raw('COALESCE(r.total_bayar, 0) as total_bayar'),
                DB::raw("COALESCE(w.last_message, '-') as last_message, w.direction as direction"),
            ])
            ->leftJoin(DB::raw("
                (
                    SELECT
                        gelombang,
                        jkl,
                        ket,
                        SUM(nominal) AS total_tanggungan
                    FROM tanggungans
                    GROUP BY gelombang, jkl, ket
                ) t
            "), function ($join) {
                $join->on('t.gelombang', '=', 'santris.gel')
                    ->on('t.jkl', '=', 'santris.jkl')
                    ->on('t.ket', '=', 'santris.ket');
            })
            ->leftJoin(DB::raw("
                (
                    SELECT
                        id_santri,
                        SUM(nominal) AS total_bayar
                    FROM registrasis
                    GROUP BY id_santri
                ) r
            "), 'r.id_santri', '=', 'santris.id_santri')
            ->leftJoin(DB::raw("
                (
                    SELECT DISTINCT ON (no_hp)
                        no_hp,
                        message as last_message,
                        direction as direction
                    FROM (
                        SELECT 
                            CASE 
                                WHEN direction = 'outbound' THEN receiver
                                ELSE sender
                            END as no_hp,
                            message,
                            direction,
                            created_at
                        FROM wa_messages
                    ) x
                    ORDER BY no_hp, created_at DESC
                ) w
            "), DB::raw("
                CASE 
                    WHEN santris.hp LIKE '08%' THEN '62' || SUBSTRING(santris.hp FROM 2)
                    ELSE santris.hp
                END
            "), '=', 'w.no_hp')
            ->where('santris.ket', 'baru')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('santris.nama', 'ILIKE', "%{$this->search}%")
                        ->orWhere('santris.desa', 'ILIKE', "%{$this->search}%")
                        ->orWhere('santris.kec', 'ILIKE', "%{$this->search}%")
                        ->orWhere('santris.kab', 'ILIKE', "%{$this->search}%")
                        ->orWhere('santris.hp', 'ILIKE', "%{$this->search}%")
                        ->orWhere('santris.lembaga', 'ILIKE', "%{$this->search}%");
                });
            })
            ->orderBy('santris.created_at', 'asc')
            ->paginate($this->paginate)->onEachSide(0);

        return view('livewire.informasi-santri-baru', compact('datas'));
    }

    // ---------------------------------------------------------------- //
    // AKSI PENGIRIMAN PESAN
    // ---------------------------------------------------------------- //

    public function sendKonfirmasi($id)
    {
        $santri = Santri::find($id);
        if (!$santri) return;

        $pesan = "Assalamualaikum Wr. Wb.\n\nKami dari panitia *PSB Pondok Pesantren Darul Lughah Wal Karomah* ingin mengkonfirmasikan apakah benar ini wali dari ananda *{$santri->nama}*?\n\nTerima Kasih.";
        $this->sendWhatsAppMessage($santri->hp, $pesan, 'Konfirmasi Pendaftaran');
    }

    public function sendPembayaran($id)
    {
        $santri = Santri::find($id);
        if (!$santri) return;

        $pesan = "Halo Sdr/i *{$santri->nama}*,\n\nMohon segera menyelesaikan administrasi *Pembayaran Pendaftaran* Anda agar proses verifikasi dapat dilanjutkan.\n\nTerima Kasih.";
        $this->sendWhatsAppMessage($santri->hp, $pesan, 'Informasi Pembayaran');
    }

    public function sendRegistrasi($id)
    {
        $santri = Santri::find($id);
        if (!$santri) return;

        $pesan = "Halo Sdr/i *{$santri->nama}*,\n\nSilakan melengkapi berkas fisik dan melakukan proses *Registrasi Ulang / Daftar Ulang* sesuai jadwal yang ditunjuk.\n\nTerima Kasih.";
        $this->sendWhatsAppMessage($santri->hp, $pesan, 'Informasi Registrasi');
    }

    public function sendGroup($id)
    {
        $santri = Santri::find($id);
        if (!$santri) return;

        $pesan = "Halo Sdr/i *{$santri->nama}*,\n\nUndangan Group: Silakan bergabung ke *Grup WhatsApp Santri Baru* melalui tautan berikut:\n\n👉 [LINK_GROUP_DISINI]\n\nTerima Kasih.";
        $this->sendWhatsAppMessage($santri->hp, $pesan, 'Undangan Group');
    }

    public function sendSeragam($id)
    {
        $santri = Santri::find($id);
        if (!$santri) return;

        $pesan = "Halo Sdr/i *{$santri->nama}*,\n\nBerikut adalah informasi terkait jadwal pengukuran dan lokasi pengambilan *Seragam / Kitab*.\n\nTerima Kasih.";
        $this->sendWhatsAppMessage($santri->hp, $pesan, 'Informasi Seragam');
    }

    // ---------------------------------------------------------------- //
    // LOGIC CHAT HISTORY & REPLY WA
    // ---------------------------------------------------------------- //

    public function loadChat($id)
    {
        $this->selectedSantri = Santri::find($id);
        if (!$this->selectedSantri) return;

        // Fetch chat
        $this->fetchChatHistory();

        $this->showChatModal = true;
    }

    public function fetchChatHistory()
    {
        if (!$this->selectedSantri) return;
        
        $targetPhone = $this->selectedSantri->hp;
        // Format to 62
        $formattedPhone = preg_replace('/^0/', '62', $targetPhone);
        $formattedPhone = preg_replace('/^\+62/', '62', $formattedPhone);

        $this->chatHistories = WaMessage::where(function($q) use ($formattedPhone) {
            $q->where('sender', $formattedPhone)
              ->orWhere('receiver', $formattedPhone);
        })->orderBy('created_at', 'asc')->get();
    }

    public function replyChat()
    {
        $this->validate([
            'replyMessage' => 'required|string'
        ]);

        if ($this->selectedSantri) {
            // Gunakan fungsi yang ada untuk mengirim pesan WA
            $this->sendWhatsAppMessage($this->selectedSantri->hp, $this->replyMessage, 'Balasan Chat', 'person');
            
            // Mengosongkan text area setelah dikirim
            $this->replyMessage = '';
            
            // Reload riwayat pesan
            // Catatan: Jika WA API tidak instan, Anda bisa memasukkan pesan sementara ke array $this->chatHistories
            $this->fetchChatHistory(); 
        }
    }

    public function closeChatModal()
    {
        $this->showChatModal = false;
        $this->selectedSantri = null;
        $this->chatHistories = [];
        $this->replyMessage = '';
    }

    // ---------------------------------------------------------------- //
    // LOGIC WA API SENDER
    // ---------------------------------------------------------------- //

    private function sendWhatsAppMessage($target, $message, $tipeAlert, $tipeApi = 'person', $extraData = [])
    {
        if (!$target) {
            $this->dispatch('swal', title: 'Gagal', text: 'Target (Nomor HP / ID Group) tidak valid/kosong.', icon: 'error');
            return;
        }

        $payload = [
            'apiKey' => env('API_KEY_WA'),
        ];

        $url = '';

        // 1. Send Person atau 2. Send Ad Reply
        if ($tipeApi === 'person' || $tipeApi === 'ad_reply') {
            // Format nomor HP (ubah awalan 0 atau +62 menjadi 62)
            // Bisa di-uncomment "$target = '...';" di bawah ini jika ingin testing ke nomor spesifik
            
            // $target = '085236924510';
            $formattedPhone = preg_replace('/^0/', '62', $target);
            $formattedPhone = preg_replace('/^\+62/', '62', $formattedPhone);

            $payload['phone'] = $formattedPhone;

            if ($tipeApi === 'person') {
                $url = env('URL_PERSON');
                $payload['message'] = $message;
            } elseif ($tipeApi === 'ad_reply') {
                $url = env('URL_AD_REPLY'); // Pastikan env URL_AD_REPLY => endpoint ad_reply
                $payload['title'] = $extraData['title'] ?? '';
                $payload['desc'] = $extraData['desc'] ?? '';
                $payload['body_message'] = $message;
                $payload['url_file'] = $extraData['url_file'] ?? '';
                $payload['url'] = $extraData['url'] ?? '';
            }
        } 
        // 3. Send Group
        elseif ($tipeApi === 'group') {
            $url = env('URL_GROUP'); // Pastikan env URL_GROUP => endpoint group
            $payload['id_group'] = $target;
            $payload['message'] = $message;
        }

        // ==== PENGIRIMAN WA API ====
        try {
            $response = Http::post($url, $payload);
            $result = $response->json();

            // Cek status HTTP Request 200 DAN 'code' dari respons JSON API adalah 200
            if ($response->successful() && isset($result['code']) && $result['code'] == 200) {
                $this->dispatch('swal', title: 'Berhasil!', text: "Pesan {$tipeAlert} berhasil dikirim", icon: 'success');
                return;
            } else {
                // Jika gagal, tampilkan pesan error asli dari API
                $errorMsg = isset($result['message']) ? $result['message'] : $response->body();
                $this->dispatch('swal', title: 'Gagal API', text: $errorMsg, icon: 'error');
                return;
            }
        } catch (\Exception $e) {
            $this->dispatch('swal', title: 'Error Koneksi', text: $e->getMessage(), icon: 'error');
            return;
        }
    }

    // ---------------------------------------------------------------- //

    public function placeholder()
    {
        return <<<'HTML'
        <div>
           <div class="flex flex-col items-center space-y-4 mt-10">
                <svg class="animate-spin h-12 w-12 text-secondary-blue mt-1/2" viewBox="0 0 50 50">
                    <circle cx="25" cy="25" r="20" fill="none" stroke="currentColor"
                        stroke-width="4" stroke-linecap="round" stroke-dasharray="31.4 31.4" opacity="0.25" />
                    <circle cx="25" cy="25" r="20" fill="none" stroke="currentColor"
                        stroke-width="4" stroke-linecap="round" stroke-dasharray="31.4 94.2" />
                </svg>
            </div>
        </div>
        HTML;
    }
}
