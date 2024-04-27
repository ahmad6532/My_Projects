<?php

namespace App\DataTables;

use App\Enums\User\UserRoleEnum;
use App\Models\Rider;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RiderDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('action', function ($passData) {
                return '<a href="' . route('rider.edit', $passData->id) . '" class="edit-btn text-success "><i class="fa-solid fa-pen-to-square"></i></a>
                <span data-url="' . route('rider.destroy', $passData->id) .
                    '" class="del-btn text-danger "><i class="fa-regular fa-trash-can"></i></span>
                <a href="' . route('rider.show', $passData->id) . '" class="view-btn text-primary "><i class="fa-solid fa-eye"></i></a>';
            })
            ->rawColumns(['action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()->where('role',UserRoleEnum::Rider->value);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('rider-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            
            Column::make('name'),
            Column::make('email'),
            Column::make('phone'),
            Column::computed('action')
            ->exportable(false)
            ->printable(false)
            ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Rider_' . date('YmdHis');
    }
}
