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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();
            $table->string('tracking_number')->unique();
            $table->decimal('width', 8, 2);
            $table->decimal('height', 8, 2);
            $table->decimal('length', 8, 2);
            $table->decimal('weight', 8, 2);
            $table->text('contents_description');
            $table->enum('status', [
                'ready_to_send',
                'pending_invoice_review',
                'needs_review',
                'invoice_approved',
                'ship_requested',
                'shipped',
                'ready_for_pickup',
                'delivered',
            ])->default('ready_to_send');
            $table->timestamp('received_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
