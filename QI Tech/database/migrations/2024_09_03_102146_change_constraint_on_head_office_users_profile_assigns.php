<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Fix conflicting rows
        $this->fixConflictingRows();

        Schema::table('head_office_users_profile_assigns', function (Blueprint $table) {
            try {
                // Drop the existing foreign key constraint if it exists
                if ($this->foreignKeyExists('head_office_users_profile_assigns', 'user_profile_id')) {
                    $table->dropForeign(['user_profile_id']);
                }

                // Add the new foreign key constraint
                $table->foreign('user_profile_id')
                    ->references('id')
                    ->on('head_office_access_rights')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            } catch (\Exception $e) {
                \Log::error('Migration failed: ' . $e->getMessage());
                throw $e;
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('head_office_users_profile_assigns', function (Blueprint $table) {
            try {
                // Drop the existing foreign key constraint if it exists
                if ($this->foreignKeyExists('head_office_users_profile_assigns', 'user_profile_id')) {
                    $table->dropForeign(['user_profile_id']);
                }

                // Re-add the original foreign key constraint
                $table->foreign('user_profile_id')
                    ->references('id')
                    ->on('head_office_user_profiles')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            } catch (\Exception $e) {
                \Log::error('Migration rollback failed: ' . $e->getMessage());
                throw $e;
            }
        });
    }

    /**
     * Fix conflicting rows that cause foreign key constraint violations.
     *
     * @return void
     */
    private function fixConflictingRows()
    {
        // Remove conflicting rows
        DB::statement('
            DELETE FROM head_office_users_profile_assigns
            WHERE user_profile_id NOT IN (SELECT id FROM head_office_access_rights)
        ');

        // Alternatively, you can update conflicting rows with a default or valid value if required.
        // Example: Setting a default value for rows with invalid references
        // DB::statement('
        //     UPDATE head_office_users_profile_assigns
        //     SET user_profile_id = (SELECT id FROM head_office_access_rights LIMIT 1)
        //     WHERE user_profile_id NOT IN (SELECT id FROM head_office_access_rights)
        // ');
    }

    /**
     * Check if a foreign key exists.
     *
     * @param string $tableName
     * @param string $foreignKey
     * @return bool
     */
    private function foreignKeyExists($tableName, $foreignKey)
    {
        $keyName = $this->getForeignKeyName($tableName, $foreignKey);

        return DB::table('information_schema.key_column_usage')
            ->where('table_name', $tableName)
            ->where('constraint_name', $keyName)
            ->exists();
    }

    /**
     * Generate the foreign key name.
     *
     * @param string $tableName
     * @param string $column
     * @return string
     */
    private function getForeignKeyName($tableName, $column)
    {
        return $tableName . '_' . $column . '_foreign';
    }
};
