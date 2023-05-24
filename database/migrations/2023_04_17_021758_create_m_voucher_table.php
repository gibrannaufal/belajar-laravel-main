<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_voucher', function (Blueprint $table) {
            $table->increments('id_voucher');
            $table->unsignedBigInteger('id_promo');
            $table->unsignedBigInteger('id_customer');
            $table->integer('nominal');
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->Integer('jumlah')->default(0);
            $table->integer('status')->default(0);
            $table->text('catatan')->nullable();
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
        Schema::dropIfExists('m_voucher');
    }
}
