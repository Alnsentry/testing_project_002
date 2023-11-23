<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Province;
use App\Models\Daop;
use App\Models\DaopsPondokKerja;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // Permission Seed

        $permissions = [
            'dashboard-read',

            'buku-read',
            'buku-create',
            'buku-edit',
            'buku-delete',

            'daftar-peminjaman',

            'pengajuan-read',
            'pengajuan-create',
            'pengajuan-edit',
            'pengajuan-delete',
            
            'histori-pengajuan',

            'pengajuan-pinjaman',

            'akun-role-read',
            'akun-role-create',
            'akun-role-edit',
            'akun-role-delete',
            
            'akun-staff-read',
            'akun-staff-create',
            'akun-staff-edit',
            'akun-staff-delete',
        ];
    
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // User Seed
        $user = User::create([
            'name' => 'Super Admin', 
            'username' => 'super_admin', 
            'email' => 'super@admin.com',
            'password' => Hash::make('SuperAdmin123!')
        ]);

        $user2 = User::create([
            'name' => 'M Akbar Satriadi', 
            'username' => 'akbar_satriadi', 
            'email' => 'akbarsatriadi97@gmail.com',
            'password' => Hash::make('123')
        ]);

        $role = Role::create(['name' => 'Super Admin']);
        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);
    
        $user->assignRole([$role->id]);

        $userRole = Role::create(['name' => 'User']);
        $userRole->givePermissionTo(['dashboard-read','pengajuan-pinjaman']);
        $user2->assignRole([$userRole->id]);
    }
}
