<?php

use App\Models\Experience;
use App\Models\Place;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experience_contents', function (Blueprint $table) {
            $table->id();

            $table->text('description')->nullable();

            ######## Foreign keys  ########
            $table->foreignIdFor(Place::class)->constrained('places')->cascadeOnDelete();
            $table->foreignIdFor(Experience::class)->constrained('experiences')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('experience_contents');
    }
};
