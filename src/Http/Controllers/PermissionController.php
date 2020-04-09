<?php


namespace Alfatron\Discuss\Http\Controllers;


use Alfatron\Discuss\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PermissionController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware(config('discuss.auth_middleware'));
    }

    public function __invoke()
    {
        $this->authorize('edit-permissions');

        $permissions = Permission::query()
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('discuss::permissions', compact('permissions'));
    }

    public function edit()
    {
        $this->authorize('edit-permissions');

    }

    public function save(Request $request)
    {
        $this->authorize('edit-permissions');

    }

    public function delete()
    {
        $this->authorize('edit-permissions');

    }
}
