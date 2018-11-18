<?php

namespace App\Http\Controllers\Forum;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Swift_Validate;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:forum_mysql.user,username',
            'email' => 'required|email|unique:forum_mysql.user,email',
            'password' => 'required',
            'dob_month' => 'required|between:1,12',
            'dob_day' => 'required',
            'dob_year' => 'required',
        ]);

        if ($validator->fails()) {
            alert()->warning($validator->messages()->first());
            return redirect()->back()->withInput();
        }

        $path = env('FORUM_DIR') . '/src/XF.php';

        if (file_exists($path) && is_readable($path) && require_once($path)) {
            \XF::start('/hc');
            // create user
            /** @var \XF\Service\User\Registration $registration */
            $registration = \XF::service('\XF:User\Registration');
            $input['username'] = $request['username'];
            $input['email'] = $request['email'];
            $input['password'] = $request['password'];
            $input['dob_month'] = $request['dob_month'];
            $input['dob_day'] = $request['dob_day'];
            $input['dob_year'] = $request['dob_year'];
            $registration->setFromInput($input);
            $registration->save();

            alert()->success("Başarılı bir şekilde üye oldunuz");
            return response()->redirectToRoute('home');
        } else {
            throw new \Exception('Could not load XenForo_Autoloader.php check path: ' . $path);
        }
    }
}
