<?php

namespace Modules\CompanyRian\Services;

use Illuminate\Support\Facades\Storage;
use Modules\Order\Repositories\OrderRepository;
use  ZipArchive;

class TxtOrderService 
{

	public function run()
	{
		Storage::deleteDirectory('txt-rian');
		$orders = OrderRepository::loadClosedOrders();

		foreach ($orders as $order) 
		{
			$this->header($this->file_path($order), $order);
			foreach ($order->items as $item) 
			{
				$this->item($this->file_path($order), $item);
			}
		}

		$this->zip();
		Storage::deleteDirectory('txt-rian');
	}

	private function header($file_path, $order)
	{
		Storage::append($file_path, 
			'*'.
			mb_substr(addString($order->id, 7, '0'), 0, 7).
			mb_substr(addString($order->order_client->client_id, 10, '0'), 0, 10).

			mb_substr($order->closing_date, 8, 2).
			mb_substr($order->closing_date, 5, 2) .
			mb_substr($order->closing_date, 0, 4) . 
			mb_substr($order->closing_date, 11, 2).
			mb_substr($order->closing_date, 14, 2).

			mb_substr(addString($order->order_saller->saller_id, 10, '0'), 0, 10).
			mb_substr(addString($order->order_payment->payment_id, 10, '0'), 0, 10).
			mb_substr(addString(number_format($order->order_payment->discount, 2, '', ''), 5, '0'), 0, 5).

			'0000000000'.

			mb_substr(addString($order->order_shipping_company->description, 45, '0'), 0, 45).
			mb_substr($order->closing_date, 8, 2).
			mb_substr($order->closing_date, 5, 2) .
			mb_substr($order->closing_date, 0, 4));
	}

	private function item($file_path, $item)
	{

		$tax_ipi = $item->item_taxes()->where('module', 'ipi')->first();
		if($tax_ipi)
		{
			$ipi = $tax_ipi->porcentage;
		}else
		{
			$ipi = 0;
		}

		Storage::append($file_path,
			mb_substr(addString($item->product->barcode, 20, ' ', false), 0, 20).
			mb_substr(addString($item->qty, 6, '0'), 0, 6).
			mb_substr(addString(str_replace('.', '', $item->price), 8, '0'), 0, 8).
			mb_substr(addString(preg_replace('/[^0-9]/', '', $ipi), 5, '0'), 0, 5).
			'0');
	}
	

	public function zip()
	{
		$files = Storage::allFiles('txt-rian');
		$zip_path = storage_path('app/txt-rian.zip'); 
		$zip = new ZipArchive;
		$zip->open($zip_path, ZipArchive::CREATE | ZipArchive::OVERWRITE);
		foreach ($files as $file) {
			$zip->addFile(storage_path('app/'.$file), $file);
		}
		$zip->close();
	}	

	private function file_path($order)
	{
		return 'txt-rian/'.addString($order->id, 7, '0') . '.txt';
	}

	

	public function download()
	{
		return response()->download(storage_path('app/txt-rian.zip'))->deleteFileAfterSend();;
	}

}
