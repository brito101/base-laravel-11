<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
        CREATE OR REPLACE VIEW `visitors_yesterday_view` AS
        SELECT v.id, v.url, v.ip, v.method, v.languages, v.useragent, v.platform, v.browser, v.visitor_type, v.visitor_id, v.created_at, u.name
        FROM shetabit_visits  as v
        LEFT JOIN users as u ON u.id = v.visitor_id
        WHERE DATE_FORMAT(v.created_at, '%Y-%m-%d') = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW visitors_yesterday_view");
    }
};
