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
        // Ambil semua data dari WHAPI
        $data = $request->all();

        // Logging (WAJIB untuk debugging awal)
        Log::info('Webhook WHAPI', $data);

        // Contoh parsing umum
        $sender  = $data['from'] ?? null;
        $message = $data['body'] ?? null;
        $type    = $data['type'] ?? null;
        $messageId = $data['messageId'] ?? null;
        $status = $data['status'] ?? null;
        $direction = $data['direction'] ?? null;

        if ($sender && $message) {
            WaMessage::create([
                'sender' => $sender,
                'message' => $message,
                'type'   => $type,
                'message_id' => $messageId,
                'status' => $status,
                'direction' => $direction,
            ]);
        }

        return response()->json(['status' => 'ok']);
    }
}
