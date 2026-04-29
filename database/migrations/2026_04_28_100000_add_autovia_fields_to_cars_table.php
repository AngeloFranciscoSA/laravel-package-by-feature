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
        Schema::table('cars', function (Blueprint $table) {
            $table->integer('km')->default(0)->after('year');
            $table->string('fuel', 20)->default('Flex Fuel')->after('km');
            $table->string('transmission', 10)->default('Automatic')->after('fuel');
            $table->string('city')->nullable()->after('color');
            $table->string('state', 2)->nullable()->after('city');
            $table->integer('views')->default(0)->after('state');
            $table->boolean('featured')->default(false)->after('views');
            $table->string('badge', 20)->nullable()->after('featured');
            $table->unsignedBigInteger('seller_id')->nullable()->after('badge');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn([
                'km',
                'fuel',
                'transmission',
                'city',
                'state',
                'views',
                'featured',
                'badge',
                'seller_id',
            ]);
        });
    }
};