<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTableAorganizationsAddSubordination extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->foreignId('organization_id')
                ->nullable()
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        DB::statement("
        CREATE OR REPLACE VIEW `organizations_view` AS
        SELECT o.id, o.alias_name, o.code, o.telephone, o.email, CONCAT(o.city, '-', o.state) as city, os.alias_name as subordination
        FROM organizations as o
        LEFT JOIN organizations as os ON os.id = o.organization_id AND os.deleted_at IS NULL
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
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('organization_id');
        });

        DB::statement("
        CREATE OR REPLACE VIEW `organizations_view` AS
        SELECT o.id, o.alias_name, o.code, o.telephone, o.email, CONCAT(o.city, '-', o.state) as city
        FROM organizations as o
        WHERE o.deleted_at IS NULL
        ");
    }
}
