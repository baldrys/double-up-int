<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGithubRepositories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('github_repositories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('github_id')->unique();

            $table->Integer('github_user_id')
                ->nullable(true)
                ->unsigned();
            $table->foreign('github_user_id')
                ->references('id')->on('github_users')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('private');
            $table->string('language');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('github_repositories');
    }
}
