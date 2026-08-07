<?php

namespace App\Datatables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

abstract class BaseDatatable
{
    protected Model|Builder $query;

    public function __construct(Model|Builder $query)
    {
        $this->query = $query;
    }

    public function get(): JsonResponse
    {
        $dataTable = DataTables::of($this->query);
        $this->configure($dataTable);

        return $dataTable->make(true);
    }

    abstract protected function configure($dataTable);
}
