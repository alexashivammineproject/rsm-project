<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrmSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_settings', function (Blueprint $table) {
            $table->id();
            $table->string('api_key');
            $table->string('api_url');
            $table->boolean('is_active')->default(true);
            $table->text('test_response')->nullable();
            $table->timestamp('last_tested_at')->nullable();
            $table->timestamps();
        });

        // Insert default values
        DB::table('crm_settings')->insert([
            'api_key' => 'shivam9501276871_Y9c4PCVmufin3gEI827pT5Ao9aPCT4cMgYxjLHZ6hR5hNjN3Sa',
            'api_url' => 'https://leads.knowyourmedi.com/api/webhook/enquiry',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_settings');
    }
}
