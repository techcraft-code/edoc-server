<?php

use Faker\Generator as Faker;
use App\Models\Document ;
$factory->define(App\Models\Document::class, function (Faker $faker) {
    $user = App\Models\User::inRandomOrder()->first();
    $reply_type_id = mt_rand(1,2);
    return [
        'code' => $faker->randomNumber($nbDigits = NULL, $strict = false) ,
        // 'from' => $faker->name,
        'reply_type_id' => $reply_type_id,
        'approved_user_id' => $reply_type_id == 2? 3: null,
        'user_id' => $user->id,
        'school_id' => $user->school_id,
        'type_id' => App\Models\DocumentType::inRandomOrder()->first()->id,
        'title' => $faker->text($maxNbChars = 100)  ,
        'receive_code' => $faker->randomNumber($nbDigits = NULL, $strict = false) ,
        'receive_date' => $faker->date($format = 'Y-m-d', $max = '+30 days') ,
        // 'receive_achives' => $faker->name ,
        'date' => $faker->date($format = 'Y-m-d', $max = '+30 days') ,
        // 'refer' => Document::count() ? Document::inRandomOrder()->first()->id : null ,
        'keywords' => $faker->words($nb = 3, $asText = false) ,
        'status' => mt_rand(1,4) ,
        'cabinet_id' => App\Models\Cabinet::inRandomOrder()->first()->id,
        'send_to_cabinet_id' => App\Models\Cabinet::inRandomOrder()->first()->id,
        'folder_id' => App\Models\Folder::inRandomOrder()->first()->id
    ];
});

$factory->define(App\Models\DocumentAttachment::class, function (Faker $faker) {
    return [
        
    ];
});

$factory->define(App\Models\Cabinet::class, function (Faker $faker) {
    return [
        
    ];
});
