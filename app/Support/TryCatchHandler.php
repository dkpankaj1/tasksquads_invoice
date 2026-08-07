<?php

namespace App\Support;

use Exception;

class TryCatchHandler
{
    public static function execute(callable $function, $redirectRoute = null)
    {
        try {
            return $function();
        } catch (Exception $e) {
            Toastr::error($e->getMessage());

            return redirect()->to($redirectRoute ?? url()->previous());
        }
    }
}
