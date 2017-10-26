<?php

use App\User;
use Illuminate\Database\Seeder;

class ResetAdminSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		echo "开始重置Admin密码\n";
		$user = User::where('name', 'admin')->first();
		if (!$user) {
			$user = new User();
			$user->name = 'admin';
			$user->email = 'admin@admin.com';
		}
		$user->password = bcrypt('11223344');
		$user->save();
		echo "用户名:admin\n";
		echo "密码:11223344\n";
		echo "邮箱:admin@admin.com\n";
		echo "重置Admin密码成功\n";
	}
}
