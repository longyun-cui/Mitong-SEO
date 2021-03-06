<?php
namespace App\Http\Controllers\Org\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Admin\AnswerService;
use App\Repositories\Admin\AnswerRepository;

class AnswerController extends Controller
{
    //
    private $service;
    private $repo;
    public function __construct()
    {
        $this->service = new AnswerService;
        $this->repo = new AnswerRepository;
    }



    public function index()
    {
        return view('admin.answer.index');
    }

    // 数据分析
    public function view_analysis()
    {
        return $this->repo->view_analysis(request()->all());
    }

    // 列表
    public function viewList()
    {
        if(request()->isMethod('get')) return $this->repo->view_list(request()->all());
        else if(request()->isMethod('post')) return $this->repo->get_list_datatable(request()->all());
    }

    // 单次回答详情
    public function view_detail()
    {
        return $this->repo->view_detail(request()->all());
    }




}
