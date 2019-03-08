<?php

namespace App\Http\Requests\V1\GithubApiRequests;

class GithubIssuesSearchRequest extends GithubApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                'title' => 'string',
                'state' => 'string',
                'number' => 'integer',
            ]
        );
    }
}
