<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailOtpsTable extends Migration
{
    public function up()
    {
        Schema::create('email_otps', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('otp', 6);
            $table->string('purpose')->default('enquiry'); // enquiry, login etc
            $table->boolean('used')->default(false);
            $table->timestamp('expires_at');
            $table->timestamps();
            $table->index('email');
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_otps');
    }
}
