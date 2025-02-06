<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToKonateliaTable extends Migration
{
    public function up()
    {
        Schema::table('konatelia', function (Blueprint $table) {
            $table->softDeletes(); // Pridáme stĺpec deleted_at
        });
    }

    public function down()
    {
        Schema::table('konatelia', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
