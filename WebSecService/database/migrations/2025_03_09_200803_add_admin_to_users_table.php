<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminToUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
    
        Schema::table('users', function (Blueprint $table) {
        $table->boolean('admin')->default(1)->change(); // Change default value to 1
        });
    }

    public function down(){
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('admin')->default(0)->change(); // Revert to default value 0
        });
    }
}
