<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGithubIssues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('github_issues', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('github_id')->unique();

            $table->Integer('repository_id')
                ->nullable(true)
                ->unsigned();
            $table->foreign('repository_id')
                ->references('id')->on('github_repositories')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->string('title');
            $table->integer('number');
            $table->string('state');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('github_issues');
    }
}
