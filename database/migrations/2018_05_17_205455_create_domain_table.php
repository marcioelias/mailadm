<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDomainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('domain')) {
            Schema::create('domain', function (Blueprint $table) {
                $table->string('domain', 255)->unique();
                $table->text('description')->default('');
                $table->text('disclaimer')->default('');
                $table->bigInteger('aliases')->default(0);
                $table->bigInteger('mailboxes')->default(0);
                $table->bigInteger('maxquota')->default(0);
                $table->bigInteger('quota')->default(0);
                $table->string('transport', 255)->default('dovecot');
                $table->text('settings')->default('');
                $table->smallInteger('backupmx')->default(0);
                $table->timestamp('created');
                $table->timestamp('modified');
                $table->timestamp('expired');
                $table->boolean('active')->default(true);

                $table->primary(['domain']);
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
        //Schema::dropIfExists('domain');
    }
}
