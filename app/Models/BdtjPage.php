<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-03-26
 * Time: 11:50
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BdtjPage extends Model
{

    protected $primaryKey = "page_id";
    public $incrementing = "false";
    protected $keyType = "string";
}