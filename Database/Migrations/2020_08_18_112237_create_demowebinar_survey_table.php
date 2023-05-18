<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemowebinarSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('demowebinar_survey');
        Schema::create('demowebinar_survey', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('webinar_id')->nullable()->unsigned()->default(NULL)->references('id')->on('demowebinars')->on_delete('cascade');
            $table->string('type');
            $table->tinyInteger('computed_type')->unsigned()->storedAs("CASE WHEN (type='poll_with_one_answer') THEN 1 WHEN (type='poll_with_multiple_answers') THEN 2 WHEN (type='question_for_short_answer') THEN 3 WHEN (type='question_for_long_answer') THEN 4 ELSE 0 END");
            $table->text('question');
            $table->timestamp('start_time')->nullable()->default(NULL);
            $table->timestamp('end_time')->nullable()->default(NULL);
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
        Schema::dropIfExists('demowebinar_survey');
    }
}
