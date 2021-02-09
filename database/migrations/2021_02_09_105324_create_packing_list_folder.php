<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class CreatePackingListFolder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!File::isDirectory(storage_path("packing-list"))) {
            File::makeDirectory(
                storage_path("packing-list"),
                0777,
                true,
                true
            );
        }
    }


    public function down()
    {
        if (File::isDirectory(storage_path("packing-list"))) {
            File::deleteDirectory(storage_path("packing-list"));
        }
    }
}
