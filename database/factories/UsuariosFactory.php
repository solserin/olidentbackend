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
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => Hash::make('secret'), // secret
        'telefono' =>  $faker->phoneNumber,
        'created_at' => now(),
        'status' => Arr::random($estados),
        'roles_id' => Arr::random($roles),
    ];
});
