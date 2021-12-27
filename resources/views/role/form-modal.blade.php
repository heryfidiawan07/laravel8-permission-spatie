<div class="modal fade" id="form-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="" method="POST" id="form-data" data-method="">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-2 col-md-6">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control">
                        </div>
                        <div class="mb-2 col-md-6">
                            <label for="guard_name">Guard Name</label>
                            <input type="text" name="guard_name" id="guard_name" placeholder='default to "web"' class="form-control">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="mb-1" id="permissions">Permissions</label>
                        <div class="row">
                            @foreach($permissions as $row)
                                <div class="col-12">
                                    <div class="form-check mt-3">
                                        <input name="permissions[]" class="form-check-input parent-permission" 
                                            type="checkbox" value="{{$row->name}}" id="{{$row->name}}">
                                        <label class="form-check-label fs-5 fw-bold" for="{{$row->name}}">
                                            {{$row->name}}
                                        </label>
                                    </div>
                                </div>
                                <div class="row px-4">
                                    @foreach($row->children as $child)
                                        <div class="form-check col-md-3">
                                            <input name="permissions[]" class="form-check-input child-permission {{$row->name}}" 
                                                data-parent="{{$row->name}}" type="checkbox" value="{{$child->name}}" id="{{$child->name}}">
                                            <label class="form-check-label" for="{{$child->name}}">
                                                {{$child->name}}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="btn-submit" class="btn btn-primary"></button>
                </div>
            </form>
        </div>
    </div>
</div>