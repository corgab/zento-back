<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\NewsletterMail;
use App\Models\Post;
use Illuminate\Support\Facades\Log;

class SendNewsletter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = 10; 

    protected $subscribers;

    public function __construct($subscribers)
    {
        $this->subscribers = $subscribers;
    }

    // app/Jobs/SendNewsletter.php

    public function handle()
    {
        \Log::info('Job eseguito per l\'invio della newsletter.');

        // Recupera gli ultimi 3 posts featured
        $posts = Post::orderBy('created_at', 'desc')->where('status', 'published')->where('featured', true)->with('tags')->take(3)->get();

        foreach ($this->subscribers as $subscriber) {
            // Invia la mail
            Mail::to($subscriber->email)->send(new NewsletterMail($posts));

            \Log::info('Invio newsletter a: ' . $subscriber->email . ' con successo');
            sleep(5);
        }
    }

    
}
