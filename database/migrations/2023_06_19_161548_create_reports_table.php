<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ip')->nullable();
            $table->string('system')->nullable();
            $table->string('keys')->nullable();
            $table->string('platform')->nullable();
            $table->longText('description')->nullable();
            $table->string('file')->nullable();
            $table->string('status')->default('Rascunho');
            $table->integer('views', false, true)->default(0);
            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });

        DB::statement("
        CREATE OR REPLACE VIEW `reports_view` AS
        SELECT r.id, r.name, r.system, r.platform, r.keys, r.status, r.views, r.user_id,
        (CASE WHEN u.alias IS NULL THEN u.name ELSE u.alias END)as author,
        r.created_at
        FROM reports as r
        LEFT JOIN users as u ON u.id = r.user_id
        WHERE r.deleted_at IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
        DB::statement("DROP VIEW reports_view");
    }
}
