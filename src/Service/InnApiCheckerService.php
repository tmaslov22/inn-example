<?php


namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class InnApiCheckerService
{

    public function check($inn)
    {

        if(strlen($inn) == 11) {
            $inn = '0' . $inn;
        }

        $requestDate = new \DateTime();

        $this->beforeCheck($inn);

        try {
            $response = $this->getClient()->post('https://statusnpd.nalog.ru:443/api/v1/tracker/taxpayer_status', [
                RequestOptions::JSON => [
                    'inn' => $inn,
                    'requestDate' => $requestDate->format('Y-m-d')
                ]
            ]);
        } catch (\Throwable $e) {
            throw new \RuntimeException('Ошибка #' . $e->getCode());
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    private function getClient()
    {
        return new Client();
    }

    private function beforeCheck($inn)
    {
        $sum = substr($inn, 10);
        $base_inn =  substr($inn, 0, 10);

        if(InnGeneratorService::checksum($base_inn) !== $sum) {
            throw new \RuntimeException('Введите корректный инн физ. лица');
        }
    }
}