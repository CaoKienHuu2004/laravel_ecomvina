<?php

// namespace App\Services;

// use Phpml\Classification\KNearestNeighbors;
// use Phpml\FeatureExtraction\TfIdfTransformer;
// use Phpml\FeatureExtraction\TokenCountVectorizer;
// use Phpml\Tokenization\WordTokenizer;
// use Phpml\Dataset\ArrayDataset;
// use Phpml\Metric\Accuracy;
// use NlpTools\Tokenizers\WhitespaceTokenizer;
// use NlpTools\Stemmers\PorterStemmer;

// class ChatAIService
// {
//     private $classifier;
//     private $vectorizer;
//     private $tfIdfTransformer;
//     private $tokenizer;
//     private $stemmer;

//     // Dữ liệu training mẫu
//     private $trainingData = [
//         // Intent: greeting
//         'xin chào' => 'greeting',
//         'chào bạn' => 'greeting',
//         'hello' => 'greeting',
//         'hi' => 'greeting',
//         'chào buổi sáng' => 'greeting',

//         // Intent: goodbye
//         'tạm biệt' => 'goodbye',
//         'bye' => 'goodbye',
//         'hẹn gặp lại' => 'goodbye',
//         'tôi đi đây' => 'goodbye',

//         // Intent: thanks
//         'cảm ơn' => 'thanks',
//         'thanks' => 'thanks',
//         'cám ơn' => 'thanks',
//         'thank you' => 'thanks',

//         // Intent: weather
//         'thời tiết' => 'weather',
//         'thời tiết hôm nay' => 'weather',
//         'trời hôm nay thế nào' => 'weather',
//         'nhiệt độ bao nhiêu' => 'weather',

//         // Intent: time
//         'mấy giờ rồi' => 'time',
//         'thời gian' => 'time',
//         'giờ hiện tại' => 'time',
//         'bây giờ là mấy giờ' => 'time',
//     ];

//     private $responses = [
//         'greeting' => [
//             'Xin chào! Tôi có thể giúp gì cho bạn?',
//             'Chào bạn! Rất vui được trò chuyện với bạn.',
//             'Hello! Bạn cần tôi giúp gì không?'
//         ],
//         'goodbye' => [
//             'Tạm biệt! Hẹn gặp lại bạn.',
//             'Bye bye! Chúc bạn một ngày tốt lành.',
//             'Hẹn gặp lại nhé!'
//         ],
//         'thanks' => [
//             'Không có gì! Rất vui được giúp đỡ bạn.',
//             'Luôn sẵn lòng hỗ trợ bạn!',
//             'Cảm ơn bạn đã sử dụng dịch vụ!'
//         ],
//         'weather' => [
//             'Hiện tôi chưa có dữ liệu thời tiết. Bạn có thể kiểm tra trên ứng dụng thời tiết nhé!',
//             'Xin lỗi, tôi chưa kết nối được với dịch vụ thời tiết.',
//             'Bạn có thể cho tôi biết vị trí cụ thể để tôi kiểm tra thời tiết không?'
//         ],
//         'time' => [
//             'Hiện tại là: {time}',
//             'Bây giờ là: {time}',
//             'Theo đồng hồ của tôi thì là: {time}'
//         ],
//         'default' => [
//             'Tôi không hiểu ý bạn. Bạn có thể diễn đạt lại được không?',
//             'Xin lỗi, tôi chưa được huấn luyện để hiểu câu này.',
//             'Bạn có thể hỏi theo cách khác không?'
//         ]
//     ];

//     public function __construct()
//     {
//         $this->tokenizer = new WordTokenizer();
//         $this->vectorizer = new TokenCountVectorizer($this->tokenizer);
//         $this->tfIdfTransformer = new TfIdfTransformer();
//         $this->stemmer = new PorterStemmer();

//         $this->trainClassifier();
//     }

//     /**
//      * Huấn luyện classifier
//      */
//     private function trainClassifier()
//     {
//         $samples = [];
//         $labels = [];

//         foreach ($this->trainingData as $text => $intent) {
//             $samples[] = $text;
//             $labels[] = $intent;
//         }

//         // Vector hóa dữ liệu
//         $this->vectorizer->fit($samples);
//         $this->vectorizer->transform($samples);

//         $this->tfIdfTransformer->fit($samples);
//         $this->tfIdfTransformer->transform($samples);

//         // Huấn luyện model KNN
//         $this->classifier = new KNearestNeighbors();
//         $this->classifier->train($samples, $labels);
//     }

//     /**
//      * Tiền xử lý văn bản
//      */
//     private function preprocessText(string $text): string
//     {
//         // Chuẩn hóa văn bản
//         $text = mb_strtolower(trim($text), 'UTF-8');
//         $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', $text); // Loại bỏ ký tự đặc biệt

//         // Stemming (tùy chọn)
//         $words = (new WhitespaceTokenizer())->tokenize($text);
//         $stemmedWords = array_map(function($word) {
//             return $this->stemmer->stem($word);
//         }, $words);

//         return implode(' ', $stemmedWords);
//     }

//     /**
//      * Dự đoán intent từ văn bản
//      */
//     public function predictIntent(string $text): string
//     {
//         $processedText = $this->preprocessText($text);

//         // Vector hóa văn bản input
//         $vectorized = [$processedText];
//         $this->vectorizer->transform($vectorized);
//         $this->tfIdfTransformer->transform($vectorized);

//         try {
//             $intent = $this->classifier->predict($vectorized)[0];
//             return $intent;
//         } catch (\Exception $e) {
//             return 'default';
//         }
//     }

//     /**
//      * Tạo phản hồi dựa trên intent
//      */
//     public function generateResponse(string $intent, string $originalText = ''): string
//     {
//         if (!isset($this->responses[$intent])) {
//             $intent = 'default';
//         }

//         $response = $this->responses[$intent][array_rand($this->responses[$intent])];

//         // Xử lý các placeholder đặc biệt
//         if ($intent === 'time') {
//             $response = str_replace('{time}', now()->format('H:i:s'), $response);
//         }

//         return $response;
//     }

//     /**
//      * Phân tích cảm xúc cơ bản
//      */
//     public function analyzeSentiment(string $text): string
//     {
//         $positiveWords = ['tốt', 'tuyệt', 'xuất sắc', 'hay', 'đẹp', 'thích', 'happy', 'vui'];
//         $negativeWords = ['xấu', 'tệ', 'dở', 'không thích', 'buồn', 'chán', 'ghét'];

//         $text = mb_strtolower($text, 'UTF-8');
//         $positiveCount = 0;
//         $negativeCount = 0;

//         foreach ($positiveWords as $word) {
//             if (strpos($text, $word) !== false) {
//                 $positiveCount++;
//             }
//         }

//         foreach ($negativeWords as $word) {
//             if (strpos($text, $word) !== false) {
//                 $negativeCount++;
//             }
//         }

//         if ($positiveCount > $negativeCount) {
//             return 'positive';
//         } elseif ($negativeCount > $positiveCount) {
//             return 'negative';
//         } else {
//             return 'neutral';
//         }
//     }

//     /**
//      * Xử lý tin nhắn người dùng
//      */
//     public function processMessage(string $message): array
//     {
//         $intent = $this->predictIntent($message);
//         $response = $this->generateResponse($intent, $message);
//         $sentiment = $this->analyzeSentiment($message);

//         return [
//             'intent' => $intent,
//             'response' => $response,
//             'sentiment' => $sentiment,
//             'confidence' => 0.8 // Giá trị giả định
//         ];
//     }
// }
