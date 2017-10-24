<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtStuInfoTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('stu_infos', function (Blueprint $table) {
			$table->string('stu_name')->nullable()->default(null);
			$table->string('id_number')->nullable()->default(null);
			$table->string('ji_guan')->nullable()->default(null);
			$table->string('gao_zhong')->nullable()->default(null);
			$table->string('gao_kao_num')->nullable()->default(null);
			$table->string('xi_suo')->nullable()->default(null);
			$table->string('zhuan_ye')->nullable()->default(null);
			$table->string('ban_ji')->nullable()->default(null);
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('stu_infos', function (Blueprint $table) {
			$table->dropColumn('stu_name');
			$table->dropColumn('id_number');
			$table->dropColumn('ji_guan');
			$table->dropColumn('gao_zhong');
			$table->dropColumn('gao_kao_num');
			$table->dropColumn('xi_suo');
			$table->dropColumn('zhuan_ye');
			$table->dropColumn('ban_ji');
		});
	}
}
