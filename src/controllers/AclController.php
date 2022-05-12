<?php

declare(strict_types=1);

use Phalcon\Mvc\Controller;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;

class AclController extends Controller
{
    public function indexAction()
    {
    }
    public function buildacl()
    {
        $aclFile = './security/acl.cache';
        // Check whether ACL data already exist
        if (true !== is_file($aclFile)) {

            // The ACL does not exist - build it
            $acl = new Memory();

            $acl->addRole('admin');
            $acl->addRole('user');

            $acl->addComponent(
                'product',
                [
                    'index',
                    'search',
                    'get',
                ]
            );

            $acl->addComponent(
                'order',
                [
                    'create',
                    'update',
                ]
            );

            $acl->allow('admin','*', '*');
            $acl->allow('user','product', '*');
            $acl->allow('user','order', 'create');

            file_put_contents(
                $aclFile,
                serialize($acl)
            );
        } else {
            $acl = unserialize(
                file_get_contents($aclFile)
            );
        }
    }
}
