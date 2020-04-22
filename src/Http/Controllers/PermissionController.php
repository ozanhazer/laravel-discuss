<?php

namespace Alfatron\Discuss\Http\Controllers;

use Alfatron\Discuss\Discuss\Permissions;
use Alfatron\Discuss\Models\Permission;
use Alfatron\Discuss\Models\Post;
use Alfatron\Discuss\Models\Thread;
use DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;

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

    public function save(Request $request)
    {
        $this->authorize('edit-permissions');

        $this->validate($request, [
            'user_id' => 'required',
            'perms'   => [
                'bail',
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    foreach ($value as $entity => $abilities) {
                        if (!isset(Permissions::$availablePermissions[$entity])) {
                            return $fail(__('Invalid permission entity'));
                        }

                        if (!is_array($abilities)) {
                            return $fail(__('Invalid abilities'));
                        }

                        $invalidVals = array_diff($abilities, Permissions::$availablePermissions[$entity]);
                        if (count($invalidVals) > 0) {
                            $fail(__('Invalid abilities'));
                        }
                    }
                },
            ],
        ]);

        $user = config('discuss.user_model')::query()->findOrFail($request->get('user_id'));

        if ($user->id == auth()->user()->id || $user->isDiscussSuperAdmin()) {
            abort(403);
        }

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

    public function findUser(Request $request)
    {
        // User is not authorized because we cannot get the reason if the
        // authorization fails.
        // FIXME This endpoint does not cause any critical security issues
        //       however leaks information, thus should be fixed
        // $this->authorize('edit-permissions');

        $user = config('discuss.user_model')::query()
            ->where('email', $request->get('user'))
            ->first();

        // TODO: Do request validation
        $this->validate($request, [
            'user' => [
                'bail',
                'required',
                'email',
                Rule::exists(config('discuss.user_model'), 'email'),
                function ($attr, $value, $fail) use ($user) {
                    if ($user->id == auth()->user()->id) {
                        $fail('You cannot edit your own permissions');

                        return;
                    }

                    if ($user->isDiscussSuperAdmin()) {
                        $fail('The user is super admin, does not need any permission');
                    }
                },
            ],
        ], [
            'user.exists' => __('No user found with this e-mail address'),
        ]);

        return response()->json(['uri' => route('discuss.permissions.edit', $user)]);
    }
}
