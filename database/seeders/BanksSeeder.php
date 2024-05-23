<?php

namespace Database\Seeders;

use App\Models\Bank;
use Http;
use Illuminate\Database\Seeder;

class BanksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $request = Http::get("https://brasilapi.com.br/api/banks/v1");
        if ($request->status() < 200 || $request->status() >= 300) {
            throw new \Exception("Falha ao buscar os bancos");
        }
        $data = json_decode($request->body(), true);

        foreach ($data as $bank) {
            if (empty($bank['name']) && empty($bank['code'])) {
                continue;
            }
            Bank::create([
                'name' => $bank['name'],
                'fullName' => $bank['fullName'] ?? null,
                'ispb' => $bank['ispb'] ?? null,
                'code' => $bank['code'],
            ]);

            echo sprintf('O banco %s foi criado com sucesso', $bank['name'] . PHP_EOL);
        }
    }
}
