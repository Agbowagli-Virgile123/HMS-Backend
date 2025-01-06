<?php

use App\Models\Department;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;
// use App\Models\Department;
use App\Models\User;

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
            $table->string('roleName')->unique();
            $table->timestamps();
        });

        // Create the 'departments' table without foreign key constraint
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('departmentName')->unique();
            $table->timestamps();
        });
        

        // Create the 'users' table
        Schema::create('users', function (Blueprint $table) {
            // Add employeeId as a primary key
            $table->string('employeeId')->unique();
            $table->primary('employeeId'); // Make employeeId the primary key
            $table->foreignIdFor(Role::class)->constrained('roles')->onDelete('cascade'); // Role reference
            // Ensure that department_id is nullable and references the departments table
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null'); 
            $table->string('firstName');
            $table->string('lastName');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone');
            $table->string('password');
            $table->enum('status', ['inactive', 'active', 'terminated'])->default('inactive');
            $table->rememberToken();
            $table->timestamps();
        });

        // Create the 'hod' table without foreign key constraint
        Schema::create('hods', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Department::class);
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

        // Create the 'sessions' table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            // Make employeeId the foreign key reference instead of the default 'id' column
            $table->string('employeeId'); // This matches the type of 'employeeId' in the 'users' table
            $table->foreign('employeeId') // Define the foreign key constraint
                  ->references('employeeId') // Reference to 'employeeId' in the 'users' table
                  ->on('users') // The 'users' table
                  ->onDelete('cascade'); // Cascade delete if the referenced user is deleted
        
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
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('user_info');
        Schema::dropIfExists('users');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('roles');
    }
};
