<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_ai_training_data_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAiTrainingDataTable extends Migration
{
    public function up()
    {
        Schema::create('ai_intents', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('ai_training_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intent_id')->constrained('ai_intents');
            $table->text('text');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('ai_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intent_id')->constrained('ai_intents');
            $table->text('response');
            $table->integer('priority')->default(1);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('ai_conversations', function (Blueprint $table) {
            $table->id();
            $table->text('user_input');
            $table->text('ai_response');
            $table->string('intent')->nullable();
            $table->string('sentiment')->nullable();
            $table->float('confidence')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ai_conversations');
        Schema::dropIfExists('ai_responses');
        Schema::dropIfExists('ai_training_data');
        Schema::dropIfExists('ai_intents');
    }
}
