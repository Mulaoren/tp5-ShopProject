<?php
namespace app\admin\model;
use think\Model;

class Type extends Model{
    // 表的主键字段
	protected $pk = 'type_id';
	// 时间戳自动写入
	protected $autoWriteTimestamp = true;

}