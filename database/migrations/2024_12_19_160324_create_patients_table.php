<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Patients\Patient;
use App\Models\Users\User;
use App\Models\Patients\Prescription;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->string('patientId')->unique()->primary();
            $table->string('firstName');
            $table->string('lastName');
            $table->string('dob');
            $table->string('gender');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('address');
            $table->string('purpose');
            $table->enum('status', ['pending', 'registered','discharged','admited','deseased','transfered'])->default('pending');
            $table->string('nhis')->nullable();
            $table->enum('emgRelationship',['parent','spouse','sibling','other']);    
            $table->string('emgPhone');
            $table->timestamps();
        });

        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Patient::class,'patientId');
            $table->foreignIdFor(User::class,'employeeId');
            $table->string('bookerId');
            $table->text('purpose');
            $table->string('department');
            $table->string('appointmentDate');
            $table->string('appointmentTime');
            $table->enum('status',['scheduled','completed','cancelled','checked-in'])->default('scheduled');
            $table->timestamps();
        });

        Schema::create('vitals', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Patient::class,'patientId');
            $table->foreignIdFor(User::class,'employeeId');
            $table->string('bloodPressure');
            $table->string('bpm')->nullable();
            $table->string('temperature');
            $table->float('weight(kg)');
            $table->float('height(m)');
            $table->integer('pulse')->nullable();
            $table->string('others')->nullable();
            $table->float('bmi');
            $table->timestamps();
        });

        Schema::create('diagnosis', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Patient::class,'patientId');
            $table->foreignIdFor(User::class,'employeeId');
            $table->text('diagnosis');
            $table->text('recommendation');
            $table->timestamps();
        });

        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Patient::class,'patientId');
            $table->foreignIdFor(User::class,'employeeId');
            $table->string('prescriptionDate');
            $table->text('note');
            $table->timestamps();
        });

        Schema::create('prescriptionItems', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Prescription::class);
            $table->string('medecineName');
            $table->string('dosage');
            $table->string('duration');
            $table->text('instruction');
            $table->timestamps();
        });

        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->double(column: 'amount');
            $table->enum('status',['paid','pending']);
            $table->string('receiptNumber');
            $table->timestamps();
        });

        Schema::create('testResults', function (Blueprint $table) {
            $table->id();
            $table->string(column: 'testName');
            $table->text(column: 'testResult');
            $table->enum('status',['pending','completed']);
            $table->timestamps();
        });

        Schema::create('admission', function (Blueprint $table) {
            $table->id();
            $table->string(column: 'bedNumber');
            $table->string(column: 'admissionDate');
            $table->enum('status',['pending','completed']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission');
        Schema::dropIfExists('testResults');
        Schema::dropIfExists('billings');
        Schema::dropIfExists('prescriptionItems');
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('diagnosis');
        Schema::dropIfExists('vitals');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('patients');
    }
};
