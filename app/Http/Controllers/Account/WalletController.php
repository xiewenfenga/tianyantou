<?php
/*********************************************************************************
 *  资金管理控制器 - phpad
 *-------------------------------------------------------------------------------
 * 版权所有: CopyRight By tianyantou.com
 * 文件内容简单说明
 *-------------------------------------------------------------------------------
 * $FILE:WalletController.php
 * $Author:pzz
 * $Dtime:2016/9/13
 ***********************************************************************************/

namespace App\Http\Controllers\Account;


use App\Http\Controllers\FrontController;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class WalletController extends FrontController
{
    public function __construct(
        UserRepository $userRepository
    )
    {
        parent::__initalize();
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     *
     * 申请提现
     */
    public function withdraw(Request $request)
    {
        $bank = $this->userRepository->bankModel->where('user_id', $this->user['id'])->first();
        //没有银行需要先绑定银行卡
        if (empty($bank)) {
            return view('account.wallet.bandcard');
        }
        $money = $this->userRepository->moneyModel->where('user_id', $this->user['id'])->first();

        if ($request->isMethod('post')) {
            $commission = 0.00;
            $price = $request->get('price');
            $password = $request->get('password');
            if ($price > $money->money) return '提现金额不能超过账户余额';
            if (!password_verify($password, $money->password)) return '交易密码不正确';
            $data = [
                'user_id' => $this->user['id'],
                'bank_id' => $bank->id,
                'price' => $price,
                'commission' => $commission,
                'status' => 0,
            ];
            $result = $this->userRepository->saveWithdraw($data);
            if ($result['status']) return json_encode($data);
            return '提现失败!';
        }

        return view('account.wallet.withdraw', compact('bank', 'money'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * 提现列表
     */
    public function withdrawlist(Request $request)
    {
        $page = $request->get('page') ?: 1;
        $where = ['user_id' => $this->user['id']];
        list($count, $lists) = $this->userRepository->getWithdrawList($where, $this->perpage, $page);
        $pageHtml = $this->pager($count, $page, $this->perpage);
        $money = $this->userRepository->moneyModel->where('user_id', $this->user['id'])->first();
        //成功提现次数
        $where = array_merge($where, ['status' => 1]);
        $success = $this->userRepository->withdrawModel->countBy($where);
        $withdraws = $this->userRepository->withdrawModel->createWhere($this->userRepository->withdrawModel, $where)->sum('price');
        return view('account.wallet.withdrawlist', compact('lists', 'pageHtml', 'money', 'success', 'withdraws'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * 资金流水列表
     */
    public function book(Request $request)
    {
        $page = $request->get('page') ?: 1;
        $type = $request->get('opType');
        $time = $request->get('timespan');
        $where = ['user_id' => $this->user['id']];
        if ($time) {
            switch ($time) {
                case '1w':
                    $where['raw'] = ['created_at>DATE_SUB(NOW(),INTERVAL 1 WEEK)'];
                    break;
                case '1m':
                    $where['raw'] = ['created_at>DATE_SUB(NOW(),INTERVAL 1 MONTH)'];
                    break;
                case '3m':
                    $where['raw'] = ['created_at>DATE_SUB(NOW(),INTERVAL 3 MONTH)'];
                    break;
                case '6m':
                    $where['raw'] = ['created_at>DATE_SUB(NOW(),INTERVAL 6 MONTH)'];
                    break;
            }
        }
        if ($type) {
            switch ($type) {
                case 'invest':
                    $where['type'] = 1;
                    break;
                case 'income':
                    $where['type'] = 2;
                    break;
                case 'recharge':
                    $where['type'] = 3;
                    break;
                case 'withdraw':
                    $where['type'] = 4;
                    break;
                case 'other':
                    $where['type'] = 0;
                    break;
            }
        }

        list($count, $lists) = $this->userRepository->getRecordList($where, $this->perpage, $page);
        $pageHtml = $this->pager($count, $page, $this->perpage);
        return view('account.wallet.book', compact('lists', 'pageHtml'));
    }
}