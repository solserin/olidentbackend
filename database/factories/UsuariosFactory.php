<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;


$factory->define(App\User::class, function (Faker $faker) {
    $roles = [1, 2, 3, 4, 5];
    $estados = [1, 0];

    $path =  public_path('images/profile.png');
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

    return [
        'name' => strtoupper($faker->name),
        'email' => $faker->unique()->safeEmail,
        'password' => Hash::make('secret'), // secret
        'telefono' =>  strtoupper($faker->phoneNumber),
        'imagen'=>$base64,
        'created_at' => now(),
        'status' => Arr::random($estados),
        'roles_id' => Arr::random($roles),
    ];
});
