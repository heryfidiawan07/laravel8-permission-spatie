@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        Table of User
        <button type="button" class="btn btn-primary btn-sm float-end btn-create" 
            data-bs-toggle="modal" data-bs-target="#form-modal" data-url="{{ route('user.store') }}">
            Create User
        </button>
    </div>
    <div class="card-body">
        {!! $dataTable->table() !!}
    </div>
</div>
@include('user.form-modal')
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
{{-- <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script> --}}
{{-- <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script> --}}
<script src="{{asset('lib/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('lib/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
{!! $dataTable->scripts() !!}}
<script>
/** User Permission Checkbox **/
$(document).on('click', '.btn-create', function() {
    $('#modalLabel').text('Create User')
    $('#btn-submit').text('Save')
    $('#form-data').attr({'action':$(this).attr('data-url'),'data-method':'POST'})
})

$(document).on('click', '.btn-edit', function() {
    $('#modalLabel').text('Edit User')
    $('#btn-submit').text('Update')
    $('#form-data').attr({'action':$(this).attr('data-url'),'data-method':'PUT'})
    getData($(this).attr('data-url'))
})

$(document).on('click', '.btn-delete', function() {
    let data = new FormData()
    data.append('_method','DELETE')
    swallRequest($(this).attr('data-url'), data, false, 'user-table')
})

$('#form-data').submit(function(e) {
    e.preventDefault()
    let data = new FormData(this)
    data.append('_method',$(this).attr('data-method'))
    swallRequest($(this).attr('action'), data, '#form-modal', 'user-table')
})

$(document).ready(function(){
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    })
})

/** Get User **/
function getData(url)
{
    $.getJSON(url, function(res) {
        console.log(res)
        $('#name').val(res.user.name)
        $('#email').val(res.user.email)
        $.each(res.roles, function(index, val) {
            $(`#${val.replace(' ', '')}`).prop('checked',true)
        })
    })
}
</script>
@endpush
