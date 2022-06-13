<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\SendMessage;
use App\Jobs\SendFile;
use Illuminate\Http\Request;

class WhatsappController extends Controller
{
    public function sendMessage(Request $request)
    {
        $validator = $request->validate([
            'chatId' => 'required',
            'number' => 'required|numeric',
            'body' => 'required|string',
        ]);

        $url = env('CHATAPI_WA') . '/sendMessage?token=' . env('CHATAPI_WA_TOKEN');
        $data = [
            'phone' => $request->number,
            'body' => $request->body
        ];

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $url, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($data),
        ]);

        return json_decode($response->getBody()->getContents(), true);

        // $response = dispatch(new SendMessage($request->chatId, $request->number, $request->body));

        // return response()->json([
        //     'status' => 'success',
        //     'data' => 'Message sent',
        // ]);
    }

    public function sendFile(Request $request)
    {
        $validator = $request->validate([
            'chatId' => 'required',
            'number' => 'required|numeric',
            'file' => 'required|file',
        ]);

        $name = $request->file->getClientOriginalName();
        $mimetype = $request->file->getMimeType();
        $base64file = base64_encode(file_get_contents($request->file));

        $url = env('CHATAPI_WA') . '/sendFile?token=' . env('CHATAPI_WA_TOKEN');
        $data = [
            'phone' => $request->number,
            'body' => 'data:' . $mimetype . ';base64,' . $base64file,
            'filename' => $name,
        ];

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $url, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($data),
        ]);

        return json_decode($response->getBody()->getContents(), true);

        // $response = dispatch(new SendFile($request->chatId, $request->number, 'data:' . $mimetype . ';base64,' . $base64file, $name));
        
        // return response()->json([
        //     'status' => 'success',
        //     'data' => 'File sent',
        //     'name' => $name,
        //     'base64file' => 'data:' . $mimetype . ';base64,' . $base64file
        // ]);
    }

    public function sendFileWhatsapp($data)
    {
        $url = env('CHATAPI_WA') . '/sendFile?token=' . env('CHATAPI_WA_TOKEN');

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $url, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($data),
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function sendMessageWhatsapp($data)
    {
        $url = env('CHATAPI_WA') . '/sendMessage?token=' . env('CHATAPI_WA_TOKEN');

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $url, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($data),
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
