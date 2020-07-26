<?php
namespace App\Models\MT;
use Illuminate\Database\Eloquent\Model;

class EPDir extends Model
{
    //
    protected $table = "epdir";

    protected $fillable = [
        'status', 'category', 'sort', 'type', 'mobile', 'email', 'password', 'name', 'true_name', 'nickname',
        'org_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dateFormat = 'U';




    function user()
    {
        return $this->belongsTo('App\Models\MT\User','epid','id');
    }


}
