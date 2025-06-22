<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('emp_id')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('asset_type_id');
            $table->string('asset_name');
            $table->string('serial_no')->unique();
            $table->enum('status', ['1', '2', '3', '4', '5']);
            $table->date('purchase_date');
            $table->date('guarantee_date')->nullable();
            $table->decimal('asset_price', 16, 2);
            $table->integer('is_deleted')->default(0);
            $table->text('description')->nullable();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('assets');
    }
}
