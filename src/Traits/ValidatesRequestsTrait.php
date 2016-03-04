<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 04.03.16
 * Time: 11:31
 */

namespace Nebo15\REST\Traits;

use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\ValidationException;

trait ValidatesRequestsTrait
{
    protected function throwValidationException(Request $request, $validator)
    {
        throw new ValidationException($validator);
    }
}
