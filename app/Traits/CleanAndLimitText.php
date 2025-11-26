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
}
