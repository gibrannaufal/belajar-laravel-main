<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableMasterPromo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_promo', function (Blueprint $table) {
            $table->increments('id_promo');
            $table->string('nama', 30);
            $table->string('type', 15)->default('0');
            $table->integer('diskon')->nullable();
            $table->integer('nominal')->nullable();
            $table->integer('kadaluarsa')->nullable();
            $table->text('syarat_ketentuan');
            $table->string('foto', 255)->nullable();
            $table->integer('created_by')->default(1);
            $table->tinyInteger('is_deleted')->default(0);
            $table->index('created_by');
            $table->integer('updated_by')->default(0);
            $table->date('deleted_at')->nullable();
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
        Schema::dropIfExists('m_promo');
    }
}
