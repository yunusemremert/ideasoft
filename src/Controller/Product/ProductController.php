<?php

namespace App\Controller\Product;

use App\Controller\BaseController;
use App\Service\Product\ProductService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class ProductController extends BaseController
{
    public function __construct(protected ProductService $productService)
    {
    }

    // request = page, order BY ..
    public function getAll(Request $request, Response $response): Response
    {
        $products = $this->productService->getAllProducts();

        if (empty($products)) {
            return $this->jsonResponse($response, 'false', "Product data not exist!", 200);
        }

        return $this->jsonResponse($response, 'success', $products, 200);
    }
}