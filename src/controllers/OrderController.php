<?php

use Phalcon\Mvc\Controller;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;
 


class OrderController extends Controller
{

    function create($p_name,$category,$price,$quantity,$token)
    {
        // echo $token;
        $parser = new Parser();
        $tokenObject = $parser->parse($token);
        $now        = new \DateTimeImmutable();
        $expires    = $now->getTimestamp();
        // $expires    = $now->modify('+1 day')->getTimestamp();

        $validator = new Validator($tokenObject, 100);
        $validator->validateExpiration($expires);
        $claim = $tokenObject->getClaims()->getPayload();
        $user = explode(',',$claim['sub']);
        $date = strtotime(date('Y-m-d h:i:sa'));
        $connect = new MongoDB\Client("mongodb+srv://m001-student:m001-mongodb-basics@sandbox.j4aug.mongodb.net/myFirstDatabase?retryWrites=true&w=majority");
        $collection = $connect->test->orders;
        $insertOneResult = $collection->insertOne([
            'costumer_name' => $user[0],
            'name' => $p_name,
            'category' => $category,
            'price' => $price,
            'quantity' => $quantity,
            'status' => 'paid',
            'date' =>  $date,
        ]);
        $collection = $connect->test->orders;
        $find = $collection->find([],["sort" => ['date' => -1]]);
        echo 'this is your order ID :';
        foreach($find as $f){
            echo $f->_id;die;
        } 
    }
    function update($id = "", $status = "")
    {
        $connect = new MongoDB\Client("mongodb+srv://m001-student:m001-mongodb-basics@sandbox.j4aug.mongodb.net/myFirstDatabase?retryWrites=true&w=majority");
        $collection = $connect->test->orders;

        $update = $collection->updateOne(
            array("_id" => new MongoDB\BSON\ObjectID($id)),
            ['$set' => [
                'status'     => $status
            ]]

        );
    }
}
