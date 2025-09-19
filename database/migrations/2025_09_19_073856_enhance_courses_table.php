<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('slug')->unique()->after('title');
            $table->text('short_description')->nullable()->after('description');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null')->after('user_id');
            $table->string('thumbnail')->nullable()->after('category_id');
            $table->decimal('price', 10, 2)->default(0)->after('thumbnail');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft')->after('price');
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner')->after('status');
            $table->integer('duration_hours')->nullable()->after('level');
            $table->json('requirements')->nullable()->after('duration_hours');
            $table->json('what_you_learn')->nullable()->after('requirements');
            $table->timestamp('published_at')->nullable()->after('what_you_learn');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn([
                'slug', 'short_description', 'category_id', 'thumbnail', 'price', 
                'status', 'level', 'duration_hours', 'requirements', 'what_you_learn', 'published_at'
            ]);
        });
    }
};