<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Product;

class SaveProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        try {
            if (is_array($this->data)) {
                foreach ($this->data as $productData) {
                    $this->saveProductData($productData);
                }
            } else {
                $decodedData = json_decode($this->data, true);
                $this->saveProductData($decodedData);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao salvar o produto: ' . $e->getMessage());
        }
    }

    private function saveProductData($product)
    {
        Product::updateOrCreate(
            ['name' => $product['title']],
            [
                'price' => $product['price'],
                'description' => $product['description'],
                'category' => $product['category'],
                'image_url' => $product['image'] ? $product['image'] : null
            ]
        );
    }
}
