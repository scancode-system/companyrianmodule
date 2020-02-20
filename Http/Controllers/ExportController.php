<?php

namespace Modules\CompanyRian\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\CompanyRian\Services\TxtOrderService;
use Modules\CompanyRian\Services\TxtClientService;

class ExportController extends Controller
{

    public function txtOrders(Request $request)
    {
        $txt =  new TxtOrderService();
        $txt->run();
        return $txt->download();
    }

        public function txtClients(Request $request)
    {
        $txt =  new TxtClientService();
        $txt->run();
        return $txt->download();
    }

}