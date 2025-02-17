<?php

use App\Models\Users\Department;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Users\Role;
// use App\Models\Department;
use App\Models\Users\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the 'roles' table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            // roleName must be unique
            $table->string('roleName')->unique();
            $table->timestamps();
        });

        // Create the 'departments' table without foreign key constraint
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            // departmentName must be unique
            $table->string('departmentName');
            $table->enum('purpose',['checkup',
            'consultation','child healthcare','heart and vascular treatments',
             'bone and joint care','skin treatments','medical imaging and diagnosis',
             'general and specialized surgical procedures','conseling',
             'diagnostics','treatement','vaccination','wellness','emergency',
            ])->nullable();
            $table->integer('number_of_minute_for_appointment')->default(10); // Set a default duration
            $table->timestamps();
        });


        // Create the 'users' table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            // Add employeeId as a primary key
            $table->string('employeeId')->unique();
            // $table->primary('employeeId'); // Make employeeId the primary key
            $table->foreignIdFor(Role::class)->nullable()->constrained('roles')->onDelete('set null'); // Role reference
            // Ensure that department_id is nullable and references the departments table
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone');
            $table->string('password');
            $table->string('address')->nullable();
            $table->enum('status', ['inactive', 'active','terminated'])->default('inactive');
            $table->string('specialization')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });


        Schema::create('employee_schedules', function (Blueprint $table) {
        $table->id();
            $table->foreignIdFor(User::class,'employeeId')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('day_of_week', ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->time('appointment_start_time');
            $table->time('appointment_end_time');
            $table->timestamps();
        });

        // Create the 'hod' table without foreign key constraint
        Schema::create('hods', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class,'employeeId')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignIdFor(Department::class)->nullable()->constrained('departments')->onDelete('set null');
            $table->timestamps();
        });

        // Create the 'user_info' table
        Schema::create('user_infos', function (Blueprint $table) {
            $table->id();
            // Make employeeId the foreign key reference instead of the default 'id' column
            $table->string('employeeId'); // This matches the type of 'employeeId' in the 'users' table
            $table->foreign('employeeId') // Define the foreign key constraint
                  ->references('employeeId') // Reference to 'employeeId' in the 'users' table
                  ->on('users') // The 'users' table
                  ->onDelete('cascade'); // Cascade delete if the referenced user is deleted

            // Other columns
            $table->string('address')->nullable();
            $table->date('dob')->nullable(); // Use 'date' type for date of birth
            $table->string('emergency_contact')->nullable();
            $table->string('emergency_relation')->nullable();
            $table->string('profileImg')->nullable();
            $table->timestamps();
        });

        // Create the 'password_reset_tokens' table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Schema::create('sessions', function (Blueprint $table) {
        //     $table->string('id')->primary();  // Session ID (Primary Key)
        //     $table->string('employeeId')->nullable()->index();  // Reference to employeeId in users table
        //     $table->string('ip_address', 45)->nullable();
        //     $table->text('user_agent')->nullable();
        //     $table->longText('payload');
        //     $table->integer('last_activity')->index();

        //     // Set the foreign key to reference employeeId from the users table
        //     $table->foreign('employeeId')->references('employeeId')->on('users')->onDelete('set null');
        // });


        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->index()->constrained('users')->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions'); // Drop first because it references users
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('user_infos'); // References users via employeeId
        Schema::dropIfExists('employee_schedules');
        Schema::dropIfExists('hods'); // References users and departments
        Schema::dropIfExists('users'); // Users is referenced by others
        Schema::dropIfExists('departments'); // Referenced by hods
        Schema::dropIfExists('roles'); // Independent
    }

};
