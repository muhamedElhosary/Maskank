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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('images');
            $table->text('description');
            $table->decimal('price', $precision = 8, $scale = 2);
            $table->integer('size');
            $table->string('purpose');
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->string('region');
            $table->string('city');
            $table->string('floor');
            $table->string('Condition');
            $table->boolean('status')->default(0);
            $table->boolean('booked')->default(0);
            $table->foreignId('owner_id')->nullable()
            ->constrained('owners','id')
            ->nullOnDelete();
            $table->foreignId('admin_id')->nullable()
             ->constrained('admins','id')
             ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
