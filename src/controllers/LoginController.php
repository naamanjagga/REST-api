<?php

use Phalcon\Mvc\Controller;


class LoginController extends Controller
{

    function index()
    {
        $naman = 'naman';
        $this->view->naman = $naman;
    }
    function auth()
    {
        $email = $this->request->get('email');
        $password = $this->request->get('password');
        $connect = new MongoDB\Client("mongodb+srv://m001-student:m001-mongodb-basics@sandbox.j4aug.mongodb.net/myFirstDatabase?retryWrites=true&w=majority");
        $collection = $connect->test->users;
        $find = $collection->find(["email" => $email]);
        foreach ($find as $f) {
            if ($find != null) {
                if ($f->password ==  $password) {
                    if($f->role == 'admin'){
                        $collection = $connect->test->orders;
                        $find = $collection->find();
                        echo '<table>';
                        foreach ($find as $v) {
                            echo '<tr><td>'.$v->costumer_name.'</td><td>'.$v->name.'</td><td>'.$v->category.'</td><td>'.$v->price.'</td><td>'.$v->quantity.'</td><td>'.$v->status.'</td></tr>';
                        }
                        echo '</table>';
                    } else {
                        echo 'You are not an admin';
                    }
                } else {
                    echo 'wrong password';
                }
            } else {
                echo 'user not found';
            }
        }
        echo '<br>';
    }
}
