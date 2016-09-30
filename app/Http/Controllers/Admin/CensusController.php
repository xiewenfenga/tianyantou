<?php
/*********************************************************************************
 *  PhpStorm - phpad
 *-------------------------------------------------------------------------------
 * 版权所有: CopyRight By cw100.com
 * 文件内容简单说明
 *-------------------------------------------------------------------------------
 * $FILE:CensusController.php
 * $Author:zxs
 * $Dtime:2016/9/8
 ***********************************************************************************/

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AdminController;
use App\Repositories\CensusRepository;
use Illuminate\Http\Request;

class CensusController extends  AdminController
{
    public function __construct(CensusRepository $census)
    {
        parent::__initalize();
        $this->census = $census;;
    }

    public function index()
    {
        return view('admin.census.index');
    }

    /**
     * @param Request $request
     * 注册统计
     */
    public function register(Request $request)
    {
        $startTime = strtotime('-7 days');
        $data = $this->getCalendar($startTime,time());
        foreach($data as $i=>$item) {
            $data[$i] = $this->census->getRegisterUserStats($item[0],$item[1]);
        }
        $categorys = array_keys($data);
        $census = array_values($data);
        return view('admin.census.register',compact('categorys','census'));
    }

    /**
     * @param $startTime
     * @param $endTime
     * 获取开始和结束的天数
     */
    private function getCalendar($startTime, $endTime)
    {
        $days = ($endTime - $startTime) / (24 * 60 * 60);
        for ($i = 0; $i < $days; $i++) {
            $date = date('Y-m-d',strtotime('+' . $i . 'days', $startTime));
            $data[$date] = [$date . ' 00:00:01', $date . ' 23:59:59'];
        }
        return $data;
    }
}