<?php

namespace Alfatron\Discuss\Http\Requests;

use Alfatron\Discuss\Discuss\Permissions;
use Illuminate\Foundation\Http\FormRequest;

class SavePerms extends FormRequest
{
    public function authorize()
    {
        if ($this->get('user_id') == $this->user()->id) {
            return false;
        }

        $user = config('discuss.user_model')::query()->findOrFail($this->get('user_id'));
        if ($user->isDiscussSuperAdmin()) {
            return false;
        }

        return $this->user()->can('edit-permissions');
    }

    public function rules()
    {
        return [
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
        ];
    }
}
