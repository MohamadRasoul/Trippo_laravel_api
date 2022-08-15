<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// /**
//  * @OA\Schema(
//  *      title="UpdateExperienceContentRequest",
//  *      description="UpdateExperienceContentRequest body data",
//  *      type="object",
//  *      required={"username","email"},
//  *
//  *
//  *      @OA\Property(
//  *         property="username",
//  *         type="string"
//  *      ),
//  *      @OA\Property(
//  *         property="email",
//  *         type="string"
//  *      ),
//  *
//  *
//  *      example={
//  *         "username"              : "mohamad_ra",
//  *         "email"                 : "mralmaahlol@gmail.com",
//  *      }
//  * )
//  */

class UpdateExperienceContentRequest extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            //
        ];
    }


    public function validated($key = null, $default = null)
    {
        return data_get($this->validator->validated(), $key, $default);
    }
}