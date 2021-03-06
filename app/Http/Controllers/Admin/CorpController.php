<?php
/*********************************************************************************
 *  PhpStorm - phpad
 *-------------------------------------------------------------------------------
 * 版权所有: CopyRight By cw100.com
 * 文件内容简单说明
 *-------------------------------------------------------------------------------
 * $FILE:CorpController.php
 * $Author:zxs
 * $Dtime:2016/9/8
 ***********************************************************************************/

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AdminController;
use App\Repositories\NewRepository;
use App\Repositories\TaskRepository;
use Illuminate\Http\Request;

class CorpController extends AdminController
{

    public function __construct(
        TaskRepository $taskRepository,
        NewRepository $newRepository
    ) {
        parent::__initalize();
        $this->taskRepository = $taskRepository;
        $this->newRepository = $newRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     * 公司列表
     */
    public function index(Request $request)
    {
        $page = !empty($request->get('page')) ? $request->get('page') : 1;
        list($count, $lists) = $this->taskRepository->getCorpList([], $this->perpage, $page);
        $pageHtml = $this->pager($count,$page, $this->perpage);
        return view('admin.corp.index', compact('lists','pageHtml'));
    }

    /**
     * @return \Illuminate\View\View
     * 创建公司信息
     */
    public function create(Request $request, $id=null)
    {
        if($request->isMethod('post')) {
            $data = $request->get('data');
            if(!empty($data['logo']))
                $data['logo'] = str_replace(config('app.static_url'), '', $data['logo']);
            if(!empty($data['m_logo']))
                $data['m_logo'] = str_replace(config('app.static_url'), '', $data['m_logo']);
            if(!empty($data['chartered']))
                $data['chartered'] = str_replace(config('app.static_url'), '', $data['chartered']);
            $pinyin = app()->make('LibraryManager')->create('pinyin');
            $data['ename'] = $pinyin->convert($data['name']);
            $result = $this->taskRepository->saveCorp($data);
            if($result['status'])
                return $this->success($result['message'],url('corp'),true);
            return $this->error($result['message'],null,true);
        }
        if(!empty($id)) {
            $corp = $this->taskRepository->corpModel->find($id);
            $area[] = !empty($corp->province) ? $corp->province : '省';
            $area[] =  !empty($corp->city) ? $corp->city : '市';
            $area[] = !empty($corp->county) ? $corp->county : '区';
            $area = json_encode($area);
            return view('admin.corp.create',compact('corp','area'));
        }
        return view('admin.corp.create');
    }

    /**
     * @param $id
     * 公司管理信息
     */
    public function manage(Request $request,$id)
    {
        $corp = $this->taskRepository->corpModel->find($id);

        if($request->isMethod('post')) {
            $data = $request->get('data');
            $data['item_type'] = 'App\Models\CorpModel';
            $result = $this->newRepository->saveArticle($data);
            if($result['status']) {
                return $this->success($result['message'], url('corp/manage',['id'=>$id]),true);
            }
            return $this->error($result['message']);
        }
        return view('admin.corp.manage',compact('corp'));
    }

    /**
     * @param $id
     * 公司下团队管理
     */
    public function term($id)
    {
        $corp = $this->taskRepository->corpModel->find($id);
        return view('admin.corp.term',compact('corp'));
    }


    /**
     * @param Request $request
     * @param null $id
     * 创建编辑公司团队成员
     */
    public function termcreate(Request $request, $corpId, $id=null)
    {
        $corp = $this->taskRepository->corpModel->find($corpId);
        if($request->isMethod('post')) {
            $data = $request->get('data');
            $data['corp_id'] = $corpId;
            if(!empty($data['avatar'])) {
                $data['avatar'] = str_replace(config('app.static_url'), '', $data['avatar']);
            }
            $result = $this->taskRepository->saveCorpTerm($data);
            if($result['status'])
                return $this->success('创建/编辑公司团队成员完成',url('corp/term',['id'=>$corpId]),true);
            return $this->error('创建/编辑公司团队成员异常，请联系开发人员');
        }

        if(!empty($id)) {
            $term = $this->taskRepository->corpTermModel->find($id);
        }
        return view('admin.corp.termcreate',compact('corp','term'));
    }

    /**
     * @param $id
     * 删除成员组信息
     */
    public function termdelete($corpId,$id)
    {
        $result = $this->taskRepository->deleteCorpTerm($corpId,$id);
        if($result['status'])
            return $this->success($result['message'], url('corp/term',['id'=>$corpId]));
        return $this->error($result['message']);
    }



    /**
     * @param $id
     * 安全保障
     */
    public function safety(Request $request,$id) {
        $corp = $this->taskRepository->corpModel->find($id);
        if($request->isMethod('post')) {
            $data = $request->get('data');
            $result = $this->taskRepository->saveMeta($id,$data);
            if($result['status'])
                return $this->success('保存安全保障信息完成',url('corp/safety',['id'=>$id]),true);
            return $this->error($result['message'],null,true);
        }
        $metas['icp_domain'] = '';
        $metas['icp_corp_type'] = '';
        $metas['icp_time'] = '';
        $metas['icp_corp_name'] = '';
        $metas['icp_no'] = '';
        $metas['assure'] = '';
        $metas['pattern'] = '';
        if(!empty($corp->metas[0])) {
           $metas = getMetas($corp->metas, $metas);
        }
        return view('admin.corp.safety',compact('corp','metas'));
    }

    /**
     * @param $id
     * 图片资料
     */
    public function photos(Request $request,$id) {
        $corp = $this->taskRepository->corpModel->find($id);
        if($request->isMethod('post')) {
            $data = $request->get('data');
            $result = $this->taskRepository->saveMeta($id,$data);
            if($result['status'])
                return $this->success('保存图片资料完成',url('corp/photos',['id'=>$id]),true);
            return $this->error($result['message'],null,true);
        }
        $metas['credentials'] = '';
        $metas['office_address'] = '';
        if(!empty($corp->metas[0])) {
            $metas = getMetas($corp->metas, $metas);
        }
        return view('admin.corp.photos',compact('corp','metas'));
    }

    /**
     * @param $id
     * 平台动态
     */
    public function news(Request $request,$id) {
        $page = !empty($request->get('page')) ? $request->get('page') : 1;
        $corp = $this->taskRepository->corpModel->find($id);
        $where['corp_id'] = $id;
        $where['category_id'] = 13;
        list($counts, $lists) = $this->newRepository->getNewList($where,$page, $this->perpage);
        $pageHtml = $this->pager($counts,$page, $this->perpage);
        return view('admin.corp.news',compact('corp', 'pageHtml', 'lists'));
    }

    /**
     * @param Request $request
     * @param $corpId
     * 公司动态
     */
    public function dynamic(Request $request , $corpId,$id=null)
    {
        if($request->isMethod('post')) {
            $data = $request->get('data');
            if(!empty($data['brandlogo']))
                $data['brandlogo'] = str_replace(config('app.static_url'), '', $data['brandlogo']);
            $result = $this->newRepository->saveMultiNew($data);
            if($result['status'])
                return $this->success('创建/编辑动态完成', url('corp/news',['id'=>$corpId]),true);
            return $this->error('创建/编辑动态异常，请联系开发人员');
        }

        if(!empty($id)) {
            $new = $this->newRepository->newModel->find($id);
        }
        return view('admin.corp.dynamic',compact('corpId','new'));
    }

    /**
     * @param $corpId
     * @param $id
     * 删除动态信息
     */
    public function newdelete($corpId,$id)
    {
        $result = $this->newRepository->deleteNews($id);
        if($result['status'])
            return $this->success('删除动态完成', url('corp/news',['id'=>$corpId]));
        return $this->error('创建/编辑动态异常，请联系开发人员', url('corp/news',['id'=>$corpId]));
    }


    /**
     * @param $id
     * 雷达图
     */
    public function charts(Request $request,$id) {
        $corp = $this->taskRepository->corpModel->find($id);
        if($request->isMethod('post')) {
            $data = $request->get('data');
            $result = $this->taskRepository->saveMeta($id,$data);
            if($result['status'])
                return $this->success('保存雷达图信息完成',url('corp/charts',['id'=>$id]),true);
            return $this->error($result['message'],null,true);
        }
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

        return view('admin.corp.charts',compact('corp','metas'));
    }

    /**
     * @param Request $request
     * @param $id
     * 企业荣誉管理
     */
    public function honour(Request $request, $id)
    {
        $corp = $this->taskRepository->corpModel->find($id);
        if($request->isMethod('post')) {
            $data = $request->get('data');
            $result = $this->taskRepository->saveMeta($id,$data);
            if($result['status'])
                return $this->success('保存荣誉信息完成',url('corp/honour',['id'=>$id]),true);
            return $this->error($result['message'],null,true);
        }
        $metas['honour_corp_1'] = '';
        $metas['honour_corp_2'] = '';
        $metas['honour_corp_3'] = '';
        $metas['honour_1'] = '';
        $metas['honour_2'] = '';
        $metas['honour_3'] = '';
        if(!empty($corp->metas[0])) {
            $metas = getMetas($corp->metas, $metas);
        }
        return view('admin.corp.honour',compact('corp','metas'));
    }

	/**
     * @param Request $request
     * @param $id
     * 档案管理
     */
    public function archives(Request $request, $id)
    {
        $corp = $this->taskRepository->corpModel->find($id);
        if($request->isMethod('post')) {
            $data = $request->get('data');
            $result = $this->taskRepository->saveMeta($id,$data);
            if($result['status'])
                return $this->success('保存档案信息完成',url('corp/archives',['id'=>$id]),true);
            return $this->error($result['message'],null,true);
        }
        $metas['icp_invest_cost'] = '';
		$metas['icp_cash_in'] = '';
		$metas['icp_cash_door'] = '';
		$metas['icp_vip_cost'] = '';
		$metas['icp_carry_time'] = '';
		$metas['icp_payment_time'] = '';
		$metas['icp_custody'] = '';
		$metas['icp_overdue_ensure'] = '';
		$metas['icp_overdue_pay'] = '';
		$metas['icp_bond'] = '';
        if(!empty($corp->metas[0])) {
            $metas = getMetas($corp->metas, $metas);
        }
        return view('admin.corp.archives',compact('corp','metas'));
    }

}