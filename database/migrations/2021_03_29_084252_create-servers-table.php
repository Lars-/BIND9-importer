<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->string('db_hostname');
            $table->string('db_username');
            $table->string('db_password');
            $table->string('db_database');
            $table->string('db_table');
            $table->string('db_domain_name_column');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('servers');
    }
}
