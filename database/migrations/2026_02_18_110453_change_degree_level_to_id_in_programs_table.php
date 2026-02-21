<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->foreignId('degree_level_id')->nullable()->after('degree_level')->constrained('degree_levels')->nullOnDelete();
        });

        // Optional: Map existing data if possible
        // This is a simple mapping. For better results, you'd match by name.
        $programs = DB::table('programs')->get();
        foreach ($programs as $program) {
            $degreeLevel = DB::table('degree_levels')->where('name', 'like', '%' . $program->degree_level . '%')->first();
            if ($degreeLevel) {
                DB::table('programs')->where('id', $program->id)->update(['degree_level_id' => $degreeLevel->id]);
            }
        }

        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn('degree_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->enum('degree_level', ['Associate', 'Bachelor', 'Master', 'PhD'])->after('degree_level_id');
        });

        // Restore data if possible
        $programs = DB::table('programs')->get();
        foreach ($programs as $program) {
            $degreeLevel = DB::table('degree_levels')->where('id', $program->degree_level_id)->first();
            if ($degreeLevel) {
                // Try to map back to enum values
                $val = 'Bachelor';
                if (stripos($degreeLevel->name, 'Associate') !== false) $val = 'Associate';
                elseif (stripos($degreeLevel->name, 'Master') !== false) $val = 'Master';
                elseif (stripos($degreeLevel->name, 'PhD') !== false || stripos($degreeLevel->name, 'Doctor') !== false) $val = 'PhD';
                
                DB::table('programs')->where('id', $program->id)->update(['degree_level' => $val]);
            }
        }

        Schema::table('programs', function (Blueprint $table) {
            $table->dropForeign(['degree_level_id']);
            $table->dropColumn('degree_level_id');
        });
    }
};
