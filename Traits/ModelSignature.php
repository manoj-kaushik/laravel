<?php

namespace Modules\Demowebinar\Traits;

use Illuminate\Http\Request;

trait ModelSignature
{

  /**
   * Bootable trait method for model.
   */
    protected static function bootModelSignature()
    {
        static::creating(function($model)
        {
            $userId = Request()->user()->id;
            $model->created_by = $userId;
            $model->updated_by = $userId;
        });
        static::updating(function($model)
        {
            $model->updated_by = Request()->user()->id ?? null;
        });
    }
}
