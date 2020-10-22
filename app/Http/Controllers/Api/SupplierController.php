<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmbellishmentSupplierCollection;
use App\Http\Resources\SupplierCollection;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        return new SupplierCollection(Supplier::all());
    }

    public function embellishmentSuppliers()
    {
        return new EmbellishmentSupplierCollection(\App\Models\EmbellishmentSupplier::all());
    }
}
