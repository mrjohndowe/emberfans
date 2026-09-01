<?php

namespace Database\Seeders;

use App\Models\{CreatorProfile, Post, User};
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create(['name' => 'Ember Admin', 'username' => 'emberadmin', 'email' => 'admin@emberfans.test', 'role' => 'admin']);
        collect([
            ['Maya Ember', 'mayaember', 'maya@emberfans.test', 'Late-night stories, studio sessions, and warm little moments.', 7.99],
            ['Nova Lane', 'novalane', 'nova@emberfans.test', 'Music, movement, and the odd spark of mischief.', 9.99],
            ['River Ash', 'riverash', 'river@emberfans.test', 'Independent artist sharing process, sound, and visual experiments.', 5.99],
        ])->each(function (array $creator): void {
            $user = User::factory()->create(['name' => $creator[0], 'username' => $creator[1], 'email' => $creator[2], 'role' => 'creator']);
            CreatorProfile::create(['user_id' => $user->id, 'display_name' => $creator[0], 'bio' => $creator[3], 'monthly_price' => $creator[4], 'is_verified' => true]);
            Post::create(['creator_id' => $user->id, 'body' => 'Welcome to my EmberFans page. I’m glad you found your way here.', 'media_type' => 'text', 'published_at' => now()->subHours(random_int(1, 48))]);
            Post::create(['creator_id' => $user->id, 'body' => 'A little extra for the people who want to support the work.', 'media_type' => 'text', 'is_locked' => true, 'unlock_price' => 3.50, 'published_at' => now()->subHours(random_int(1, 48))]);
        });
    }
}
