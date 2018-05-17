<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAliasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('alias')) {
            Schema::create('alias', function (Blueprint $table) {
                $table->string('address', 255)->unique();
                $table->text('goto')->default('');
                $table->string('name', 255)->default('');
                $table->text('moderators')->default('');
                $table->string('accesspolicy', 30)->default('');
                $table->string('domain', 255)->default('');
                $table->boolean('islist')->default(false);
                $table->boolean('is_alias')->default(false);
                $table->timestamp('created');
                $table->timestamp('modified');
                $table->timestamp('expired');
                $table->boolean('active')->default(true);

                $table->primary(['address']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('aliases');
    }
}
