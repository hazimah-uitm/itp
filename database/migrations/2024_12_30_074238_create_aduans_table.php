<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAduansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aduans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('aduan_ict_ticket')->nullable();
            $table->string('complainent_name')->nullable();
            $table->string('complainent_id')->nullable();
            $table->string('complainent_category')->nullable();
            $table->string('aduan_category')->nullable();
            $table->string('category')->nullable();
            $table->string('aduan_subcategory')->nullable();
            $table->string('campus')->nullable();
            $table->text('location')->nullable();
            $table->text('aduan_details')->nullable();
            $table->string('aduan_status')->nullable();
            $table->string('aduan_type')->nullable();
            $table->string('staff_duty')->nullable();
            $table->text('remark_staff_duty')->nullable();
            $table->date('date_applied')->nullable();
            $table->time('time_applied')->nullable();
            $table->date('date_completed')->nullable();
            $table->time('time_completed')->nullable();
            $table->string('response_time')->nullable();
            $table->integer('response_days')->nullable();
            $table->integer('rating')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('aduans');
    }
}
