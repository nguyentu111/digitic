<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->mediumText('email');
            $table->string('provider', 45)->nullable();
            $table->integer('provider_id')->nullable();
            $table->string('access_token', 405)->nullable();
            $table->string('session_token', 405)->nullable();
        });
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name',200);
            $table->string('brand',50);
            $table->text("description");
            $table->string("slug",255);
            $table->softDeletes($column = 'deleted_at');
            $table->timestamps();
        });
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->string("name",50);
            $table->unsignedBigInteger("product_id");
            $table->foreign('product_id')->references('id')->on('products');
        });
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string("name",50);
            $table->string("hex_value",10);
            $table->timestamps();
        });
        Schema::create('pictures', function (Blueprint $table) {
            $table->id();
            $table->text("source");
        });
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string("name",50);
            $table->unsignedBigInteger("picture_id");
            $table->foreign('picture_id')->references('id')->on('pictures');
        });
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string("name",50);
            $table->unsignedBigInteger("collection_id")->nullable();
            $table->foreign('collection_id')->references('id')->on('collections');
        });
        Schema::create('product_tags', function (Blueprint $table) {
            $table->unsignedBigInteger("tag_id");
            $table->foreign('tag_id')->references('id')->on('tags');
            $table->unsignedBigInteger("product_id");
            $table->foreign('product_id')->references('id')->on('products');
            $table->primary(['tag_id','product_id']);
        });
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("product_id");
            $table->foreign('product_id')->references('id')->on('products');
            $table->unsignedBigInteger("color_id");
            $table->foreign('color_id')->references('id')->on('colors');
            $table->unsignedBigInteger("picture_id");
            $table->foreign('picture_id')->references('id')->on('pictures');
            $table->decimal('regular_price',15,2);
            $table->integer('quantity');
            $table->timestamps();
        });
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->timestamp('from_time')->nullable();
            $table->timestamp('to_time')->nullable();
            $table->decimal('sale_price',15,2);
            $table->unsignedBigInteger("product_detail_id");
            $table->foreign('product_detail_id')->references('id')->on('product_details');
            $table->timestamps();
        });
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('paid');
            $table->unsignedBigInteger("user_id");
            $table->foreign('user_id')->references('id')->on('users');
            $table->enum('status',['canceled','pendding','shipping','shipped']);
            $table->timestamp('shipped_at')->nullable();
            $table->timestamps();
        });
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("order_id");
            $table->foreign('order_id')->references('id')->on('orders');
            $table->unsignedBigInteger("product_detail_id");
            $table->foreign('product_detail_id')->references('id')->on('product_details');
            $table->decimal('regular_price',15,2);
            $table->decimal('sale_price',15,2);
            $table->unsignedBigInteger('review_id')->nullable();
            $table->integer('quantity');
        });
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("parent_id")->nullable();
            $table->string('title',255);
            $table->text('content');
            $table->enum('rating',[1,2,3,4,5]);
            $table->unsignedBigInteger("user_id");
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger("order_detail_id");
            $table->foreign('order_detail_id')->references('id')->on('order_details');
            $table->timestamps();
        });
        Schema::table('order_details',function (Blueprint $table){
            $table->foreign('review_id')->references('id')->on('reviews');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->string("slug",255)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('products');
        // Schema::dropIfExists('sizes');
        // Schema::dropIfExists('collections');
        // Schema::dropIfExists('tags');
        // Schema::dropIfExists('product_tags');
        // Schema::dropIfExists('colors');
        // Schema::dropIfExists('pictures');
        // Schema::dropIfExists('product_details');
        // Schema::dropIfExists('sales');
        // Schema::dropIfExists('orders');
        // Schema::dropIfExists('order_details');
        // Schema::dropIfExists('reviews');
    }
}
