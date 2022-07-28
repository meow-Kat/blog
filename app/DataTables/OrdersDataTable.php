<?php

namespace App\DataTables;

use App\Models\Order;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
// 引入
use Yajra\DataTables\DataTables;

class OrdersDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            // 編輯顯示的資料，呼應下面的 action column
            ->editColumn('action', function($model){
                $html = '<a class="btn btn-success" href="'.$model->id.'">查看</a>';
                // 做好的 html return 出去
                return $html;
            });
            
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Order $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Order $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('orders-table')
                    ->columns($this->getColumns())
                    ->orderBy( 0, 'desc' )
                    ->parameters([
                        // 分頁長度
                        'pageLength' => 30,
                        // 中文化
                        'language' => config('database.i18n.tw'),
                    ]);
 
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        // 客製自己的欄位，這邊的欄位會自己對映資料庫的值去撈
        return [
            Column::make('id'),
            // 針對 is_shipped 更詳細操作
            new Column ([
                'title' => '是否運送',
                'data' => 'is_shipped',
                'attribute' => [
                    'data-try' => 'teat data'
                ]
            ]),
            Column::make('is_shipped'),
            Column::make('created_at'),
            Column::make('updated_at'),
            Column::make('user_id'),
            new Column ([
                'title' => '功能',
                'data' => 'action',
                // 是否能被搜尋
                'searchable' => false,
            ]),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Orders_' . date('YmdHis');
    }
}
