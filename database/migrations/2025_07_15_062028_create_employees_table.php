<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            $table->string('first_name', 50);
            $table->string('last_name', 50)->nullable();
            $table->string('full_name', 100);

            $table->string('email', 100)->unique();
            $table->string('phone', 20);

            $table->string('position', 50);

            $table->date('date_of_birth')->nullable();
            $table->date('date_joined');

            $table->text('address')->nullable();
            $table->enum('status', ['active', 'inactive', 'on_leave'])
                ->default('active');

            $table->string('photo_path', 255)->nullable();

            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments')
                ->onDelete('set null');

            $table->foreignId('role_id')
                ->constrained('roles')
                ->onDelete('restrict');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
