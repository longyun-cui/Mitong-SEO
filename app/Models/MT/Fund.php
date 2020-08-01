<?php
namespace App\Models\MT;
use Illuminate\Database\Eloquent\Model;


class Fund extends Model
{
    //
    protected $table = "funds";

    protected $fillable = [
        'owner_id', 'createuserid', 'createusername', 'createtime',
        'userid', 'usertype', 'productid', 'unit',
        'totalfunds', 'balancefunds', 'availablefunds', 'initfreezefunds', 'freezefunds',
        'status', 'readpriv', 'writepriv', 'notes', 'reguser', 'regtime', 'moduser', 'modtime',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dateFormat = 'U';




    function user()
    {
        return $this->belongsTo('App\Models\MT\User','userid','id');
    }


}
