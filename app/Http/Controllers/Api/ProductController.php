<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProductService;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    protected function productValidator(array $data, $id = 0) {
        return Validator::make($data, [
            'name' =>  'required|string|max:255|min:3|unique:products,name'. ($id ? ",$id" : ''),
            'price' => 'required|numeric|min:0',
            'description' => 'required|string|max:255|min:3',
            'category' => 'required|string|max:50|min:3',
            'image_url' => 'nullable|url',
        ], [
            'name.required' => 'O nome é obrigatório.',
            'name.unique' => 'O nome já está sendo utilizado.',
            'name.string' => 'O nome deve ser uma string.',
            'name.max' => 'O nome não pode ter mais de :max caracteres.',
            'name.min' => 'O nome deve ter pelo menos :min caracteres.',
            'price.required' => 'O preço é obrigatório.',
            'price.numeric' => 'O preço deve ser um número.',
            'price.min' => 'O preço deve ser pelo menos :min.',
            'description.required' => 'A descrição é obrigatória.',
            'description.string' => 'A descrição deve ser uma string.',
            'description.max' => 'A descrição não pode ter mais de :max caracteres.',
            'description.min' => 'A descrição deve ter pelo menos :min caracteres.',
            'category.required' => 'A categoria é obrigatória.',
            'category.string' => 'A categoria deve ser uma string.',
            'category.max' => 'A categoria não pode ter mais de :max caracteres.',
            'category.min' => 'A categoria deve ter pelo menos :min caracteres.',
        ]);
    }

    public function store(Request $request)
    {
        $validator = $this->productValidator($request->all());

        if ($validator->fails()) {
            return response()->json([
                'error' =>  $validator->messages()
            ], 400);
        }
        
        try {
            $data = $request->only(['name', 'price', 'description', 'category', 'image_url']);
            $product = $this->productService->create($data);

            return response()->json(
                $product, 
                201
            );
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = $this->productValidator($request->all(), $id);
        if ($validator->fails()) {  
            return response()->json([
                'error'=>$validator->errors()
            ], 400);
        }

        try {
            $data = $request->only(['name', 'price', 'description', 'category', 'image_url']);
            $product = $this->productService->update($id, $data);

            return response()->json(
                $product, 
                201
            );
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $this->productService->delete($id);

            return response()->json([
                'message' => 'Produto excluído com sucesso.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return response()->json([
                'error' => 'ID inválido.'
            ], 400);
        }

        $product = $this->productService->show($id);

        if (!$product) {
            return response()->json([
                'error' => 'Produto não encontrado.'
            ], 404);
        }

        return response()->json(
            $product
            , 200
        );
    }

    public function searchByNameAndCategory(Request $request)
    {
        if (empty($request->query('category'))) {
        
            return response()->json([
                'error' => 'Produto não encontrado.'
            ], 400);
        }
    
        $name = $request->query('name');
        $category = $request->query('category');

        $products = $this->productService->searchByNameAndCategory($name, $category);

        if ($products->isEmpty()) {
            return response()->json([
                'error' => 'Nenhum produto encontrado com os critérios especificados.'
            ], 404);
        }

        return response()->json(
            $products
            , 200
        );
    }

    public function searchByCategory(Request $request)
    {
        if (empty($request->query('category'))) {
        
            return response()->json([
                'errors' => 'Parâmetros ausentes ou inválidos.'
            ], 400);
        }

        $category = $request->query('category');
    
        $products = $this->productService->searchByCategory($category);

        if ($products->isEmpty()) {
            return response()->json([
                'error' => 'Nenhum produto encontrado com a categoria especificada.'
            ], 404);
        }
    
        return response()->json(
            $products
            , 200
        );
    }

    public function searchWithImage()
    {
        $products = $this->productService->searchWithImage();

        if ($products->isEmpty()) {
            return response()->json([
                'error' => 'Nenhum produto encontrado com imagem.'
            ], 404);
        }

        return response()->json(
            $products
            , 200
        );
    }

    public function searchWithoutImage()
    {
        $products = $this->productService->searchWithoutImage();
        
        if ($products->isEmpty()) {
            return response()->json([
                'error' => 'Nenhum produto encontrado sem imagem.'
            ], 404);
        }

        return response()->json(
            $products
            , 200
        );
    }

}
