<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('creator_profiles', function (Blueprint $table) {
            $table->id(); $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('display_name'); $table->text('bio')->nullable(); $table->string('avatar_path')->nullable(); $table->string('banner_path')->nullable();
            $table->decimal('monthly_price', 8, 2)->default(0); $table->boolean('is_verified')->default(false); $table->boolean('is_accepting_tips')->default(true); $table->timestamps();
        });
        Schema::create('posts', function (Blueprint $table) {
            $table->id(); $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete(); $table->text('body')->nullable();
            $table->enum('media_type', ['text', 'image', 'audio', 'video'])->default('text'); $table->string('media_path')->nullable(); $table->string('thumbnail_path')->nullable();
            $table->boolean('is_locked')->default(false); $table->decimal('unlock_price', 8, 2)->nullable(); $table->timestamp('published_at')->nullable(); $table->timestamps();
        });
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id(); $table->foreignId('subscriber_id')->constrained('users')->cascadeOnDelete(); $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->enum('provider', ['stripe', 'paypal', 'manual']); $table->string('provider_reference')->nullable(); $table->decimal('amount', 8, 2); $table->string('status')->default('pending'); $table->timestamp('starts_at')->nullable(); $table->timestamp('ends_at')->nullable(); $table->timestamps(); $table->unique(['subscriber_id', 'creator_id']);
        });
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete(); $table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('type', ['subscription', 'unlock', 'tip']); $table->enum('provider', ['stripe', 'paypal', 'manual']); $table->string('provider_reference')->nullable(); $table->decimal('amount', 8, 2); $table->string('currency', 3)->default('USD'); $table->string('status')->default('pending'); $table->json('metadata')->nullable(); $table->timestamps();
        });
        Schema::create('content_unlocks', function (Blueprint $table) { $table->id(); $table->foreignId('post_id')->constrained()->cascadeOnDelete(); $table->foreignId('user_id')->constrained()->cascadeOnDelete(); $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete(); $table->decimal('amount', 8, 2); $table->timestamps(); $table->unique(['post_id', 'user_id']); });
        Schema::create('tips', function (Blueprint $table) { $table->id(); $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete(); $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete(); $table->decimal('amount',8,2); $table->text('message')->nullable(); $table->enum('provider',['stripe','paypal','manual']); $table->string('status')->default('pending'); $table->timestamps(); });
        Schema::create('likes', function (Blueprint $table) { $table->id(); $table->foreignId('post_id')->constrained()->cascadeOnDelete(); $table->foreignId('user_id')->constrained()->cascadeOnDelete(); $table->timestamps(); $table->unique(['post_id','user_id']); });
        Schema::create('comments', function (Blueprint $table) { $table->id(); $table->foreignId('post_id')->constrained()->cascadeOnDelete(); $table->foreignId('user_id')->constrained()->cascadeOnDelete(); $table->text('body'); $table->timestamps(); });
        Schema::create('messages', function (Blueprint $table) { $table->id(); $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete(); $table->foreignId('recipient_id')->constrained('users')->cascadeOnDelete(); $table->text('body')->nullable(); $table->string('media_path')->nullable(); $table->timestamp('read_at')->nullable(); $table->timestamps(); });
        Schema::create('user_notifications', function (Blueprint $table) { $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete(); $table->string('type'); $table->string('title'); $table->text('body')->nullable(); $table->json('data')->nullable(); $table->timestamp('read_at')->nullable(); $table->timestamps(); });
    }
    public function down(): void { Schema::dropIfExists('user_notifications'); Schema::dropIfExists('messages'); Schema::dropIfExists('comments'); Schema::dropIfExists('likes'); Schema::dropIfExists('tips'); Schema::dropIfExists('content_unlocks'); Schema::dropIfExists('payments'); Schema::dropIfExists('subscriptions'); Schema::dropIfExists('posts'); Schema::dropIfExists('creator_profiles'); }
};
