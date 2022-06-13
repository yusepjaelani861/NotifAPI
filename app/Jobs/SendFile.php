<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chatId,
                $number,
                $file,
                $name;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($chatId, $number, $file, $name)
    {
        $this->file = $file;
        $this->number = $number;
        $this->chatId = $chatId;
        $this->name = $name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url = env('CHATAPI_WA') . '/sendFile?token=' . env('CHATAPI_WA_TOKEN');
        $data = [
            'phone' => $this->number,
            'body' => $this->file,
            'filename' => $this->name,
        ];

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
