@extends('layouts.app')

@section('body')
<x-layouts.navigation></x-layouts.navigation>
<main class="py-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <x-layouts.sidebar></x-layouts.sidebar>
            </div>
            <div class="col-md-10">
                <div class="mt-4">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
function swallRequest(url, data=new FormData(), modal=false, tableId=false)
{
    swal({
        title: "Are you sure ?",
        icon: "warning",
        buttons: [
            "Cancel", 
            {
                text: data.get('_method') == 'DELETE' ? 'Delete !' : 'Save',
                closeModal: false,
            }
        ],
        dangerMode: data.get('_method') == 'DELETE' ? true : false,
        closeOnClickOutside: false,
        closeOnEsc: false,
    })
    .then(confirm => {
        console.log('confirm',confirm)
        if (!confirm) throw null;

        return $.ajax({
            url: url,
            type: 'POST',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (res) {
                return res;
            }, error: function(err) {
                return err;
            }
        })
    })
    .then(response => {
        console.log('response',response)
        swal.stopLoading()
        if(response.status) {
            if(modal) {
                $(`${modal}`).modal('hide')
            }
            if(tableId) {
                window.LaravelDataTables[`${tableId}`].ajax.reload()
            }
            return swal('Good job !', `${response.message}`, 'success')
        }
        return swal('Error !', `${response.message}`, 'error')
    })
    .catch(err => {
        console.log('err',err)
        if (err) {
            swal("Error !", err.responseJSON.message, 'error')
            if(err.responseJSON.errors) {
                $('.text-error').remove()
                $.each(err.responseJSON.errors, function(index, val) {
                    console.log('index',index)
                    console.log('val',val)
                    let html = '<span class="text-error text-danger d-block" role="alert"><strong>'+val[0]+'</strong></span>'
                    $(`#${index}`).after(html)
                })
            }
        } else {
            swal.stopLoading()
            swal.close()
        }
    })
}
</script>
@endpush