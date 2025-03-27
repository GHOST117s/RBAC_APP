<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // Create a few posts for each user
            for ($i = 1; $i <= 3; $i++) {
                Post::create([
                    'title' => "Post {$i} by {$user->name}",
                    'content' => "This is the content of post {$i} created by {$user->name}. This is sample content for seeding the database.",
                    'status' => ['draft', 'published', 'archived'][rand(0, 2)],
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
