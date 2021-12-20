@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        Table of Role
        <button type="button" class="btn btn-primary btn-sm float-end btn-create" 
            data-bs-toggle="modal" data-bs-target="#form-modal" data-url="{{ route('role-permission.store') }}">
            Create Role
        </button>
    </div>
    <div class="card-body">
        {!! $dataTable->table() !!}
    </div>
</div>
@include('role-permission.form-modal')
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
{!! $dataTable->scripts() !!}}
<script>
/** Role Permission Checkbox **/
$('.child-permission').prop('disabled',true)
$(document).on('click', '.parent-permission', function() {
    if(this.checked) {
        $(`.${this.id}`).prop({'disabled':false, 'checked':true})
    }else {
        $(`.${this.id}`).prop({'disabled':true, 'checked':false})
    }
})
$(document).on('click', '.child-permission', function() {
    let parent = $(this).attr('data-parent')
    let length = $(`.${parent}`).filter(':checked').length
    if(length < 1) {
        $(`#${parent}`).prop('checked',false)
        $(`.${parent}`).prop('disabled',true)
    }
})

$(document).on('click', '.btn-create', function() {
    $('#modalLabel').text('Create Role')
    $('#btn-submit').text('Save')
    $('#form-data')[0].reset()
    $('#form-data').attr({'action':$(this).attr('data-url'),'data-method':'POST'})
})

$(document).on('click', '.btn-edit', function() {
    $('#modalLabel').text('Edit Role')
    $('#btn-submit').text('Update')
    $('#form-data')[0].reset()
    $('#form-data').attr({'action':$(this).attr('data-url'),'data-method':'PUT'})
    getData($(this).attr('data-url'))
})

$(document).on('click', '.btn-delete', function() {
    let data = new FormData()
    data.append('_method','DELETE')
    swallRequest($(this).attr('data-url'), data, false, 'role-table')
})

$('#form-data').submit(function(e) {
    e.preventDefault()
    let data = new FormData(this)
    data.append('_method',$(this).attr('data-method'))
    swallRequest($(this).attr('action'), data, '#form-modal', 'role-table')
})

$(document).ready(function(){
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    })
})

/** Get Role **/
function getData(url)
{
    $.getJSON(url, function(res) {
        console.log(res)
        $('#name').val(res.role.name)
        $('#guard_name').val(res.role.guard_name)
        $.each(res.permissions, function(index, val) {
            $(`#${val}`).prop('checked',true)
            if($(`#${val}`).hasClass('parent-permission')) {
                $(`.${val}`).prop('disabled',false)
            }
        })
    })
}
</script>
@endpush
