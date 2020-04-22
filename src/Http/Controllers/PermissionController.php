<?php

namespace Alfatron\Discuss\Http\Controllers;

use Alfatron\Discuss\Http\Requests\FindUser;
use Alfatron\Discuss\Http\Requests\SavePerms;
use Alfatron\Discuss\Models\Permission;
use Alfatron\Discuss\Models\Post;
use Alfatron\Discuss\Models\Thread;
use DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class PermissionController extends Controller
{
    use AuthorizesRequests;
    use ValidatesRequests;

    public function __construct()
    {
        $this->middleware(config('discuss.auth_middleware'));
    }

    public function __invoke()
    {
        $this->authorize('edit-permissions');

        // We don't know how super admins are decided thus we cannot get
        // them and list here. Actually we don't need to anyways...
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

        if ($user->id == auth()->user()->id) {
            abort(403);
        }

        $permissions     = [
            [Thread::class, 'update'],
            [Thread::class, 'delete'],
            [Thread::class, 'changeCategory'],
            [Thread::class, 'makeSticky'],
            [Post::class, 'update'],
            [Post::class, 'delete'],
        ];
        $userPermissions = $user->discussPermissions->mapToGroups(function ($permission) {
            return [$permission->entity => $permission->ability];
        })->toArray();

        return view('discuss::permissions.edit', compact('user', 'permissions', 'userPermissions'));
    }

    public function save(SavePerms $request)
    {
        $user = config('discuss.user_model')::query()->findOrFail($request->get('user_id'));

        DB::beginTransaction();

        Permission::query()->where('user_id', $user->getKey())->delete();

        if ($request->get('perms')) {
            foreach ($request->get('perms') as $entity => $abilities) {
                foreach ($abilities as $ability) {
                    $perm             = new Permission();
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

    public function findUser(FindUser $request)
    {
        $user = config('discuss.user_model')::query()
            ->where('email', $request->get('user'))
            ->first();

        return response()->json(['uri' => route('discuss.permissions.edit', $user)]);
    }
}
