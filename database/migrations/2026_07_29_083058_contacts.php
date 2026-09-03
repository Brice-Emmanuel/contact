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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('email')->nullable(); 
            $table->string('phone', 100);        
            $table->string('adress')->nullable();
            $table->enum('group', ['Famille', 'Amis', 'Collègue', 'Autres']);
            $table->boolean('favoris')->default(false);
            $table->date('Birthday')->nullable();
            $table->string('notes')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // --- UNICITÉ PAR UTILISATEUR ---
            // L'e-mail doit être unique pour un même utilisateur
            $table->unique(['user_id', 'email']);
            // Le numéro de téléphone doit être unique pour un même utilisateur
            $table->unique(['user_id', 'phone']);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};