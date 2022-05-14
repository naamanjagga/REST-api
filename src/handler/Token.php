<?php

namespace Api\Handler;

use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;

class Token
{

    function checkToken($token)
    {
        if (isset($token)) {
            $parser = new Parser();
            $tokenObject = $parser->parse($token);
            $now        = new \DateTimeImmutable();
            $expires    = $now->getTimestamp();
            // $expires    = $now->modify('+10 day')->getTimestamp();

            $validator = new Validator($tokenObject, 100);
            $validator->validateExpiration($expires);
            $claim = $tokenObject->getClaims()->getPayload();
            $user = explode(',', $claim['sub']);

            $mongo =  new \MongoDB\Client("mongodb+srv://m001-student:m001-mongodb-basics@sandbox.j4aug.mongodb.net/myFirstDatabase?retryWrites=true&w=majority");
            $collection = $mongo->test->users;
            $find = $collection->find(["token" => $token]);
            foreach ($find as $f) {
                if ($f->name == $user[0]) {
                    // echo 'naman';die;
                    return true;
                } else {
                    echo 'Token didnt match';
                }
            }
        }
    }
    function error()
    {
    }
}
