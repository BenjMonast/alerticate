<?php

namespace App\Http;

use Illuminate\Database\Eloquent\Model;

class Helper extends Model
{
    public static function message($message) {

        $url = 'https://discord.com/api/webhooks/1054632727443419136/0RL7QtlJkKhkio08BZFpEufZv1fj4GSXXfb8RGh7PNf7kUhnBJFUhieOqVIO3npdNX1b';

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/json",
                'method'  => 'POST',
                'content' => "{
                    \"content\": \"$message\"
                  }"
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === FALSE) { /* Handle error */ }

        var_dump($result);
    }
}
