<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseNotificationService;

class NotificationController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'device_token' => 'required|string',
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        $deviceToken = $request->device_token;
        $title = $request->title;
        $body = $request->body;
        $data = $request->input('data', []); // opsional, bisa kosong

        // Panggil service FCM
        $fcm = new FirebaseNotificationService();
        $result = $fcm->sendNotification($deviceToken, $title, $body, $data);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dikirim.',
            'result' => $result,
        ]);
    }
}
