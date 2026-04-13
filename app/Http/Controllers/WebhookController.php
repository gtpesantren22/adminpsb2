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

        // Panggil fungsi rekursif untuk membersihkan semua array raksasa (Buffer byte array) dari payload
        $data = $this->sanitizeLargePayload($data);

        Log::info('WHAPI RAW', $data);

        try {

            $type    = $data['type'] ?? null;
            $results = $data['results'] ?? [];

            // =========================
            // PESAN (INBOUND / OUTBOUND)
            // =========================
            if ($type === 'message') {

                // Tangkap ID Pesan
                $messageId = $results['key']['id'] ?? ($results['id'] ?? null);
                
                // Tangkap Body Pesan (bervariasi lokasinya antara pesan normal dan ad_reply)
                $body = $results['body'] ?? 
                        ($results['message']['extendedTextMessage']['text'] ?? 
                        ($results['message']['conversation'] ?? null));

                $fromMe    = $results['key']['fromMe'] ?? ($results['fromMe'] ?? false);

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

    // =========================
    // HELPER: Bersihkan Array Raksasa (Buffer 1.1MB dsb)
    // =========================
    private function sanitizeLargePayload($payload)
    {
        if (!is_array($payload)) {
            return $payload;
        }

        foreach ($payload as $key => $val) {
            if (is_array($val)) {
                // Jika array ini sangat besar (lebih dari 50 item, biasanya byte array Buffer gambar ukurannya ribuan item)
                // dan format key-nya berupa index numerik berturut-turut seperti "0", "1", "2"
                if (count($val) > 100) {
                    $payload[$key] = '[REMOVED_LARGE_BUFFER_SIZE_'.count($val).']';
                    continue;
                }
                
                // Jika mengandung nama thumbnail
                if (stripos((string)$key, 'thumbnail') !== false && count($val) > 10) {
                    $payload[$key] = '[REMOVED_THUMBNAIL_DATA]';
                    continue;
                }

                // Teruskan rekursif ke anak array
                $payload[$key] = $this->sanitizeLargePayload($val);
            } 
            elseif (is_string($val) && strlen($val) > 10000) {
                // Jika ternyata dikirim dalam bentuk base64 string super panjang (> 10KB)
                $payload[$key] = '[REMOVED_LARGE_BASE64_STRING]';
            }
        }

        return $payload;
    }
}
