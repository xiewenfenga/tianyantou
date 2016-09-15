<?php
/*********************************************************************************
 *  PhpStorm - phpad
 *-------------------------------------------------------------------------------
 * 版权所有: CopyRight By cw100.com
 * 文件内容简单说明
 *-------------------------------------------------------------------------------
 * $FILE:PassportController.php
 * $Author:zxs
 * $Dtime:2016/9/11
 ***********************************************************************************/

namespace App\Http\Controllers\Account;


use App\Http\Controllers\FrontController;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PassportController extends FrontController
{
    public function __construct(
        UserRepository $userRepository
    )
    {
        parent::__initalize();
        $this->userRepository = $userRepository;
    }

    /**
     * 登录
     */
    public function signin(Request $request)
    {
        if ($request->isMethod('post')) {

            $username = $request->username;
            $password = $request->password;
            $remember = $request->remember;

            if (!$username || !$password) return $this->error('用户名或密码错误!', '', true);

            $result = $this->userRepository->checkLogin($username, $password, false, $remember);

            if ($result['status']) {
                return $this->success('登陆成功!', url('/'), true);
            }

            return $this->error($result['message'], '', true);
        }

        if ($this->user) return redirect('/');

        return view('account.passport.signin');
    }

    /**
     * 注册
     */
    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            $rules = [
                'username' => 'required|unique:users',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
            ];
            $messages = [
                'username.required' => '请输入用户名!',
                'username.unique' => '此用户名已被注册!',
                'password.required' => '请输入密码!',
                'password.confirmed' => '两次密码不一致!',
                'password_confirmation.required' => '请输入确认密码!',
            ];
            $data = $request->only(['username','password','password_confirmation']);
            $validator = Validator::make($data, $rules, $messages);
            var_dump($validator);exit;
        }
        return view('account.passport.register');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     *
     * 退出登录
     */
    public function signout()
    {
        if ($this->userRepository->logout()) {
            return redirect(url('signin.html'));
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * 用户协议
     */
    public function protocol()
    {
        return view('account.passport.protocol');
    }

    public function captcha()
    {

    }

}