<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        DB::statement("
        CREATE OR REPLACE VIEW `visitors_view` AS
        SELECT v.id, v.url, v.ip, v.method, v.languages, v.useragent, v.platform, v.browser, v.visitor_type, v.visitor_id, v.created_at, u.name, v.request
        FROM shetabit_visits  as v
        LEFT JOIN users as u ON u.id = v.visitor_id
        WHERE DATE_FORMAT(v.created_at, '%Y-%m-%d') = CURDATE()
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('
        CREATE OR REPLACE VIEW `visitors_view` AS
        SELECT v.id, v.url, v.method, v.created_at
        FROM shetabit_visits as v
        ');
    }
};
