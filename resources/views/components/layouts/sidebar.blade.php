<div>
    @canany(['role-menu','user-menu'])
        <div class="mb-4">
            <small class="d-block text-secondary mb-2 text-uppercase">User & Permission</small>
            <div class="list-group">
                @can('role-menu')
                    <a href="{{ route('role.index') }}" class="list-group-item list-group-item-action">Role</a>
                @endcan
                @can('user-menu')
                    <a href="{{ route('user.index') }}" class="list-group-item list-group-item-action">User</a>
                @endcan
            </div>
        </div>
    @endcanany

    <div class="mb-4">
        <small class="d-block text-secondary mb-2 text-uppercase">Logout</small>
        <div class="list-group">
            <a class="list-group-item list-group-item-action" href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('form-logout').submit();">
                {{ __('Logout') }}
            </a>

            <form id="form-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</div>
