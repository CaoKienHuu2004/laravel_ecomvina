<?php
// app/Services/ChatAIService.php

namespace App\Services;

use Phpml\Classification\KNearestNeighbors;
use Phpml\Classification\NaiveBayes;
use Phpml\Classification\SVC;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WordTokenizer;
use Phpml\Dataset\ArrayDataset;
use NlpTools\Tokenizers\WhitespaceTokenizer;
use NlpTools\Stemmers\PorterStemmer;
use App\Models\AIIntent;
use App\Models\AITrainingData;
use App\Models\AIResponse;
use App\Models\AIConversation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ChatAIService
{
    private $classifier;
    private $vectorizer;
    private $tfIdfTransformer;
    private $tokenizer;
    private $stemmer;
    private $algorithm;
    private $vocabulary = [];

    // Dữ liệu responses mặc định
    private $defaultResponses = [
        'greeting' => [
            'Xin chào! Tôi có thể giúp gì cho bạn?',
            'Chào bạn! Rất vui được trò chuyện với bạn.',
            'Hello! Bạn cần tôi giúp gì không?'
        ],
        'goodbye' => [
            'Tạm biệt! Hẹn gặp lại bạn.',
            'Bye bye! Chúc bạn một ngày tốt lành.',
            'Hẹn gặp lại nhé!'
        ],
        'thanks' => [
            'Không có gì! Rất vui được giúp đỡ bạn.',
            'Luôn sẵn lòng hỗ trợ bạn!',
            'Cảm ơn bạn đã sử dụng dịch vụ!'
        ],
        'weather' => [
            'Hiện tôi chưa có dữ liệu thời tiết. Bạn có thể kiểm tra trên ứng dụng thời tiết nhé!',
            'Xin lỗi, tôi chưa kết nối được với dịch vụ thời tiết.',
            'Bạn có thể cho tôi biết vị trí cụ thể để tôi kiểm tra thời tiết không?'
        ],
        'time' => [
            'Hiện tại là: {time}',
            'Bây giờ là: {time}',
            'Theo đồng hồ của tôi thì là: {time}'
        ],
        'unknown' => [
            'Tôi không hiểu ý bạn. Bạn có thể diễn đạt lại được không?',
            'Xin lỗi, tôi chưa được huấn luyện để hiểu câu này.',
            'Bạn có thể hỏi theo cách khác không?'
        ]
    ];

    public function __construct($algorithm = 'naive_bayes')
    {
        $this->algorithm = $algorithm;
        $this->tokenizer = new WordTokenizer();
        $this->vectorizer = new TokenCountVectorizer($this->tokenizer);
        $this->tfIdfTransformer = new TfIdfTransformer();
        $this->stemmer = new PorterStemmer();

        $this->initializeModel();
    }

    /**
     * Khởi tạo model
     */
    private function initializeModel()
    {
        try {
            $this->trainModel();
        } catch (\Exception $e) {
            Log::error('Failed to initialize AI model: ' . $e->getMessage());
        }
    }
    // geter và seter begin
    public function getAlgorithm(): string
    {
        return $this->algorithm;
    }

    // geter và seter end

    /**
     * Train model với dữ liệu từ database
     */
    public function trainModel()
    {
        $trainingData = $this->getTrainingDataFromDB();

        if (empty($trainingData['samples'])) {
            // Sử dụng dữ liệu training mặc định nếu database trống
            $trainingData = $this->getDefaultTrainingData();
        }

        // Lưu vocabulary để sử dụng sau này
        $this->vocabulary = $this->extractVocabulary($trainingData['samples']);

        // Vector hóa dữ liệu
        $this->vectorizer->fit($trainingData['samples']);
        $vectorizedSamples = $trainingData['samples'];
        $this->vectorizer->transform($vectorizedSamples);

        $this->tfIdfTransformer->fit($vectorizedSamples);
        $this->tfIdfTransformer->transform($vectorizedSamples);

        // Chọn algorithm
        switch ($this->algorithm) {
            case 'svm':
                $this->classifier = new SVC();
                break;
            case 'knn':
                $this->classifier = new KNearestNeighbors(3);
                break;
            case 'naive_bayes':
            default:
                $this->classifier = new NaiveBayes();
                break;
        }

        // Train model
        $this->classifier->train($vectorizedSamples, $trainingData['labels']);

        Log::info('AI model trained successfully with algorithm: ' . $this->algorithm);
    }
    // public function getAlgorithm()
    // {
    //     switch ($this->algorithm) {
    //         case 'knn':
    //             return new KNearestNeighbors();
    //         case 'svc':
    //             return new SVC();
    //         case 'naive_bayes':
    //         default:
    //             return new NaiveBayes();
    //     }
    // }



    /**
     * Trích xuất vocabulary từ samples
     */
    private function extractVocabulary(array $samples): array
    {
        $vocabulary = [];
        foreach ($samples as $sample) {
            $words = preg_split('/\s+/', strtolower($sample));
            foreach ($words as $word) {
                $word = preg_replace('/[^\p{L}\p{N}]/u', '', $word);
                if (!empty($word)) {
                    $vocabulary[$word] = true;
                }
            }
        }
        return array_keys($vocabulary);
    }

    /**
     * Lấy dữ liệu training từ database
     */
    private function getTrainingDataFromDB(): array
    {
        $trainingData = AITrainingData::with('intent')->get();

        $samples = [];
        $labels = [];

        foreach ($trainingData as $data) {
            $samples[] = $data->text;
            $labels[] = $data->intent->name;
        }

        return [
            'samples' => $samples,
            'labels' => $labels
        ];
    }

    /**
     * Dữ liệu training mặc định
     */
    private function getDefaultTrainingData(): array
    {
        return [
            'samples' => [
                'xin chào', 'chào bạn', 'hello', 'hi', 'chào buổi sáng',
                'tạm biệt', 'bye', 'hẹn gặp lại', 'tôi đi đây',
                'cảm ơn', 'thanks', 'cám ơn', 'thank you',
                'thời tiết', 'thời tiết hôm nay', 'trời hôm nay thế nào', 'nhiệt độ bao nhiêu',
                'mấy giờ rồi', 'thời gian', 'giờ hiện tại', 'bây giờ là mấy giờ'
            ],
            'labels' => [
                'greeting', 'greeting', 'greeting', 'greeting', 'greeting',
                'goodbye', 'goodbye', 'goodbye', 'goodbye',
                'thanks', 'thanks', 'thanks', 'thanks',
                'weather', 'weather', 'weather', 'weather',
                'time', 'time', 'time', 'time'
            ]
        ];
    }

    /**
     * Tiền xử lý văn bản
     */
    private function preprocessText(string $text): string
    {
        // Chuẩn hóa văn bản
        $text = mb_strtolower(trim($text), 'UTF-8');
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', $text); // Loại bỏ ký tự đặc biệt

        return $text;
    }

    /**
     * Dự đoán intent
     */
    public function predictIntent(string $text): array
    {
        $processedText = $this->preprocessText($text);

        // Ưu tiên sử dụng classifier nếu available
        if ($this->classifier) {
            try {
                return $this->predictWithClassifier($processedText);
            } catch (\Exception $e) {
                Log::warning('Classifier prediction failed, using simple matching: ' . $e->getMessage());
            }
        }

        // Fallback: sử dụng similarity matching đơn giản
        return $this->predictWithSimilarity($processedText);
    }

    /**
     * Dự đoán sử dụng classifier - ĐÃ SỬA LỖI
     */
    private function predictWithClassifier(string $processedText): array
    {
        try {
            // Approach đơn giản: predict trực tiếp trên text
            $intent = $this->classifier->predict([$processedText])[0];

            // Tính confidence đơn giản
            $confidence = $this->calculateSimpleConfidence($processedText, $intent);

            return [
                'intent' => $intent,
                'confidence' => $confidence,
                'probabilities' => [$intent => $confidence],
                'method' => 'classifier'
            ];

        } catch (\Exception $e) {
            Log::error('Simple classifier failed: ' . $e->getMessage());
            // Fallback to similarity matching
            return $this->predictWithSimilarity($processedText);
        }
    }

    /**
     * Tính confidence đơn giản - ĐÃ SỬA LỖI
     */
    private function calculateSimpleConfidence(string $text, string $predictedIntent): float
    {
        // Tính confidence dựa trên similarity với training data của intent đó
        $trainingData = $this->getTrainingDataFromDB();

        if (empty($trainingData['samples'])) {
            $trainingData = $this->getDefaultTrainingData();
        }

        $maxSimilarity = 0;

        // Tìm sample có similarity cao nhất trong cùng intent
        foreach ($trainingData['samples'] as $index => $sample) {
            if ($trainingData['labels'][$index] === $predictedIntent) {
                $similarity = $this->calculateTextSimilarity($text, $sample);
                $maxSimilarity = max($maxSimilarity, $similarity);
            }
        }

        // Scale similarity thành confidence (0.5 - 0.95)
        return 0.5 + ($maxSimilarity * 0.45);
    }

    /**
     * Dự đoán sử dụng similarity matching
     */
    private function predictWithSimilarity(string $processedText): array
    {
        $trainingData = $this->getTrainingDataFromDB();
        $bestMatch = ['intent' => 'unknown', 'confidence' => 0];

        if (empty($trainingData['samples'])) {
            $trainingData = $this->getDefaultTrainingData();
        }

        foreach ($trainingData['samples'] as $index => $sample) {
            $similarity = $this->calculateTextSimilarity($processedText, $sample);

            if ($similarity > $bestMatch['confidence']) {
                $bestMatch = [
                    'intent' => $trainingData['labels'][$index],
                    'confidence' => $similarity
                ];
            }
        }

        // Nếu confidence quá thấp, trả về unknown
        if ($bestMatch['confidence'] < 0.3) {
            $bestMatch = ['intent' => 'unknown', 'confidence' => 0.1];
        }

        return [
            'intent' => $bestMatch['intent'],
            'confidence' => $bestMatch['confidence'],
            'probabilities' => [$bestMatch['intent'] => $bestMatch['confidence']],
            'method' => 'similarity'
        ];
    }

    /**
     * Tính similarity giữa hai văn bản
     */
    private function calculateTextSimilarity(string $text1, string $text2): float
    {
        // Sử dụng similar_text cho đơn giản
        similar_text($text1, $text2, $similarity);
        return $similarity / 100;
    }

    /**
     * Tạo phản hồi dựa trên intent
     */
    public function generateResponse(string $intent, string $originalText = ''): string
    {
        // Ưu tiên lấy response từ database
        $dbResponse = $this->getResponseFromDatabase($intent);
        if ($dbResponse) {
            return $this->processResponseWithPlaceholders($dbResponse, $originalText);
        }

        // Fallback đến responses mặc định
        if (!isset($this->defaultResponses[$intent])) {
            $intent = 'unknown';
        }

        $response = $this->defaultResponses[$intent][array_rand($this->defaultResponses[$intent])];
        return $this->processResponseWithPlaceholders($response, $originalText);
    }

    /**
     * Lấy response từ database
     */
    private function getResponseFromDatabase(string $intent): ?string
    {
        try {
            $intentModel = AIIntent::where('name', $intent)->first();
            if (!$intentModel) {
                return null;
            }

            $response = AIResponse::where('intent_id', $intentModel->id)
                ->where('active', true)
                ->orderBy('priority', 'desc')
                ->first();

            return $response ? $response->response : null;
        } catch (\Exception $e) {
            Log::error('Error getting response from database: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Xử lý placeholders trong response
     */
    private function processResponseWithPlaceholders(string $response, string $originalText = ''): string
    {
        // Xử lý các placeholder đặc biệt
        if (strpos($response, '{time}') !== false) {
            $response = str_replace('{time}', now()->format('H:i:s'), $response);
        }

        if (strpos($response, '{date}') !== false) {
            $response = str_replace('{date}', now()->format('d/m/Y'), $response);
        }

        if (strpos($response, '{user_input}') !== false) {
            $response = str_replace('{user_input}', $originalText, $response);
        }

        return $response;
    }

    /**
     * Phân tích cảm xúc cơ bản
     */
    public function analyzeSentiment(string $text): string
    {
        $positiveWords = ['tốt', 'tuyệt', 'xuất sắc', 'hay', 'đẹp', 'thích', 'happy', 'vui', 'cảm ơn'];
        $negativeWords = ['xấu', 'tệ', 'dở', 'không thích', 'buồn', 'chán', 'ghét', 'tồi'];

        $text = mb_strtolower($text, 'UTF-8');
        $positiveCount = 0;
        $negativeCount = 0;

        foreach ($positiveWords as $word) {
            if (strpos($text, $word) !== false) {
                $positiveCount++;
            }
        }

        foreach ($negativeWords as $word) {
            if (strpos($text, $word) !== false) {
                $negativeCount++;
            }
        }

        if ($positiveCount > $negativeCount) {
            return 'positive';
        } elseif ($negativeCount > $positiveCount) {
            return 'negative';
        } else {
            return 'neutral';
        }
    }

    /**
     * Xử lý tin nhắn người dùng (all-in-one)
     */
    public function processMessage(string $message): array
    {
        $prediction = $this->predictIntent($message);
        $response = $this->generateResponse($prediction['intent'], $message);
        $sentiment = $this->analyzeSentiment($message);

        return [
            'intent' => $prediction['intent'],
            'response' => $response,
            'sentiment' => $sentiment,
            'confidence' => $prediction['confidence'],
            'method' => $prediction['method'] ?? 'unknown'
        ];
    }

    /**
     * Kiểm tra model đã được train chưa
     */
    public function isModelTrained(): bool
    {
        return $this->classifier !== null;
    }

    /**
     * Đổi algorithm và train lại
     */
    public function changeAlgorithm(string $newAlgorithm): bool
    {
        $allowedAlgorithms = ['naive_bayes', 'knn', 'svm'];

        if (!in_array($newAlgorithm, $allowedAlgorithms)) {
            return false;
        }

        $this->algorithm = $newAlgorithm;
        try {
            $this->trainModel();
            return true;
        } catch (\Exception $e) {
            Log::error('Error changing algorithm: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get training data statistics
     */
    public function getTrainingStats(): array
    {
        $trainingData = $this->getTrainingDataFromDB();

        $intentCounts = [];
        if (!empty($trainingData['labels'])) {
            $intentCounts = array_count_values($trainingData['labels']);
        }

        return [
            'total_samples' => count($trainingData['samples']),
            'intent_distribution' => $intentCounts,
            'algorithm' => $this->algorithm,
            'vocabulary_size' => count($this->vocabulary),
            'model_trained' => $this->isModelTrained(),
            'model_type' => get_class($this->classifier)
        ];
    }

    /**
     * Debug prediction
     */
    public function debugPrediction(string $text): array
    {
        $processedText = $this->preprocessText($text);

        $result = [
            'original_text' => $text,
            'processed_text' => $processedText,
            'classifier_available' => $this->classifier !== null,
            'algorithm' => $this->algorithm
        ];

        if ($this->classifier) {
            try {
                $result['direct_prediction'] = $this->classifier->predict([$processedText])[0];
            } catch (\Exception $e) {
                $result['direct_prediction_error'] = $e->getMessage();
            }
        }

        $result['similarity_prediction'] = $this->predictWithSimilarity($processedText);

        return $result;
    }

    /**
     * Debug method để kiểm tra model
     */
    public function debugModel(): array
    {
        return [
            'classifier' => $this->classifier ? get_class($this->classifier) : null,
            'vectorizer' => $this->vectorizer ? get_class($this->vectorizer) : null,
            'tfidf' => $this->tfIdfTransformer ? get_class($this->tfIdfTransformer) : null,
            'vocabulary_size' => count($this->vocabulary),
            'algorithm' => $this->algorithm
        ];
    }


}
