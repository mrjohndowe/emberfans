<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('username')->nullable()->after('name');
            });
        }

        DB::table('users')->whereNull('username')->orderBy('id')->get()->each(function (object $user): void {
            DB::table('users')->where('id', $user->id)->update([
                'username' => 'member-' . $user->id,
            ]);
        });

        $hasUsernameIndex = collect(Schema::getIndexes('users'))
            ->contains(fn (array $index): bool => $index['name'] === 'users_username_unique');

        if (! $hasUsernameIndex) {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('username', 'users_username_unique');
            });
        }
    }

    public function down(): void
    {
        $hasUsernameIndex = collect(Schema::getIndexes('users'))
            ->contains(fn (array $index): bool => $index['name'] === 'users_username_unique');

        Schema::table('users', function (Blueprint $table) use ($hasUsernameIndex) {
            if ($hasUsernameIndex) {
                $table->dropUnique('users_username_unique');
            }

            $table->dropColumn('username');
        });
    }
};
