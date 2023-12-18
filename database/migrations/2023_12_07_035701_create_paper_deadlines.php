<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paper_deadlines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('CCF');
            $table->string('date');
            $table->datetime('submission_deadline');
            $table->integer('countdown');
            $table->string('place');
            $table->string('website');
        });
    }

    public function down()
    {
        Schema::dropIfExists('paper_deadlines');
    }
};
