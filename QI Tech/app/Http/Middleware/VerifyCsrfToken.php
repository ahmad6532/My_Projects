<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
        '/forms/attachment/upload',
        '/head_office/form_card/fields',
        '/location/five_whys/quesiton',
        '/location/root_cause_analysis_answer_delete',
        '/location/register',
        '/head_office/bespokeforms/test_submit_form/*',
        '/location/bespokeforms/submit_form/*',
    ];
}
