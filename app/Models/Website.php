<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    //
    protected $table = "website";
    protected $fillable = [
        'sort', 'type', 'org_id', 'admin_id', 'home', 'introduction', 'information'
    ];
    protected $dateFormat = 'U';

    function org()
    {
        return $this->belongsTo('App\Models\Softorg','org_id','id');
    }

    function admin()
    {
        return $this->belongsTo('App\Administrator','admin_id','id');
    }



}
