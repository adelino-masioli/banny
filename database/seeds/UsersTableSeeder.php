<?php
use Illuminate\Database\Seeder;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array(
                'type'           => 'ADMIN',
                'cellphone'      => '31 98095410',
                'name'           => 'Junior Ferreira',
                'email'          => 'alfjuniorbh.web@gmail.com',
                'password'       => Hash::make('123456'),
                'status'         => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            )
        );
        //insert data
        foreach ($data as $datas) {
            DB::table('users')->insert($datas);
        }
    }
}