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
        $table = 'head_office_invites';
        $foreignKey = 'head_office_user_profile_id';

        Schema::table($table, function (Blueprint $table) use ($foreignKey) {
            try {
                // Check if foreign key exists before attempting to drop it
                if ($this->foreignKeyExists($table, $foreignKey)) {
                    $table->dropForeign([$foreignKey]);
                }

                // Add the new foreign key constraint
                $table->foreign($foreignKey)
                    ->references('id')
                    ->on('head_office_access_rights')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            } catch (\Exception $e) {
                // Log error or handle exception
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
        $table = 'head_office_invites';
        $foreignKey = 'head_office_user_profile_id';

        Schema::table($table, function (Blueprint $table) use ($foreignKey) {
            try {
                // Check if foreign key exists before attempting to drop it
                if ($this->foreignKeyExists($table, $foreignKey)) {
                    $table->dropForeign([$foreignKey]);
                }

                // Re-add the original foreign key constraint
                $table->foreign($foreignKey)
                    ->references('id')
                    ->on('head_office_user_profiles')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            } catch (\Exception $e) {
                // Log error or handle exception
                \Log::error('Migration rollback failed: ' . $e->getMessage());
                throw $e;
            }
        });
    }

    /**
     * Check if a foreign key exists.
     *
     * @param Blueprint $table
     * @param string $foreignKey
     * @return bool
     */
    private function foreignKeyExists(Blueprint $table, $foreignKey)
    {
        $keyName = $this->getForeignKeyName($table->getTable(), $foreignKey);

        $exists = DB::table('information_schema.key_column_usage')
            ->where('table_name', $table->getTable())
            ->where('constraint_name', $keyName)
            ->exists();

        return $exists;
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