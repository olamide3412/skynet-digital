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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });

        // Insert default initial contact settings
        $now = now();
        DB::table('settings')->insert([
            [
                'key' => 'contact_email',
                'value' => 'info@skynetdigital.com',
                'group' => 'contact',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'contact_phone',
                'value' => '+234 803 207 2831',
                'group' => 'contact',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'contact_whatsapp',
                'value' => '2348032072831',
                'group' => 'contact',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'contact_address',
                'value' => 'Delta State, Nigeria',
                'group' => 'contact',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'contact_hours',
                'value' => 'Monday – Friday: 8:00 AM – 6:00 PM',
                'group' => 'contact',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'rc_number',
                'value' => 'RC 9168845',
                'group' => 'general',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
