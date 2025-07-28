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
        Schema::create('api_connections', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Tên để dễ nhận biết, ví dụ: "Product API", "Order API"
            $table->string('api_url');
            $table->text('api_key'); // Dùng kiểu text để lưu trữ key đã được mã hóa (dài hơn)
            $table->boolean('is_active')->default(true); // Cờ để bật/tắt kết nối
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_connections');
    }
};
