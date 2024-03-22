<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Api\BaseRequester;
use App\Jobs\SaveProductJob;

class ImportProducts extends Command
{
    protected $baseRequester;
    protected $signature = 'products:import {--id=}';
    protected $description = 'Comando importar os produtos para o banco';

    public function __construct(BaseRequester $baseRequester)
    {
        parent::__construct();
        $this->baseRequester = $baseRequester;
    }

    public function handle()
    {
        try {
            if ($this->option('id')) {
                $response = $this->importSingleProduct($this->option('id'));
                $data = $response;
            } else {
                $response = $this->importAllProducts();
                $data = json_decode($response, true);
            }

            if (empty($data)) {
                $this->info('Falha ao importar produtos: resposta vazia.');
                return false;
            }

            SaveProductJob::dispatch($data)->onQueue('saving');
            $this->info('Produtos importados com sucesso!');

        } catch (\Exception $e) {
            $this->error('Erro ao importar produtos: ' . $e->getMessage());
        }
    }

    private function importAllProducts()
    {
        return $this->baseRequester->get('/products');
    }

    private function importSingleProduct($id)
    {
        return $this->baseRequester->get('/products/'.$id);
    }
}