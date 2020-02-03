<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdminMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('base_menu', function (Blueprint $table) {
            $table->increments('menu_id');
            $table->string('menu_name', 100)->nullable($value = false);
            $table->string('menu_trans', 100)->nullable($value = false)->comment('hold translate prefix eg: edit_menu');
            $table->longText('menu_desc');
            $table->integer('menu_sort')->unsigned()->comment('sorting menu asc or desc order');
            $table->string('menu_icon', 30);
            $table->string('menu_url', 100)->nullable($value = false)->comment('url');
            $table->integer('parent_id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->useCurrent();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('base_menu');
    }
}
