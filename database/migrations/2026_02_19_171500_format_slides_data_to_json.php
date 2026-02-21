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
        $slides = DB::table('slides')->get();

        foreach ($slides as $slide) {
            $updateData = [];

            // Check if title is already JSON
            if (!$this->isJson($slide->title)) {
                $updateData['title'] = json_encode(['en' => $slide->title, 'km' => $slide->title], JSON_UNESCAPED_UNICODE);
            }

            // Check subtitle
            if ($slide->subtitle && !$this->isJson($slide->subtitle)) {
                $updateData['subtitle'] = json_encode(['en' => $slide->subtitle, 'km' => $slide->subtitle], JSON_UNESCAPED_UNICODE);
            }
            
            // Check button_text (if it had data before migration which is unlikely as we just added it, but good practice)
            // But wait, button_text was newly added in previous migration (2679), so it's null. No need to fix.
            // Description was also added.
            
            if (!empty($updateData)) {
                DB::table('slides')->where('id', $slide->id)->update($updateData);
            }
        }
    }

    private function isJson($string) {
        if (!is_string($string)) return false;
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No revert needed
    }
};
