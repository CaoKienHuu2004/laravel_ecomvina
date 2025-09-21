<?php
// app/Console/Commands/TestAIService.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ChatAIService;

class TestAIService extends Command
{
    protected $signature = 'ai:test';
    protected $description = 'Test AI Service functionality';

    protected $chatService;

    public function __construct(ChatAIService $chatService)
    {
        parent::__construct();
        $this->chatService = $chatService;
    }

    public function handle()
    {
        $this->info('Testing AI Service...');

        // Test prediction
        $result = $this->chatService->processMessage('Xin chào');
        $this->info('Response: ' . $result['response']);
        $this->info('Intent: ' . $result['intent']);
        $this->info('Confidence: ' . $result['confidence']);

        // Test model info
        $stats = $this->chatService->getTrainingStats();
        $this->info('Training stats: ' . json_encode($stats));

        $this->info('AI Service test completed!');
    }
}
