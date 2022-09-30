<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('si',20)->nullable();
            $table->bigInteger('module_id')->nullable();
            $table->bigInteger('chapter_id')->nullable();
            $table->string('question_pattern',100)->nullable();
            $table->text('question_title')->nullable();
            $table->string('question_image',100)->nullable();
            $table->text('option_1')->nullable();
            $table->string('option_1_image',100)->nullable();
            $table->text('option_2')->nullable();
            $table->string('option_2_image',100)->nullable();
            $table->text('option_3')->nullable();
            $table->string('option_3_image',100)->nullable();
            $table->text('option_4')->nullable();
            $table->string('option_4_image',100)->nullable();
            $table->text('answer')->nullable();
            $table->string('answer_image',100)->nullable();
            $table->text('part_66_reference')->nullable();
            $table->text('training_note_reference')->nullable();
            $table->text('prepared_by')->nullable();
            $table->text('verified_by')->nullable();
            $table->text('level')->nullable();
            $table->string('creator',20)->nullable();
            $table->string('slug',50)->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
