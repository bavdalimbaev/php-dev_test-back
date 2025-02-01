<?php

namespace App\Http\Controllers;

use App\Utils\App\Core\Traits\TResponseDispatch;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as ABaseController;

/**
 * @OA\Info(title="PHP DEV test swagger", version="0.1")
 */
class Controller extends ABaseController
{
    use AuthorizesRequests, ValidatesRequests;
    use TResponseDispatch;
}
