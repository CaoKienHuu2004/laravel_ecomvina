<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ChatAIService;

class TrainChatModel extends Command
{
    protected $signature = 'chat:train';
    protected $description = 'Train the chat AI model';

    protected $chatService;

    public function __construct(ChatAIService $chatService)
    {
        parent::__construct();
        $this->chatService = $chatService;
    }

    public function handle()
    {
        $this->info('Starting chat model training...');

        try {
            $this->chatService->trainModel();  //trainClassifier();
            $this->info('Model trained successfully!');

            return 0;
        } catch (\Exception $e) {
            $this->error('Training failed: ' . $e->getMessage());
            return 1;
        }
    }
}
