<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code')->nullable();
            $table->string('reference')->nullable();
            $table->string('spindle');
            $table->longText('situation')->nullable();
            $table->longText('type')->nullable(); //exploration / systematic
            $table->longText('mission')->nullable();
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->string('classification')->nullable(); // protection / exploration / attack
            $table->foreignId('step_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->longText('execution')->nullable();
            $table->longText('instructions')->nullable();
            $table->longText('logistics')->nullable();
            $table->longText('control')->nullable();
            $table->string('file')->nullable();
            $table->unsignedBigInteger('creator');
            $table->unsignedBigInteger('editor');
            $table->foreign('creator')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('editor')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });

        DB::statement("
        CREATE OR REPLACE VIEW `operations_view` AS
        SELECT o.id, o.code, o.title, o.type, o.classification, o.reference, s.name as step, o.start, o.end
        FROM operations as o
        LEFT JOIN steps as s ON s.id = o.step_id AND s.deleted_at IS NULL
        WHERE o.deleted_at IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operations');
        DB::statement("DROP VIEW operations_view");
    }
}
