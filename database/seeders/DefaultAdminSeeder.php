<?php

namespace Database\Seeders;

use App\Models\AccessRight;
use App\Models\AccessRightDetail;
use App\Models\Admin;
use App\Resources\ConsDB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::setCurrentUser('system');
        AccessRight::setCurrentUser('system');
        AccessRightDetail::setCurrentUser('system');

        $checkAccess = AccessRight::query()->where('access_name', 'Superadmin')->first();

        if (!$checkAccess) {
            $access = AccessRight::query()->create([
                'access_name' => 'Superadmin',
            ]);

            $accessList = ConsDB::ACCESS_RIGHT_LIST;
            foreach ($accessList as $value) {
                AccessRightDetail::query()->create([
                    'access_id'   => $access->id,
                    'access_code' => $value['code'],
                    'create'      => $value['create'],
                    'read'        => $value['read'],
                    'update'      => $value['update'],
                    'delete'      => $value['delete'],
                ]);
            }

            Admin::query()->create([
                'fullname'  => 'Superadmin',
                'username'  => 'superadmin',
                'nik'       => 123456,
                'password'  => Hash::make('superadmin'),
                'access_id' => $access->id,
                'is_active' => 1,
            ]);
        } else {
            Admin::query()->updateOrCreate([
                'username'  => 'superadmin',
            ],[
                'fullname'  => 'Superadmin',
                'nik'       => 123456,
                'password'  => Hash::make('superadmin'),
                'access_id' => $checkAccess->id,
                'is_active' => 1,
            ]);
        }
    }
}
