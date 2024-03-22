<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function create($data)
    {
        return $this->productRepository->create($data);
    }

    public function update($id, $data)
    {
        return $this->productRepository->update($id, $data);
    }

    public function delete($id)
    {
        $this->productRepository->delete($id);
    }

    public function show($id)
    {
        return $this->productRepository->show($id);
    }
    
    public function searchByNameAndCategory($name, $category)
    {
        return $this->productRepository->searchByNameAndCategory($name, $category);
    }

    public function searchByCategory($category)
    {
        return $this->productRepository->searchByCategory($category);
    }

    public function searchWithImage()
    {
        return $this->productRepository->searchWithImage();
    }

    public function searchWithoutImage()
    {
        return $this->productRepository->searchWithoutImage();
    }

}

?>