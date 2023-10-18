<?php

namespace App\Controller\Customer;

use App\Controller\BaseController;
use App\Service\Customer\CustomerService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class CustomerController extends BaseController
{
    public function __construct(protected CustomerService $customerService)
    {
    }

    // request = page, order BY ...
    public function getAll(Request $request, Response $response): Response
    {
        $customers = $this->customerService->getAllCustomers();

        if (empty($customers)) {
            return $this->jsonResponse($response, 'false', "Customer data not exist!", 200);
        }

        return $this->jsonResponse($response, 'success', $customers, 200);
    }
}