<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $user = $session->get('user');

        if(!$user){
            return redirect()->to('/login');
        }

        if($arguments){
            $allowedRoles = explode(':', $arguments[0])[1]; // role:admin
            $allowedRoles = explode(',', $allowedRoles);

            if(!in_array($user['role'], $allowedRoles)){
                return redirect()->to('/login')->with('error', 'Access denied');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing after
    }
}
