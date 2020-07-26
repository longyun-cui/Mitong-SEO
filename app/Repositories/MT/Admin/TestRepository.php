<?php
namespace App\Repositories\MT\Admin;

use App\Models\MT\User;

use App\Repositories\Common\CommonRepository;
use Response, Auth, Validator, DB, Exception;
use QrCode;

class TestRepository {

    private $model;
    private $repo;
    public function __construct()
    {
        $this->model = new User;
    }


    // 返回（后台）主页视图
    public function index()
    {
    }





}