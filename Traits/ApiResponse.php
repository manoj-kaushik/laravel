<?php

namespace Modules\Demowebinar\Traits;

use Illuminate\Http\Request;

trait ApiResponse
{

  /**
   * Get additional data that should be returned with the resource array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function with($request)
  {
    $errors = array();
    $message = '';


    //Extra attributes
    return array(
            'status' => true,
            'message' => $message,
            'errors' => $errors);
  }

}
