<?php
namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function create($data)
    {
        return Product::create($data);
    }

    public function update($id, $data)
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }

    public function delete($id)
    {
        Product::find($id)->delete();
    }

    public function show($id)
    {
        return Product::find($id);
    }

    public function searchByNameAndCategory($name, $category)
    {
        return Product::where('name', 'LIKE', '%'.$name.'%')
            ->where('category', 'LIKE', '%'.$category.'%')
            ->get();
    }

    public function searchByCategory($category)
    {
        return Product::where('category', $category)->get();
    }

    public function searchWithImage()
    {
        return Product::whereNotNull('image_url')->get();
    }

    public function searchWithoutImage()
    {
        return Product::whereNull('image_url')->get();
    }

    public function searchById($id)
    {
        return Product::find($id);
    }
}
?>