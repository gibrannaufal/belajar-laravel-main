<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MDiskonMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_diskon', function (Blueprint $table) {
            $table->increments('id_diskon');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_promo');
            $table->tinyInteger('status')->default(0);
            $table->Integer('created_by')->default(1);
            $table->integer('updated_by')->default(0);
            $table->tinyInteger('is_deleted')->default(0);
            $table->timestamps();
            $table->date('deleted_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_diskon');
    }
}
