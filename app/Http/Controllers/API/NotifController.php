<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NotifController extends Controller
{
    public function send(Request $request)
    {
        $validator = $request->validate([
            'chatId' => 'required',
            'number' => 'required_if:chatId,whatsapp|numeric',
            'email' => 'required_if:chatId,email|email',
            'subject' => 'required_if:chatId,email|string',
            'body' => 'required_without:file|string',
            'caption' => 'string|nullable',
            'file' => 'required_without:body|file|nullable',
            'filename' => 'required_if:file,|string|nullable',
        ]);

        if ($request->chatId == 'whatsapp') {
            if ($request->file) {
                $data = [
                    'phone' => $request->number,
                    'body' => 'data:' . $request->file->getMimeType() . ';base64,' . base64_encode(file_get_contents($request->file)),
                    'filename' => $request->file->getClientOriginalName(),
                    'caption' => $request->caption,
                ];

                return (new WhatsappController)->sendFileWhatsapp($data);

            } else{
                $data = [
                    'phone' => $request->number,
                    'body' => $request->body,
                ];

                return (new WhatsappController)->sendMessageWhatsapp($data);
            }
        } else if ($request->chatId == 'email') {
            if ($request->file) {
                Mail::raw($request->body, function ($message) use ($request) {
                    $message->subject($request->subject);
                    $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                    $message->to($request->email);
                    $message->attach($request->file->getRealPath(), [
                        'as' => $request->file->getClientOriginalName(),
                        'mime' => $request->file->getMimeType(),
                    ]);
                });

                return response()->json([
                    'status' => 'success',
                    'data' => 'Email sent with attachment',
                ]);
            } else {
                Mail::raw($request->body, function ($message) use ($request) {
                    $message->subject($request->subject);
                    $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                    $message->to($request->email);
                });

                return response()->json([
                    'status' => 'success',
                    'data' => 'Email sent',
                ]);
            }
        }
    }
}
