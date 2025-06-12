<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trials', function (Blueprint $table) {
            //

            $table->string('startTime')->nullable();
            $table->string('permit')->default('TBA');
            $table->string('coc')->default('TBA');
            $table->string('contactName');
            $table->string('email');
            $table->string('phone');
            $table->string('centre')->nullable();
            $table->string('extras')->nullable();
            $table->string('stripeProductCodes')->nullable();
            $table->string('status');
            $table->string('otherRestriction')->nullable();
            $table->string('notes')->nullable();
            $table->string('options')->nullable();
            $table->string('customCourses')->nullable();
            $table->string('customClasses')->nullable();
            $table->string('otherVenue')->nullable();
            $table->string('entryMethod');
            $table->string('onlineEntryLink')->nullable();


            $table->boolean('hasEodSurcharge')->default(false);
            $table->boolean('hasEntryLimit')->default(false);
            $table->boolean('hasClosingDate')->default(false);
            $table->boolean('hasOpeningDate')->default(false);
            $table->boolean('hasTimePenalty')->default(false);
            $table->boolean('hasWaitingList')->default(false);
            $table->boolean('published')->default(true);

            $table->boolean('isEntryLocked')->default(false);
            $table->boolean('isScoringLocked')->default(false);
            $table->boolean('isLocked')->default(false);
            $table->boolean('isMultiDay')->default(false);
            $table->boolean('isResultPublished')->default(false);

            $table->unsignedTinyInteger('numDays')->default(1);
            $table->unsignedTinyInteger('numColumns')->default(1);
            $table->unsignedTinyInteger('numRows')->default(1);
            $table->unsignedTinyInteger('numLaps')->default(1);
            $table->unsignedTinyInteger('numSections')->default(1);
            $table->unsignedTinyInteger('numSheets')->default(1);

            $table->unsignedInteger('youthEntryFee')->default(0);
            $table->unsignedInteger('adultEntryFee')->default(0);
            $table->unsignedInteger('eodSurcharge')->default(0);
            $table->unsignedInteger('penaltyDelta')->default(0);
            $table->unsignedInteger('startInterval')->default(0);
            $table->unsignedInteger('entryLimit')->default(0);

            $table->unsignedBigInteger('venueID')->nullable();

            $table->dateTime('closingDate')->nullable();
            $table->dateTime('openingDate')->nullable();

            $table->enum('stopNonStop', ['Stop permitted', 'Non-stop'])->default('Stop permitted');
            $table->enum('authority', ['ACU', 'AMCA', 'Other'])->default('AMCA');
            $table->enum('entrySelectionBasis', ['Order of Payment', 'Ballot', 'Selection', 'Other'])->default('Order of Payment');
            $table->enum('scoringMode', ['Observer', 'Electronic', 'Punch cards', 'Other'])->default('Observer');
            $table->enum('restriction', ['Open', 'Centre', 'Closed to club', 'Other'])->default('Open');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trial', function (Blueprint $table) {
            //
        });
    }
};
