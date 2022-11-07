<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/5 0005
 * Time: 9:40
 */

namespace App\Http\Controllers\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\CompareFaceService;
use App\Http\Service\TencentService;
use App\Models\CompareFace;

class CompareFaceController extends Controller
{
    /**
     * 进行照片比对
     *
     * @author yezi
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws ApiException
     */
    public function store()
    {
        $user     = request()->input('user');
        $yourFace = request()->input('your_face');
        $hisFace  = request()->input('his_face');
        $collegeId= request()->input("college_id");

        if(empty($yourFace) || empty($hisFace)){
            throw new ApiException('照片不能为空',500);
        }

        $compareService = app(CompareFaceService::class);
        $compareResult = app(TencentService::class)->compareFace($yourFace,$hisFace);
        if($compareResult){
            $result = $compareService->create($user->id,$yourFace,$hisFace,CompareFace::ENUM_STATUS_SUCCESS,$compareResult);
            if($result){
                $report = $compareService->report($compareResult);
                return $report;
            }else{
                throw new ApiException('比对失败，请稍后再试！',500);
            }
        }else{
            throw new ApiException('比对失败，请稍后再试！',500);
        }

    }
}