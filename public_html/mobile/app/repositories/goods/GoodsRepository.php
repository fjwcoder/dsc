<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\repositories\goods;

class GoodsRepository
{
	protected $goods;

	public function __construct(\app\models\Goods $goods)
	{
		$this->goods = $goods;
	}

	public function all($columns = array('*'))
	{
		return $this->goods->all()->toArray();
	}

	public function paginate($perPage = 15, $columns = array('*'))
	{
	}

	public function create(array $data)
	{
	}

	public function update(array $data, $id)
	{
	}

	public function delete($id)
	{
	}

	public function find($id, $columns = array('*'))
	{
		return $this->goods->find($id);
	}

	public function findBy($field, $value, $columns = array('*'))
	{
	}
}


?>
