<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('subject');
            $table->text('body');
            $table->enum('status', ['open','pending','closed'])->default('open');
            $table->string('category')->nullable();
            $table->text('note')->nullable();
            $table->float('confidence')->nullable(); // 0..1
            $table->text('explanation')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIfExists('tickets');
        });
    }
};
