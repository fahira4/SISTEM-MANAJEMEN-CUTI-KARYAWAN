<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->enum('leave_type', ['tahunan', 'sakit']);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days');
            $table->text('reason'); 
            
            $table->string('attachment_path')->nullable();
            
            $table->string('address_during_leave')->nullable();
            $table->string('emergency_contact')->nullable();

            $table->enum('status', [
                'pending',
                'approved_by_leader', 
                'rejected_by_leader',
                'approved_by_hrd',
                'rejected_by_hrd',
                'cancelled'
            ])->default('pending');

            $table->foreignId('leader_approver_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            
            $table->timestamp('leader_approval_at')->nullable();
            
            $table->text('leader_approval_note')->nullable(); 
            $table->text('leader_rejection_notes')->nullable();
            $table->foreignId('hrd_approver_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->timestamp('hrd_approval_at')->nullable();
            
            $table->text('hrd_approval_note')->nullable();
            $table->text('hrd_rejection_notes')->nullable();

            $table->text('cancellation_reason')->nullable();
            
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('leave_type');
            $table->index('start_date');
            $table->index('end_date');
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_applications');
    }
};