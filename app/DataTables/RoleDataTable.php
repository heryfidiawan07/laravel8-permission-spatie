<?php

namespace App\DataTables;

use Spatie\Permission\Models\Role;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RoleDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('permissions', function($row) {
                return implode(', ', $row->getPermissionNames()->toArray());
            })
            ->editColumn('created_at', function($row) {
                return $row->created_at->format("d M Y");
            })
            ->editColumn('updated_at', function($row) {
                return $row->updated_at->format("d M Y");
            })
            ->addColumn('action', function($row) {
                $html = '<div class="d-flex">'; 
                $html .= '<button type="button" class="btn btn-primary btn-sm btn-edit" data-bs-toggle="modal" data-bs-target="#form-modal" data-url="'.route('role-permission.update', $row).'">Edit</button>';
                $html .= '<button type="button" class="btn btn-danger btn-sm btn-delete ms-1" data-url="'.route('role-permission.destroy', $row).'">Delete</button>';
                $html .= '</div>';
                return $html;
            });
    }

    public function query(Role $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
                    ->addIndex()
                    ->setTableId('role-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax();
                    // ->dom('Bfrtip')
                    // ->orderBy(1)
                    // ->buttons(
                    //     Button::make('create'),
                    //     Button::make('export'),
                    //     Button::make('print'),
                    //     Button::make('reset'),
                    //     Button::make('reload')
                    // );
    }

    protected function getColumns()
    {
        return [
            Column::computed('DT_RowIndex')
                ->title('No')
                ->exportable(false)
                ->printable(false)
                ->width(5)
                ->addClass('text-center'),
            Column::make('name'),
            Column::make('guard_name'),
            Column::make('permissions'),
            Column::make('created_at'),
            Column::make('updated_at'),
            Column::make('action'),
        ];
    }

    protected function filename()
    {
        return 'Role_' . date('YmdHis');
    }
}
