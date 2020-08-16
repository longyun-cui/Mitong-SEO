<?php
namespace App\Models\TEST;
use Illuminate\Database\Eloquent\Model;

class Temp extends Model
{
    //
    protected $connection = 'mysql2';

    protected $table = "test_temp";

    protected $fillable = [
        'sort', 'type', 'sort', 'org_id', 'admin_id', 'menu_id', 'active', 'itemable_id', 'itemable_type',
        'title', 'subtitle', 'content', 'custom', 'custom2', 'custom3'
    ];
    protected $dateFormat = 'U';

    /**
     * 获得拥有此条目的模型。
     */
    public function itemable()
    {
        return $this->morphTo();
    }



}
