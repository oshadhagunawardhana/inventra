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
        Schema::table('users', function (Blueprint $table) {
            $table->string('wso2_issuer')->nullable();
            $table->string('wso2_subject')->nullable();
            $table->unique(['wso2_issuer', 'wso2_subject']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['wso2_issuer', 'wso2_subject']);
            $table->dropColumn(['wso2_issuer', 'wso2_subject']);
        });
    }
};
