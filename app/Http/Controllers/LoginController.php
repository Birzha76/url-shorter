<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request) {
        if (Auth::check()) {
            return redirect(route('admin.home'));
        }

        $formFields = $request->only([
            'email',
            'password'
        ]);

        if (Auth::attempt($formFields)) {

            if (Auth::user()->is_admin) {

                return redirect()->intended(route('admin.home'));
            }else {
                return redirect()->intended(route('admin.home'));
            }
        }

        return redirect()->to(route('user.login'))->withErrors([
            'formError' => 'Вы ввели неверные авторизационные данные.'
        ]);

    }
}
