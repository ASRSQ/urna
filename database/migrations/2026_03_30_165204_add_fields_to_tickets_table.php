<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('leader_name')->after('number');
            $table->string('leader_photo')->nullable();

            $table->string('vice_name')->nullable();
            $table->string('vice_photo')->nullable();

            $table->string('party')->nullable();

            // opcional: remover campo antigo
            $table->dropColumn('name');
        });
    }

    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('name')->nullable();

            $table->dropColumn([
                'leader_name',
                'leader_photo',
                'vice_name',
                'vice_photo',
                'party'
            ]);
        });
    }
};