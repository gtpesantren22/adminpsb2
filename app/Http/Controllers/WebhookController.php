<?php

namespace App\Http\Controllers;

use App\Models\WaMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    //
    public function whatsapp(Request $request)
    {
        $data = $request->all();

        Log::info('WHAPI WEBHOOK', $data);

        try {

            $type = $data['type'] ?? null;

            // =========================
            // 1. PESAN MASUK
            // =========================
            if ($type === 'message') {

                WaMessage::create([
                    'message_id' => $data['id'] ?? null,
                    'sender'    => $this->cleanNumber($data['from'] ?? null),
                    'receiver'  => null,
                    'message'   => $data['body'] ?? null,
                    'type'      => 'message',
                    'direction' => 'inbound',
                    'raw'       => $data
                ]);

                // 🔥 Contoh auto respon absensi
                $this->handleAutoReply($data);
            }

            // =========================
            // 2. STATUS PESAN
            // =========================
            elseif ($type === 'message_ack') {

                // Update jika ada
                WaMessage::where('message_id', $data['message_id'] ?? null)
                    ->update([
                        'status' => $data['status'] ?? null
                    ]);

                // fallback kalau belum ada
                if (!WaMessage::where('message_id', $data['message_id'] ?? null)->exists()) {
                    WaMessage::create([
                        'message_id' => $data['message_id'] ?? null,
                        'type'      => 'message_ack',
                        'status'    => $data['status'] ?? null,
                        'raw'       => $data
                    ]);
                }
            }

            // =========================
            // 3. PESAN KELUAR
            // =========================
            elseif ($type === 'message_browser') {

                WaMessage::create([
                    'message_id' => $data['id'] ?? null,
                    'sender'    => null,
                    'receiver'  => $this->cleanNumber($data['to'] ?? null),
                    'message'   => $data['body'] ?? null,
                    'type'      => 'message_browser',
                    'direction' => 'outbound',
                    'status'    => 'sent',
                    'raw'       => $data
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Webhook Error: ' . $e->getMessage());
        }

        return response()->json(['status' => 'ok']);
    }

    // =========================
    // HELPER: Bersihkan nomor
    // =========================
    private function cleanNumber($number)
    {
        if (!$number) return null;

        return str_replace('@s.whatsapp.net', '', $number);
    }

    // =========================
    // AUTO REPLY (OPSIONAL 🔥)
    // =========================
    private function handleAutoReply($data)
    {
        $message = strtoupper($data['body'] ?? '');
        $sender  = $data['from'] ?? null;

        if ($message === 'HADIR') {
            Log::info("User HADIR: " . $sender);

            // TODO: kirim balasan via WHAPI API
        }
    }
}
