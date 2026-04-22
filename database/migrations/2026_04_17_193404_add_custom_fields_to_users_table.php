<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('user_type')->default('candidate');
        $table->string('unique_id')->unique()->nullable();
        $table->string('title')->nullable();
        $table->text('summary')->nullable();
        $table->string('phone')->nullable();
        $table->string('address')->nullable();
        $table->text('skills')->nullable();
        $table->string('profile_photo')->nullable();
        $table->string('cv_path')->nullable();
        $table->string('github_url')->nullable();
        $table->string('linkedin_url')->nullable();
        $table->string('portfolio_url')->nullable();
        $table->date('dob')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
