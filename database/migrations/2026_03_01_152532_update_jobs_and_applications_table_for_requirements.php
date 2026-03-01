<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            if (!Schema::hasColumn('jobs', 'company')) {
                $table->string('company')->nullable()->after('title');
            }
        });

        Schema::table('applications', function (Blueprint $table) {
            if (Schema::hasColumn('applications', 'resume')) {
                $table->renameColumn('resume', 'resume_link');
            }
            if (Schema::hasColumn('applications', 'cover_letter')) {
                $table->renameColumn('cover_letter', 'cover_note');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('company');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->renameColumn('resume_link', 'resume');
            $table->renameColumn('cover_note', 'cover_letter');
        });
    }
};
