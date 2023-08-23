<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterToolsViewAddTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
        CREATE OR REPLACE VIEW `tools_view` AS
        SELECT t.id, t.name, t.description,
        (SELECT GROUP_CONCAT(tt.text SEPARATOR ', ') FROM tool_tags AS tt WHERE tt.deleted_at IS NULL AND tt.tool_id = t.id) as tags
        FROM tools as t
        WHERE t.deleted_at IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("
        CREATE OR REPLACE VIEW `tools_view` AS
        SELECT t.id, t.name, t.description
        FROM tools as t
        WHERE t.deleted_at IS NULL
        ");
    }
}
