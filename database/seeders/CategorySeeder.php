<?php
namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Web Development',
                'description' => 'Learn to build websites and web applications',
                'color' => '#3b82f6',
                'icon' => '💻',
            ],
            [
                'name' => 'Mobile Development',
                'description' => 'Create mobile apps for iOS and Android',
                'color' => '#10b981',
                'icon' => '📱',
            ],
            [
                'name' => 'Data Science',
                'description' => 'Analyze data and build predictive models',
                'color' => '#f59e0b',
                'icon' => '📊',
            ],
            [
                'name' => 'Design',
                'description' => 'UI/UX design and graphic design principles',
                'color' => '#ec4899',
                'icon' => '🎨',
            ],
            [
                'name' => 'Business',
                'description' => 'Entrepreneurship and business management',
                'color' => '#8b5cf6',
                'icon' => '💼',
            ],
            [
                'name' => 'Marketing',
                'description' => 'Digital marketing and growth strategies',
                'color' => '#ef4444',
                'icon' => '📢',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}