<?php

namespace Modules\CompanyRian\Services;

use Illuminate\Support\Facades\Storage;
use Modules\Order\Repositories\OrderRepository;
use Modules\Order\Entities\OrderClient;
use  ZipArchive;

class TxtClientService 
{

	public function run()
	{
		Storage::delete('clientes-rian.txt');

		$orders = OrderRepository::loadClosedOrders();
		foreach ($orders as $order) {
			Storage::append('clientes-rian.txt', $this->item($order->order_client));
		}

	}

	private function item(OrderClient $order_client)
	{
		return 	
		mb_substr(addString($order_client->client_id, 10, '0'), 0, 10).
		mb_substr(addString($order_client->fantasy_name, 45, ' ', false), 0, 45) .
		mb_substr(addString($order_client->corporate_name, 45, ' ', false), 0, 45).
		mb_substr(addString(preg_replace('/[^0-9]/', '', $order_client->cpf_cnpj), 14, ' ', false), 0, 14).
		mb_substr(addString($order_client->order_client_address->street, 45, ' ', false), 0, 45) .
		mb_substr(addString($order_client->order_client_address->neighborhood, 45, ' ', false), 0, 45) .
		mb_substr(addString($order_client->order_client_address->city, 45, ' ', false), 0, 45) .
		mb_substr(addString($order_client->order_client_address->st, 2, ' ', false), 0, 2) .
		mb_substr(addString(preg_replace('/[^0-9]/', '', $order_client->order_client_address->postcode), 8, ' ', false), 0, 8).
		'00'.
		mb_substr(addString(preg_replace('/[^0-9]/', '', $order_client->phone), 9, ' ', false), 0, 9).
		mb_substr(addString(preg_replace('/[^0-9]/', '', $order_client->phone), 9, ' ', false), 0, 9).
		mb_substr(addString($order_client->buyer, 45, ' ', false), 0, 45).
		mb_substr(addString('', 45, ' ', false), 0, 45).
		mb_substr(addString('', 45, ' ', false), 0, 45).
		mb_substr(addString($order_client->email, 45, ' ', false), 0, 45);
	}

	public function download()
	{
		return response()->download(storage_path('app/clientes-rian.txt'))->deleteFileAfterSend();;
	}

}



