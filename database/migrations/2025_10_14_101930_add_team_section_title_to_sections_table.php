<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Modules\Frontend\app\Models\Section;
use Modules\Frontend\app\Models\Home;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update team_section global_content with default title
        $theme_name = DEFAULT_HOMEPAGE;
        $home = Home::where('slug', $theme_name)->first();
        
        if ($home) {
            $teamSection = Section::where('home_id', $home->id)
                ->where('name', 'team_section')
                ->first();
                
            if ($teamSection) {
                $globalContent = $teamSection->global_content ?? [];
                $globalContent['title'] = 'Our Team Behind The Studio';
                
                $teamSection->update([
                    'global_content' => $globalContent
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove title from team_section global_content
        $theme_name = DEFAULT_HOMEPAGE;
        $home = Home::where('slug', $theme_name)->first();
        
        if ($home) {
            $teamSection = Section::where('home_id', $home->id)
                ->where('name', 'team_section')
                ->first();
                
            if ($teamSection) {
                $globalContent = $teamSection->global_content ?? [];
                unset($globalContent['title']);
                
                $teamSection->update([
                    'global_content' => $globalContent
                ]);
            }
        }
    }
};
