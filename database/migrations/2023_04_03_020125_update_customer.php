<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCustomer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_customer', function (Blueprint $table) {
            $table->string('phone_number', 25)
            ->default(null)
            ->comment('Fill with phone number of customer')
            ->nullable();
            $table->date('date_of_birth')
            ->default(null)
            ->comment('Fill with date of birth of customer')
            ->nullable();
            $table->string('foto',100)
            ->default(null)
            ->comment('Fill with directory file of customer')
            ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_customer', function (Blueprint $table) {
            $table->dropColumn('phone_number');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('foto');
        });
    }
}
