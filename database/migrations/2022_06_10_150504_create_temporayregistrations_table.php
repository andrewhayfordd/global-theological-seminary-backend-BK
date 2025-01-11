
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemporayregistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temporayregistrations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('firstname');
            $table->string('surname');
            $table->string('email')->unique();
            $table->string('nationality');
            $table->date('birthday');
            $table->string('password');
            $table->string('maritalstatus');
            $table->string('gender');
            $table->string('pobox');
            $table->string('phonenumber');
            $table->string('whatsappnumber');
            $table->string('employername');
            $table->string('refereename');
            $table->string('refereephone');
            $table->string('refereeocuppation');
            $table->text('refereesignature');
            $table->text('refereestamp');
            $table->string('classsession');
            $table->string('branch');
            $table->integer('isapproved');
            $table->string('admissionnumber');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temporayregistrations');
    }
}
