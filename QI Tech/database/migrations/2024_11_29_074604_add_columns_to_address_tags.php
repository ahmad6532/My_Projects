<?php

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
        Schema::table('address_tags', function (Blueprint $table) {
            if (!Schema::hasColumn('address_tags', 'head_office_id')) {
                $table->unsignedInteger('head_office_id')->nullable(); // Make nullable to avoid errors on existing data
                $table->foreign('head_office_id')->references('id')->on('head_offices');
            }

            if (!Schema::hasColumn('address_tags', 'address_id')) {
                $table->unsignedBigInteger('address_id')->nullable();
                $table->foreign('address_id')->references('id')->on('new_contact_addresses')->onDelete('cascade');
            }

            if (!Schema::hasColumn('address_tags', 'name')) {
                $table->string('name')->nullable();
            }

            if (!Schema::hasColumn('address_tags', 'color')) {
                $table->string('color')->default('#000');
            }

            if (!Schema::hasColumn('address_tags', 'icon')) {
                $table->string('icon')->default('1');
            }

            if (!Schema::hasColumn('address_tags', 'icon_color')) {
                $table->string('icon_color')->default('#fff');
            }

            if (!Schema::hasColumn('address_tags', 'text_color')) {
                $table->string('text_color')->default('#fff');
            }
            if (Schema::hasColumn('address_tags', 'tag_name')) {
                $table->text('tag_name')->nullable()->change();
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
        Schema::table('address_tags', function (Blueprint $table) {
            if (Schema::hasColumn('address_tags', 'head_office_id')) {
                $table->dropForeign(['head_office_id']);
                $table->dropColumn('head_office_id');
            }

            if (Schema::hasColumn('address_tags', 'address_id')) {
                $table->dropForeign(['address_id']);
                $table->dropColumn('address_id');
            }

            if (Schema::hasColumn('address_tags', 'name')) {
                $table->dropColumn('name');
            }

            if (Schema::hasColumn('address_tags', 'color')) {
                $table->dropColumn('color');
            }

            if (Schema::hasColumn('address_tags', 'icon')) {
                $table->dropColumn('icon');
            }

            if (Schema::hasColumn('address_tags', 'icon_color')) {
                $table->dropColumn('icon_color');
            }

            if (Schema::hasColumn('address_tags', 'text_color')) {
                $table->dropColumn('text_color');
            }
        });
    }
};
