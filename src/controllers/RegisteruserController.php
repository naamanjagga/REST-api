<?php

use Phalcon\Mvc\Controller;
use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;


class RegisteruserController extends Controller
{
    function index()
    {
        // if(isset($_POST['signup'])){
        // echo 'hello';
        // }
    }
    function validate()
    {
        $name = $this->request->get('name');
        $email = $this->request->get('email');
        $password = $this->request->get('password');
        $payload = array($name,$email,$password);
        $str = implode(",",$payload);
        $signer  = new Hmac();

        // Builder object
        $builder = new Builder($signer);

        $now        = new DateTimeImmutable();
        $issued     = $now->getTimestamp();
        $notBefore  = $now->modify('-1 minute')->getTimestamp();
        $expires    = $now->modify('+1 day')->getTimestamp();
        $passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';


        $builder
            ->setAudience('https://localhost')  // aud
            ->setContentType('application/json')        // cty - header
            ->setExpirationTime($expires)               // exp 
            ->setId('abcd123456789')                    // JTI id 
            ->setIssuedAt($issued)                      // iat 
            ->setIssuer('https://phalcon.io')           // iss 
            ->setNotBefore($notBefore)                  // nbf
            ->setSubject($str)   // sub
            ->setPassphrase($passphrase)                // password 
        ;

        $tokenObject = $builder->getToken();

        // The token
        $token = $tokenObject->getToken();
        
        $connect = new MongoDB\Client("mongodb+srv://m001-student:m001-mongodb-basics@sandbox.j4aug.mongodb.net/myFirstDatabase?retryWrites=true&w=majority");
        $collection = $connect->test->users;
        $insertOneResult = $collection->insertOne([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'token' => $token,
            'role' => 'user'
        ]);
        echo 'this is your order ID :'.$token;
        // $this->response->redirect('product/index');
    }
}
