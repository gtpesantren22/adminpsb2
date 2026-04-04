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

        Log::info('WHAPI RAW', $data);

        try {

            $type    = $data['type'] ?? null;
            $results = $data['results'] ?? [];

            // =========================
            // PESAN (INBOUND / OUTBOUND)
            // =========================
            if ($type === 'message') {

                $messageId = $results['id'] ?? null;
                $body      = $results['body'] ?? null;

                $fromMe    = $results['fromMe'] ?? false;

                $remoteJid = $results['key']['remoteJid'] ?? null;

                $number    = $this->cleanNumber($remoteJid);

                // Tentukan arah pesan
                $direction = $fromMe ? 'outbound' : 'inbound';

                WaMessage::create([
                    'message_id' => $messageId,
                    'sender'    => $fromMe ? null : $number,
                    'receiver'  => $fromMe ? $number : null,
                    'message'   => $body,
                    'type'      => 'message',
                    'direction' => $direction,
                    'status'    => 'sent',
                    'raw'       => $data
                ]);

                // 🔥 hanya proses auto reply jika dari user
                if (!$fromMe) {
                    $this->handleAutoReply($body, $number);
                }
            }

            // =========================
            // STATUS (ACK)
            // =========================
            elseif ($type === 'message_ack') {

                $results = $data['results'] ?? [];

                WaMessage::where('message_id', $results['id'] ?? null)
                    ->update([
                        'status' => $results['status'] ?? null
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
