<?php

namespace App\DataTables;

use App\Models\Article;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ArticlesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {

        if ($this->request->has('showAll')) {
            return (new EloquentDataTable($query))
                ->addColumn('action', function ($passData) {
                    $userData = $passData->articleToLikes->first();
                    if ($userData) {
                        if ($userData->userId == auth()->user()->id) {
                            return ' 
                <span data-url = "' .   route('article.show', $passData->articleId) . '" class="view-btn  action-btns"><i class="fa-solid fa-eye"></i></span>
                <span data-url = "' .   route('article.like', $passData->articleId) . '" class="like-article-btn  action-btns"><i class="fa-solid fa-thumbs-up text-primary  "></i></span>';
                        }
                    }
                    return ' 
                <span data-url = "' .   route('article.show', $passData->articleId) . '" class="view-btn  action-btns"><i class="fa-solid fa-eye"></i></span>
                <span data-url = "' .   route('article.like', $passData->articleId) . '" class="like-article-btn  action-btns"><i class="fa-solid fa-thumbs-up  like-btn-style"></i></span>';
                })
                ->rawColumns(['action']);
        }
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($passData) {
                return ' 
                <span data-url = "' .   route('article.show', $passData->articleId) . '" class="view-btn  action-btns"><i class="fa-solid fa-eye"></i></span>
                <span data-url = "' .   route('article.edit', $passData->articleId) . '" class="edit-btn  action-btns"><i class="fa-solid fa-pen-to-square"></i></span>
                <span data-url = "' .  route('article.delete', $passData->articleId) . '" class="del-btn action-btns"><i class="fa-regular fa-trash-can"></i></span>';
            })
            ->rawColumns(['action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Article $model): QueryBuilder
    {
        $query = $model->newQuery()->where('userId', auth()->user()->id);
        if ($this->request->has('showAll')) {
            $query = $model->newQuery()->where('userId', '!=', auth()->user()->id);
            return $query->orderBy('createdAt', 'desc');
        }
        return $query->orderBy('createdAt', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('articles-table')
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

            Column::make('title'),
            Column::make('content'),
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
        return 'Articles_' . date('YmdHis');
    }
}
