<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;



class Controller extends BaseController
{

    protected $status  = 'fail';

    protected $message = '';

    protected $code    = 200;

    protected function responseData($data = [], $more = '',$code = 200) {
        $res = [
            'status'  => $this->status,
            'message' => $this->message,
            'code'=>$this->code,
            'data'    => $data
        ];
        if($more) {
            $res = array_merge($res, $more);
        }
        return response()->json($res,$code);
    }

    protected function validateBase(Request $request ,$rules)
    {
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors      = $validator->errors();
            $errors      = json_decode($errors, true);
            $errorsValue = '';
            foreach ($errors as $item) {
                $errorsValue = $errorsValue . implode(',', $item);
            }
            return $errorsValue;
        }
        return false;
    }
}
