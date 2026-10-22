<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('job_circulars', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        $table->string('title');            // 1. Job Title
        $table->string('job_type');         // 2. Job Type
        $table->integer('openings');        // 3. Openings
        $table->string('educations');       // 4. Educations
        $table->string('category');         // 5. Category
        $table->text('skills_needed');      // 6. Skills Needed
        $table->text('description');        // 7. Short Description
        $table->text('job_details');        // 8. Details about the job
        
        $table->string('company_name'); 
        $table->string('image')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_circulars');
    }
};
