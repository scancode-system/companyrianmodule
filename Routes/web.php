<?php

Route::prefix('companyrian')->group(function() {
    Route::get('txt/orders/rian', 'ExportController@txtOrders')->name('exports.txt.orders.rian');
    Route::get('txt/clients/rian', 'ExportController@txtClients')->name('exports.txt.clients.rian');
});

