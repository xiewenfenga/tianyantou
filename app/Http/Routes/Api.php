<?php
/*********************************************************************************
 *  H5前端 - 路由
 *-------------------------------------------------------------------------------
 * 版权所有: CopyRight By
 * 文件内容简单说明
 *-------------------------------------------------------------------------------
 * $FILE:M.php
 * $Author:wyw
 * $Dtime:2017/3/31
 ***********************************************************************************/

//首页
Route::get('/', ['as' => 'api', 'uses' => 'IndexController@index']);


