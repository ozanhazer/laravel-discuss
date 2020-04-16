<?php

namespace Alfatron\Discuss\Http\Controllers;

use Alfatron\Discuss\Models\Permission;
use DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Log;

class PermissionController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware(config('discuss.auth_middleware'));

        // FIXME: We might want to make this check for every route...
        if (!method_exists(config('discuss.user_model'), 'discussPermissions')) {
            Log::error('Invalid setup: HasDiscussPermissions trait is not used on user model');
            abort(501, 'Invalid setup: HasDiscussPermissions trait is not used on user model');
        }
    }

    public function __invoke()
    {
        $this->authorize('edit-permissions');

        $usersWithPermissions = config('discuss.user_model')::query()
            ->whereExists(function ($query) {
                $userTable = config('discuss.user_model');
                $userModel = new $userTable;
                $query->select(DB::raw(1))
                    ->from(discuss_table('permissions'))
                    ->whereRaw(discuss_table('permissions') . '.user_id=' . $userModel->getTable() . '.' . $userModel->getKeyName());
            })
            ->with('discussPermissions')
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('discuss::permissions.index', compact('usersWithPermissions'));
    }

    public function edit($user)
    {
        $this->authorize('edit-permissions');
        $permissions     = \Alfatron\Discuss\Discuss\Permission::$permissions;
        $userPermissions = $user->discussPermissions->mapToGroups(function ($permission) {
            return [$permission->entity => $permission->ability];
        })->toArray();

        return view('discuss::permissions.edit', compact('user', 'permissions', 'userPermissions'));
    }

    public function save(Request $request)
    {
        $this->authorize('edit-permissions');

        $this->validate($request, [
            'user_id' => 'required',
        ]);

        $user = config('discuss.user_model')::query()->findOrFail($request->get('user_id'));

        DB::beginTransaction();

        Permission::query()->where('user_id', $user->getKey())->delete();

        if ($request->get('perms')) {
            foreach ($request->get('perms') as $entity => $abilities) {
                foreach ($abilities as $ability => $val) {
                    $perm             = new Permission;
                    $perm->user_id    = $user->getKey();
                    $perm->entity     = $entity;
                    $perm->ability    = $ability;
                    $perm->granted_by = $request->user()->getKey();
                    $perm->save();
                }
            }
        }

        DB::commit();

        return redirect()->back();
    }
}
