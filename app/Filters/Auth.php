<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;

class Auth implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */

    public $session;

    public function before(RequestInterface $request, $arguments = null)
    {
        $this->session = \Config\Services::session();
        $key = getenv('JWT_SECRET');

        $headers = $request->getServer('HTTP_AUTHORIZATION');

        if (!$headers) {
            $response = service('response');
            $response->setJSON(['error' => 'Access denied'], 401);
            return $response->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);    
        }

        try {
            $token = explode(' ', $headers)[1];
            $decoded = JWT::decode($token, $key, ['HS256']);
            $this->session->set('user_id', $decoded['user_id']);
        } catch (\Exception $e) {
            $response = service('response');
            $response->setJSON(['error' => 'Token Invalid:' . $e->getMessage()], 401);
            return $response->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);   
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}