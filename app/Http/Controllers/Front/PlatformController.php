<?php
/*********************************************************************************
 *  平台控制器
 *-------------------------------------------------------------------------------
 * 版权所有: CopyRight By phpad
 * 首页控制器内容简单说明
 *-------------------------------------------------------------------------------
 * $FILE:PlatformController.php
 * $Author:pzz
 * $Dtime:2016/9/7
 ***********************************************************************************/

namespace App\Http\Controllers\Front;


use App\Http\Controllers\FrontController;
use App\Repositories\NewRepository;
use App\Repositories\TaskRepository;
use Illuminate\Http\Request;

class PlatformController extends FrontController
{
    public function __construct(
        TaskRepository $tasks,
        NewRepository $news
    ) {
        parent::__initalize();
        $this->tasks = $tasks;
        $this->news = $news;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 精选平台套用
     */
    public function index(Request $request)
    {
        $page = !empty($request->get('page')) ? $request->get('page') : 1;
        $where['status'] = 1;
        list($counts, $lists) = $this->tasks->getCorpList($where, $this->perpage,$page);
        return view('front.platform.index', compact('lists'));
    }



    /**
     * @param Request $request
     * ajax获取查询列表
     */
    public function plists(Request $request)
    {
        $from = $request->get('from');
        $page = $request->get('page');
        $sortType = $request->get('sorttype');
        $sortOrder = $request->get('sortorder');

        $corp = $this->tasks->getCorpByEname($from);
        $order = [];
        if($sortType == 1) {
            $order['ratio'] = $sortOrder;
        }
        if($sortType == 3 ) {
            $order['proccess'] = $sortOrder;
        }
        $where['status'] = 1;
        $where['corp_id'] = $corp->id;
        list($counts, $lists) = $this->tasks->getTaskList($where,5, $page,0,$order);
        $result['total'] = $counts;
        $result['page']  = $page;
        $html = view('front.platform.plists', compact('lists'))->render();
        $html = str_replace('\r','', $html);
        $html = str_replace("\n","", $html);
        $result['bidstr'] = $html;
        return $this->ajaxReturn($result);

    }

    /**
     * @param $ename
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 单平台数据
     */
    public function corp($ename)
    {;
        $corp = $this->tasks->getCorpByEname($ename);
        $metas['icp_domain'] = '';
        $metas['icp_corp_type'] = '';
        $metas['icp_time'] = '';
        $metas['icp_corp_name'] = '';
        $metas['icp_no'] = '';
        $metas['credentials'] = '';
        $metas['office_address'] = '';
        //资本充足率
        $metas['capital_adequacy'] = '';
        //运营能力比率
        $metas['operating_capacity'] = '';
        //流动性
        $metas['flowability'] = '';
        //分散率
        $metas['dissemination'] = '';
        //透明去
        $metas['transparency'] = '';
        //违约比率
        $metas['contract_rate'] = '';
        if(!empty($corp->metas[0])) {
            $metas = getMetas($corp->metas, $metas);
        }

        return view('front.platform.corp',compact('corp','metas'));
    }
}