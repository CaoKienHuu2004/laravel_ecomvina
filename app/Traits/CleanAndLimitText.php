<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait CleanAndLimitText
{
    /**
     * Loại bỏ thẻ HTML, emoji và ký tự đặc biệt, cắt chuỗi về độ dài giới hạn với dấu "..."
     *
     * @param string $text
     * @param int $limit
     * @return string
     */
    private function cleanAndLimitText(string $text, int $limit = 76): string
    {
        // Bỏ thẻ HTML
        $textWithoutHtml = strip_tags($text);

        // Loại bỏ ký tự không phải chữ, số, dấu câu, khoảng trắng (hỗ trợ Unicode)
        $cleanText = preg_replace('/[^\p{L}\p{N}\p{P}\p{Z}]/u', '', $textWithoutHtml);

        // Cắt chuỗi nếu vượt quá giới hạn
        if (mb_strlen($cleanText) > $limit) {
            return mb_substr($cleanText, 0, $limit) . '...';
        }
        return $cleanText;
    }
    private function cleanAndLimitTextPosts(string $text, int $limit = 160): string
    {
        // 1. Bỏ thẻ HTML
        $textWithoutHtml = strip_tags($text);

        // 2. Loại bỏ emoji & ký tự đặc biệt nguy hiểm (giữ lại chữ, số, dấu câu, khoảng trắng)
        $cleanText = preg_replace('/[^\p{L}\p{N}\p{P}\s]/u', '', $textWithoutHtml);

        // 3. Gộp nhiều khoảng trắng thành 1
        $cleanText = preg_replace('/\s+/', ' ', $cleanText);

        // 4. Trim bỏ khoảng trắng đầu/cuối
        $cleanText = trim($cleanText);

        // 5. Cắt chuỗi nếu quá dài
        if (mb_strlen($cleanText) > $limit) {
            return mb_substr($cleanText, 0, $limit) . '...';
        }

        return $cleanText;
    }
}
