<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Location\app\Models\Country;

class UserInfoSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $users = [
            [
                'name'             => 'Curtis Campher',
                'email'            => 'user@gmail.com',
                'gender'           => 'Male',
                'country_slug'     => 'united-states',
                'province'         => 'Florida',
                'city'             => 'Florida',
                'zip_code'         => '8834',
                'address'          => '742 Evergreen Terrace',
                'age'              => 45,
                'phone'            => '+1-242-013-61452',
                'image'            => 'frontend/images/user.png',
            ],
            [
                'name'         => 'Fatima Noor',
                'email'        => 'fatima.noor@example.com',
                'gender'       => 'Female',
                'country_slug' => 'pakistan',
                'province'     => 'Punjab',
                'city'         => 'Lahore',
                'zip_code'     => '54000',
                'address'      => '23-B Model Town',
                'age'          => 26,
                'phone'        => '+92-300-1234567',
            ],
            [
                'name'         => 'John Doe',
                'email'        => 'johndoe@gmail.com',
                'gender'       => 'Male',
                'country_slug' => 'united-states',
                'province'     => 'Washington',
                'city'         => 'Washington DC',
                'zip_code'     => '8834',
                'address'      => '1600 Pennsylvania Ave NW',
                'age'          => 32,
                'phone'        => '+1-202-456-1111',
            ],
            [
                'name'         => 'Liam Smith',
                'email'        => 'liam.smith@example.com',
                'gender'       => 'Male',
                'country_slug' => 'australia',
                'province'     => 'New South Wales',
                'city'         => 'Sydney',
                'zip_code'     => '2000',
                'address'      => '5 George Street',
                'age'          => 35,
                'phone'        => '+61-2-9374-4000',
            ],
            [
                'name'         => 'Towfik Hasan',
                'email'        => 'tufikhasan05@gmail.com',
                'gender'       => 'Male',
                'country_slug' => 'bangladesh',
                'province'     => 'Rajshahi',
                'city'         => 'Bogura',
                'zip_code'     => '5800',
                'age'          => 28,
                'phone'        => '+88-01521-489753',
            ],
        ];
        foreach ($users as $data) {
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->gender = $data['gender'];
            $user->country_id = Country::where('slug', $data['country_slug'])->first()?->id ?? 1;
            $user->province = $data['province'];
            $user->city = $data['city'];
            $user->zip_code = $data['zip_code'];
            $user->address = $data['address'] ?? 'N/A';
            $user->age = $data['age'] . ' Year';
            $user->phone = $data['phone'];
            $user->image = $data['image'] ?? null;
            $user->email_verified_at = now();
            $user->password = Hash::make('1234');
            $user->status = 'active';
            $user->save();

            $user->delivery_address()->create([
                'first_name' => $user->name,
                'email'      => $user->email,
                'phone'      => $user->phone,
                'title'      => 'Home',
                'country_id' => $user->country_id,
                'province'   => $user->province,
                'city'       => $user->city,
                'address'    => $user->address,
                'zip_code'   => $user->zip_code,
            ]);
        }
    }
}
