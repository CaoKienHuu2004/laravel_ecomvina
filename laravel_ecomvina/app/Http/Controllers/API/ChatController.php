<?php
// app/Http/Controllers/ChatController.php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\ChatAIService;
use App\Services\SemanticProcessor;
use App\Models\AIConversation;
use App\Models\AITrainingData;
use App\Models\AIIntent;
use App\Models\AIResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ChatController extends BaseController
{
    protected $chatService;
    protected $semanticProcessor;

    /**
     * curl -X GET http://your-app.test/api/chat/model-info \
        * -H "Authorization: Bearer YOUR_TOKEN"
     */
    public function __construct(ChatAIService $chatService, SemanticProcessor $semanticProcessor)
    {
        $this->chatService = $chatService;
        $this->semanticProcessor = $semanticProcessor;
    }

    /**
     * Xử lý chat message chính
     */
    public function processChat(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $userInput = $validated['message'];

        try {
            // Phân tích ngữ nghĩa nâng cao
            $semanticAnalysis = $this->enhancedSemanticAnalysis($userInput);

            // Xử lý với AI service
            $prediction = $this->chatService->predictIntent($userInput);

            // Tìm response phù hợp
            $response = $this->getResponseForIntent(
                $prediction['intent'],
                $userInput,
                $semanticAnalysis
            );

            // Lưu conversation
            $this->saveConversation($userInput, $response, $prediction, $semanticAnalysis);

            return $this->jsonResponse([
                'reply' => $response,
                'intent' => $prediction['intent'],
                'confidence' => $prediction['confidence'],
                'entities' => $semanticAnalysis['entities'],
                'sentiment' => $this->chatService->analyzeSentiment($userInput),
                'status' => 'success'
            ]);

        } catch (\Exception $e) {
            Log::error('Chat processing error: ' . $e->getMessage());

            return $this->jsonResponse([
                'reply' => 'Xin lỗi, hệ thống đang gặp sự cố kỹ thuật.',
                'status' => 'error'
            ], 500);
        }
    }

    /**
     * Thêm dữ liệu training mới - Sử dụng ChatAIService
     */
    public function addTrainingData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string|max:1000',
            'intent' => 'required|string|max:255',
            'response' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Tìm hoặc tạo intent
            $intent = AIIntent::firstOrCreate(
                ['name' => $request->intent],
                ['description' => 'Intent: ' . $request->intent]
            );

            // Thêm training data
            $trainingData = AITrainingData::create([
                'intent_id' => $intent->id,
                'text' => $request->text,
                'metadata' => ['source' => 'api']
            ]);

            // Thêm response nếu có
            if ($request->has('response') && !empty($request->response)) {
                AIResponse::create([
                    'intent_id' => $intent->id,
                    'response' => $request->response,
                    'priority' => 1,
                    'active' => true
                ]);
            }

            DB::commit();

            // Train lại model với dữ liệu mới
            $this->chatService->trainModel();

            return $this->jsonResponse([
                'status' => 'success',
                'message' => 'Training data added successfully',
                'data' => [
                    'intent' => $intent->name,
                    'training_data_id' => $trainingData->id
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Add training data error: ' . $e->getMessage());

            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Failed to add training data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Train lại model - Sử dụng ChatAIService (phiên bản đơn giản)
     */
    public function retrainModel(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'algorithm' => 'nullable|in:naive_bayes,knn,svm'
            ]);

            if ($validator->fails()) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => 'Invalid algorithm',
                    'errors' => $validator->errors()
                ], 422);
            }

            $algorithm = $request->input('algorithm');

            // Nếu có algorithm mới, tạo instance ChatAIService mới
            if ($algorithm && $algorithm !== $this->chatService->getAlgorithm()) {
                $this->chatService = new ChatAIService($algorithm);
            }

            // Train lại model
            $this->chatService->trainModel();

            $stats = $this->chatService->getTrainingStats();

            return $this->jsonResponse([
                'status' => 'success',
                'message' => 'Model retrained successfully',
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Retrain model error: ' . $e->getMessage());

            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Failed to retrain model: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thông tin model
     */
    public function getModelInfo()
    {
        try {
            $stats = $this->chatService->getTrainingStats();

            return $this->jsonResponse([
                'status' => 'success',
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Get model info error: ' . $e->getMessage());

            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Failed to get model info'
            ], 500);
        }
    }

    /**
     * Phân tích ngữ nghĩa nâng cao
     */
    private function enhancedSemanticAnalysis(string $text): array
    {
        return [
            'entities' => $this->semanticProcessor->extractEntities($text),
            'dependencies' => $this->semanticProcessor->analyzeDependencies($text),
            'similar_questions' => $this->semanticProcessor->findSimilarQuestion($text)
        ];
    }

    /**
     * Lấy response cho intent với xử lý ngữ nghĩa
     */
    private function getResponseForIntent(string $intent, string $userInput, array $semanticAnalysis): string
    {
        $response = $this->chatService->generateResponse($intent, $userInput);

        // Xử lý thay thế entities trong response
        return $this->processResponseWithEntities($response, $semanticAnalysis['entities']);
    }

    /**
     * Xử lý thay thế entities trong response
     */
    private function processResponseWithEntities(string $response, array $entities): string
    {
        if (!empty($entities['phones'])) {
            $response = str_replace('{phone}', $entities['phones'][0], $response);
        }

        if (!empty($entities['emails'])) {
            $response = str_replace('{email}', $entities['emails'][0], $response);
        }

        return $response;
    }

    /**
     * Lưu conversation với đầy đủ thông tin
     */
    private function saveConversation(string $userInput, string $response, array $prediction, array $semanticAnalysis): void
    {
        AIConversation::create([
            'user_input' => $userInput,
            'ai_response' => $response,
            'intent' => $prediction['intent'],
            'sentiment' => $this->chatService->analyzeSentiment($userInput),
            'confidence' => $prediction['confidence'],
            'ip_address' => request()->ip(),
            'metadata' => [
                'semantic_analysis' => $semanticAnalysis,
                'probabilities' => $prediction['probabilities'] ?? [],
                'method' => $prediction['method'] ?? 'unknown'
            ]
        ]);
    }
}
