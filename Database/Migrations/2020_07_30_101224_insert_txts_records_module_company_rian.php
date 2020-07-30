<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Modules\Dashboard\Repositories\TxtRepository;

class InsertTxtsRecordsModuleCompanyRian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        TxtRepository::new(['module' => 'CompanyRian', 'service' => 'TxtClient', 'alias' => 'Clientes - Rian']);
        TxtRepository::new(['module' => 'CompanyRian', 'service' => 'TxtOrder', 'alias' => 'Pedidos - Rian']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        TxtRepository::deleteByAlias('Clientes - Rian');
        TxtRepository::deleteByAlias('Pedidos - Rian');
    }
}
