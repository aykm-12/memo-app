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
        Schema::table('notes', function (Blueprint $table) {
            // category_id を nullable に変更
            //Schemaファサードのtableメソッドを使うことで既存のテーブルを更新できる
            $table->foreignId('category_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            // ロールバックした時に元に戻す（NULLを許可しない設定に戻す）
            // ※既存のデータにNULLがあるとエラーになるので注意
            $table->foreignId('category_id')->nullable(false)->change();
        });
    }
};
