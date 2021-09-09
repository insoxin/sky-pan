<?php

namespace app\common\model;

use think\Exception;
use think\Model;
use think\model\concern\SoftDelete;

class Folders extends Model
{

    use SoftDelete;

    protected $deleteTime = 'delete_time';


}