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
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control">
                        </div>
                        <div class="mb-2 col-md-6">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <div class="mb-2 col-md-6">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="mb-1" id="roles">Roles</label>
                        <div class="row">
                            @foreach($roles as $row)
                                <div class="col-12">
                                    <div class="form-check mt-3">
                                        <input name="roles[]" class="form-check-input" 
                                            type="checkbox" value="{{$row->name}}" id="{{str_replace(' ','',$row->name)}}">
                                        <label class="form-check-label fs-5 fw-bold" for="{{str_replace(' ','',$row->name)}}">
                                            {{$row->name}}
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <p>{{implode(', ', $row->getPermissionNames()->toArray())}}</p>
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
