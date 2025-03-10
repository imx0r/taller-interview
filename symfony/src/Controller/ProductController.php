<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{
    private array $products;
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;

        $this->products = [
            new Product(1, "Product 1", "Some product 1", 10.19),
            new Product(2, "Product 2", "Some product 2", 29.99),
            new Product(3, "Product 3", "Some product 3", 5.69),
            new Product(4, "Product 4", "Some product 4", 79.90),
            new Product(5, "Product 5", "Some product 5", 102.25),
        ];
    }

    #[Route('/api/products', name: 'products', methods: ['GET'])]
    public function getProducts(): JsonResponse
    {
        $data = array_map(fn($product) => $product->toArray(), $this->products);
        return $this->json($data);
    }

    #[Route('/api/products/{id}', name: 'get_product', methods: ['GET'])]
    public function getProduct(int $id): JsonResponse
    {
        $product = $this->findProduct($id);
        if (!$product) {
            return $this->json(['error' => 'Product not found'], 404);
        }
        return $this->json($product->toArray());
    }

    #[Route('/api/product', name: 'product', methods: ['POST'])]
    public function createProduct(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $product = new Product(
            count($this->products) + 1,
            $data['name'] ?? '',
            $data['description'] ?? '',
            $data['price'] ?? 0
        );

        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        $this->products[] = $product;
        return new JsonResponse($product, Response::HTTP_CREATED);
    }

    #[Route('/api/products/{id}', name: 'update_product', methods: ['PUT'])]
    public function updateProduct(int $id, Request $request): JsonResponse
    {
        $product = $this->findProduct($id);
        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $product->setName($data['name'] ?? $product->getName());
        $product->setDescription($data['description'] ?? $product->getDescription());
        $product->setPrice($data['price'] ?? $product->getPrice());

        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($product);
    }

    #[Route('/api/products/{id}', name: 'delete_product', methods: ['DELETE'])]
    public function deleteProduct(int $id): JsonResponse
    {
        $key = array_search($this->findProduct($id), $this->products);
        if ($key === false) {
            return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        unset($this->products[$key]);
        $this->products = array_values($this->products);
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    private function findProduct(int $id): ?Product
    {
        foreach ($this->products as $product) {
            if ($product->getId() === $id) {
                return $product;
            }
        }
        return null;
    }
}
