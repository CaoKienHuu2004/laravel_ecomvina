<?php
// database/seeders/AITrainingDataSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AIIntent;
use App\Models\AITrainingData;
use App\Models\AIResponse;

class AITrainingDataSeeder extends Seeder
{
    public function run()
    {
        $intents = [
            'greeting' => ['Xin chào', 'Chào bạn', 'Hello', 'Hi', 'Chào buổi sáng'],
            'goodbye' => ['Tạm biệt', 'Bye', 'Hẹn gặp lại', 'Tôi đi đây'],
            'thanks' => ['Cảm ơn', 'Thanks', 'Cám ơn', 'Thank you'],
            'weather' => ['Thời tiết', 'Thời tiết hôm nay', 'Trời hôm nay thế nào'],
            'time' => ['Mấy giờ rồi', 'Thời gian', 'Giờ hiện tại']
        ];

        $responses = [
            'greeting' => ['Xin chào! Tôi có thể giúp gì cho bạn?', 'Chào bạn! Rất vui được trò chuyện.'],
            'goodbye' => ['Tạm biệt! Hẹn gặp lại bạn.', 'Bye bye! Chúc bạn ngày tốt lành.'],
            'thanks' => ['Không có gì! Rất vui được giúp đỡ.', 'Luôn sẵn lòng hỗ trợ bạn!'],
            'weather' => ['Hiện tôi chưa có dữ liệu thời tiết.'],
            'time' => ['Hiện tại là: {time}', 'Bây giờ là: {time}']
        ];

        foreach ($intents as $name => $samples) {
            $intent = AIIntent::create(['name' => $name, 'description' => ucfirst($name) . ' intent']);

            foreach ($samples as $sample) {
                AITrainingData::create([
                    'intent_id' => $intent->id,
                    'text' => $sample
                ]);
            }

            foreach ($responses[$name] as $response) {
                AIResponse::create([
                    'intent_id' => $intent->id,
                    'response' => $response
                ]);
            }
        }
    }
}
