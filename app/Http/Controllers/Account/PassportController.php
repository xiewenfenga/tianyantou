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
use App\Library\Traits\SmsTrait;
use App\Library\Utils\Captcha;
use App\Repositories\UserRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PassportController extends FrontController
{
    use SmsTrait;

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
                $url = Session::get('previous');
                return $this->success('登陆成功!', url($url), true);
            }

            return $this->error($result['message'], '', true);
        }
        if (!$request->is('signin.html') || !$request->is('register.html') || !$request->is('signout.html')) {
            Session::put('previous', \URL::previous());
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
            $mobile = trim($request->mobile);
            $password = trim($request->password);
            $confirmpassword = trim($request->password_confirmation);
            $verifyCode = trim($request->verifyCode);
            $phoneCode = Session::get('phone');
            if (!$mobile) {
                return $this->error('手机号不能为空!', null, true);
            }
            if ($verifyCode != $phoneCode) {
                return $this->error('手机验证码不正确!', null, true);
            }
            if (!$password || !$confirmpassword) {
                return $this->error('密码或确认密码不能为空!', null, true);
            }
            if ($password != $confirmpassword) {
                return $this->error('两次输入密码不一致!', null, true);
            }
            $exists = $this->userRepository->userModel->where('mobile', $mobile)->exists();
            if ($exists) {
                return $this->error('该手机号码已注册天眼投账号!', null, true);
            }
            $data = [
                'mobile' => $mobile,
                'password' => $password,
            ];
            $result = $this->userRepository->saveUser($data);
            if ($result['status']) return $this->success('注册成功!', url('/'), true);

            return $this->error('注册失败!', null, true);
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

    /**
     * @param Request $request
     * @param Captcha $captcha
     * @return \Illuminate\Http\JsonResponse
     *
     * 获取图形验证码
     */
    public function captcha(Request $request, Captcha $captcha)
    {
        if ($request->isMethod('post')) {
            $code = $request->get('captcha');;
            $checkCode = Session::get('captcha');

            if (strtolower($code) == strtolower($checkCode)) {
                return response()->json('success');
            }
            return response()->json('fail');
        }
        $captcha->doimg();
        Session::put('captcha', $captcha->getCode());
    }

    /**
     * @param Request $request
     *
     * 发送手机验证码
     */
    public function sendVerifyCode(Request $request)
    {
        $action = $request->get('action');
        $phone = $request->get('telephone');
        $captcha = $request->get('captcha');
        $checkCaptcha = Session::get('captcha');

        if ($action == 'register') {
            if (!$captcha) {
                return $this->error('图形验证码不能为空', null, true);
            }
            if (strtolower($captcha) != strtolower($checkCaptcha)) {
                return $this->error('图形验证码不正确', null, true);
            }
            $model = $this->userRepository->userModel->where('mobile', $phone)->first();
            if (!empty($model)) {
                $this->error('该手机号码已注册天眼投账号', null, true);
            }
            $code = randomCode();
            $mobile = [$phone];
            $template = $this->getSmsTemplates($action, $code);
            try {
                $this->sendSms($mobile, $template);
                Session::put('phone', $code);
            } catch (\Exception $e) {
                $e->getMessage();
            }
        }
    }
}