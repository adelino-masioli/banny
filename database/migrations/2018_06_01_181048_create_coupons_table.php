<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->text('url')->nullable();
            $table->text('company')->nullable();
            $table->text('document')->nullable();
            $table->text('address')->nullable();
            $table->text('datetime')->nullable();
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->text('key_access')->nullable();
            $table->text('protocol')->nullable();
            $table->text('payment_method')->nullable();
            $table->decimal('total', 10,2);
            $table->decimal('discount', 10,2);
            $table->integer('status')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('coupons');
    }
}
