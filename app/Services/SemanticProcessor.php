<?php
// app/Services/SemanticProcessor.php

namespace App\Services;

use NlpTools\Similarity\CosineSimilarity;
use NlpTools\Similarity\JaccardIndex;
use NlpTools\Tokenizers\WhitespaceTokenizer;
use NlpTools\Stemmers\PorterStemmer;
use Illuminate\Support\Facades\DB;

class SemanticProcessor
{
    private $cosineSimilarity;
    private $jaccardIndex;
    private $tokenizer;
    private $stemmer;

    public function __construct()
    {
        $this->cosineSimilarity = new CosineSimilarity();
        $this->jaccardIndex = new JaccardIndex();
        $this->tokenizer = new WhitespaceTokenizer();
        $this->stemmer = new PorterStemmer();
    }

    /**
     * Tìm câu hỏi tương tự trong database
     */
    public function findSimilarQuestion(string $question, float $threshold = 0.7): ?array
    {
        $questions = DB::table('ai_training_data')
            ->join('ai_intents', 'ai_training_data.intent_id', '=', 'ai_intents.id')
            ->select('ai_training_data.text', 'ai_intents.name as intent')
            ->get();

        $bestMatch = null;
        $bestScore = 0;

        foreach ($questions as $trainingQuestion) {
            $score = $this->calculateSimilarity($question, $trainingQuestion->text);

            if ($score > $bestScore && $score >= $threshold) {
                $bestScore = $score;
                $bestMatch = [
                    'text' => $trainingQuestion->text,
                    'intent' => $trainingQuestion->intent,
                    'similarity' => $score
                ];
            }
        }

        return $bestMatch;
    }

    /**
     * Tính similarity giữa hai văn bản
     */
    public function calculateSimilarity(string $text1, string $text2): float
    {
        $tokens1 = $this->preprocessAndTokenize($text1);
        $tokens2 = $this->preprocessAndTokenize($text2);

        // Sử dụng cả Cosine và Jaccard similarity
        $cosineScore = $this->cosineSimilarity->similarity($tokens1, $tokens2);
        $jaccardScore = $this->jaccardIndex->similarity($tokens1, $tokens2);

        // Kết hợp cả hai scores
        return ($cosineScore + $jaccardScore) / 2;
    }

    /**
     * Tiền xử lý và tokenize văn bản
     */
    private function preprocessAndTokenize(string $text): array
    {
        $text = mb_strtolower(trim($text), 'UTF-8');
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', $text);

        $tokens = $this->tokenizer->tokenize($text);

        // Stemming
        return array_map(function($token) {
            return $this->stemmer->stem($token);
        }, $tokens);
    }

    /**
     * Phát hiện entities trong văn bản
     */
    public function extractEntities(string $text): array
    {
        $entities = [];

        // Phát hiện số điện thoại
        preg_match_all('/\b(0[3|5|7|8|9])+([0-9]{8})\b/', $text, $phoneMatches);
        if (!empty($phoneMatches[0])) {
            $entities['phones'] = $phoneMatches[0];
        }

        // Phát hiện email
        preg_match_all('/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\b/i', $text, $emailMatches);
        if (!empty($emailMatches[0])) {
            $entities['emails'] = $emailMatches[0];
        }

        // Phát hiện địa chỉ
        $addressKeywords = ['hà nội', 'hồ chí minh', 'đà nẵng', 'đường', 'phố', 'quận', 'huyện'];
        foreach ($addressKeywords as $keyword) {
            if (stripos($text, $keyword) !== false) {
                $entities['address'] = true;
                break;
            }
        }

        return $entities;
    }

    /**
     * Phân tích phụ thuộc cú pháp đơn giản
     */
    public function analyzeDependencies(string $text): array
    {
        $tokens = $this->tokenizer->tokenize($text);
        $dependencies = [];

        // Simple dependency analysis (trong thực tế cần dùng thư viện NLP)
        $questionWords = ['ai', 'cái gì', 'ở đâu', 'khi nào', 'tại sao', 'thế nào'];
        $actionWords = ['mua', 'bán', 'đặt', 'hỏi', 'tìm', 'kiểm tra'];

        foreach ($tokens as $token) {
            if (in_array($token, $questionWords)) {
                $dependencies['is_question'] = true;
            }
            if (in_array($token, $actionWords)) {
                $dependencies['action'] = $token;
            }
        }

        return $dependencies;
    }
}
