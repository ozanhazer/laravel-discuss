<?php

namespace Alfatron\Discuss\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FindUser extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('edit-permissions');
    }

    public function rules()
    {
        return [
            'user' => [
                'bail',
                'required',
                'email',
                function ($attr, $value, $fail) {
                    $user = config('discuss.user_model')::query()
                        ->where('email', $value)
                        ->first();

                    if (!$user->exists()) {
                        return $fail(__('No user found with this email address'));
                    }

                    if ($user->id == auth()->user()->id) {
                        return $fail(__('You cannot edit your own permissions'));
                    }

                    if ($user->isDiscussSuperAdmin()) {
                        return $fail(__('The user is super admin, does not need any permission'));
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'user.exists' => __('No user found with this e-mail address'),
        ];
    }
}
