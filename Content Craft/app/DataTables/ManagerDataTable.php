<?php

namespace App\DataTables;

use App\Enums\UserRoleEnum;
use App\Enums\UserStatusEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

use Yajra\DataTables\Services\DataTable;

class ManagerDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {

        $checkStatus = function ($query) {
            return $query->status == UserStatusEnum::ACTIVE ?
                '<a href="' . route('admin.editManager', $query->id) . '" class="edit-btn action-btns"><i class="fa-solid fa-pen-to-square"></i></a>
                <span data-url="' . route('admin.deleteManager', $query->id) . '" class="del-btn action-btns"><i class="fa-regular fa-trash-can"></i></span>
                <span data-url = "' . route('admin.showManager', $query->id) . '" class="show-btn  action-btns"><i class="fa-solid fa-eye"></i></span>
                <span class="block-unblock-btn" id="blockBtn" data-url="' . route('blockUnblock', $query->id) . '"data-status="' . $query->status . '"><i class="fa-solid fa-ban"></i></span>' :


                '<a href="' . route('admin.editManager', $query->id) . '" class="edit-btn action-btns"><i class="fa-solid fa-pen-to-square"></i></a>
                <span data-url="' . route('admin.deleteManager', $query->id) . '" class="del-btn action-btns"><i class="fa-regular fa-trash-can"></i></span>
                <span data-url = "' . route('admin.showManager', $query->id) . '" class="show-btn  action-btns"><i class="fa-solid fa-eye"></i></span>
                <span class="block-unblock-btn" id="unBlockBtn" data-url="' . route('blockUnblock', $query->id) . '"data-status="' . $query->status . '"><i class="fa-solid fa-ban"></i></span>';
        };

        return (new EloquentDataTable($query))
            ->addColumn('action', $checkStatus)
            ->rawColumns(['action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        $query = $model->whereHas('roles', function ($query) {
            $query->where('name', UserRoleEnum::MANAGER);
        });

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('manager-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
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
            Column::make('firstName'),
            Column::make('lastName'),
            Column::make('email'),
            Column::make('phone'),
            Column::make('country'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(70)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Manager_' . date('YmdHis');
    }
}
