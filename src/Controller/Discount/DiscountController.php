<?php

namespace App\Controller\Discount;

use App\Controller\BaseController;
use App\Service\Discount\DiscountService;
use App\Service\Order\OrderService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class DiscountController extends BaseController
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function calculate(Request $request, Response $response, array $args): Response
    {
        $orderId = $args["id"];

        if (empty($orderId)) {
            return $this->jsonResponse($response, 'false', "Invalid data!", 406);
        }

        $orderCheck = $this->orderService->findOrder($orderId);

        if (empty($orderCheck)) {
            return $this->jsonResponse($response, 'false', "Order not exist!", 404);
        }

        $discountService = new DiscountService($orderCheck);
        $calculate       = $discountService->calculateDiscount();

        return $this->jsonResponse($response, 'success', $calculate, 200);
    }
}