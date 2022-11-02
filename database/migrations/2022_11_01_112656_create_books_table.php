<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', 255);
            $table->string('cover_url', 766);
            $table->string('isbn', 50);
            $table->string('revision_no', 100)->nullable();
            $table->string('publisher', 150);
            $table->timestamp('published_date')->nullable();
            $table->integer('number_of_pages')->nullable();
            $table->enum('status', ['checked_in', 'checked_out'])->default('checked_in');
            $table->foreignUuid('genre_id')->constrained('genres')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
};
