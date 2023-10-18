<?php

namespace App\Controller\Order;

use App\Controller\BaseController;
use App\Service\Order\OrderService;
use App\Service\Product\ProductService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class OrderController extends BaseController
{
    private ProductService $productService;

    public function __construct(
        protected OrderService $orderService,
        ProductService $productService
    )
    {
        $this->productService = $productService;
    }

    // request = page, order BY ...
    public function getAll(Request $request, Response $response): Response
    {
        $orders = $this->orderService->getAllOrders();

        if (empty($orders)) {
            return $this->jsonResponse($response, 'false', "Order data not exist!", 200);
        }

        return $this->jsonResponse($response, 'success', $orders, 200);
    }

    public function add(Request $request, Response $response) : Response
    {
        $data = $request->getParsedBody();

        if (empty($data)) {
            return $this->jsonResponse($response, 'false', "Invalid data!", 400);
        }

        if (empty($data["customerId"]) || empty($data["items"])) {
            return $this->jsonResponse($response, 'false', "Invalid data!", 406);
        }

        $orders     = [
            "id"         => rand(1, 99999999),
            "customerId" => $data["customerId"],
            "items"      => []
        ];

        $totalPrice = 0;

        foreach ($data["items"] as $item) {
            $productId    = $item["productId"];
            $quantity     = $item["quantity"];

            $productCheck = $this->productService->findProduct($productId);

            if (empty($productCheck)) {
                return $this->jsonResponse($response, 'false', "Product not exist!", 404);
            }

            if ($quantity > $productCheck["stock"]) {
                return $this->jsonResponse($response,
                    'false',
                    "ProductId : " . $productId . ", You entered more than stock!, remaining stock : " . $productCheck["stock"],
                    200
                );
            }

            $productPrice  = $productCheck["price"];
            $subTotalPrice = round($productPrice * $quantity, 2);
            $totalPrice   += $subTotalPrice;

            $orders["items"][] = [
                "productId" => $productId,
                "quantity"  => $quantity,
                "unitPrice" => $productPrice,
                "total"     => $subTotalPrice
            ];

            $orders["total"]   = $totalPrice;
        }

        $this->orderService->addOrder($orders);

        return $this->jsonResponse($response, 'success', "Added orders.", 201);
    }

    public function delete(Request $request, Response $response, array $args) : Response
    {
        $orderId = $args["id"];

        if (empty($orderId)) {
            return $this->jsonResponse($response, 'false', "Invalid data!", 406);
        }

        $orderCheck = $this->orderService->findOrder($orderId);

        if (empty($orderCheck)) {
            return $this->jsonResponse($response, 'false', "Order not exist!", 404);
        }

        $this->orderService->deleteOrder($orderId);

        return $this->jsonResponse($response, 'success', "Deleted order.", 200);
    }
}