<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
function order_download_list($result)
{
	if (empty($result)) {
		return i('没有符合您要求的数据！^_^');
	}

	$data = i('订单编号,下单时间,收货人,总金额,折扣,优惠券,积分,红包,退款金额,有效分成金额,订单状态,佣金金额,结算状态,冻结状态' . "\n");
	$count = count($result);

	for ($i = 0; $i < $count; $i++) {
		$order_sn = i($result[$i]['order_sn']);
		$short_order_time = i($result[$i]['short_order_time']);
		$consignee = i($result[$i]['consignee']) . '' . i($result[$i]['address']);
		$total_fee_price = i($result[$i]['order_amount_field']);
		$discount = i($result[$i]['discount']);
		$coupons = i($result[$i]['coupons']);
		$integral_money = i($result[$i]['integral_money']);
		$bonus = i($result[$i]['bonus']);
		$return_amount_price = i($result[$i]['return_amount_price']);
		$brokerage_amount_price = i($result[$i]['brokerage_amount_price']);
		$effective_amount_price = i($result[$i]['effective_amount_price']);
		$is_settlement = i($result[$i]['settlement_status']);
		$status = i($result[$i]['ordersTatus']);
		$is_frozen = i($result[$i]['settlement_frozen']);
		$data .= $order_sn . ',' . $short_order_time . ',' . $consignee . ',' . $total_fee_price . ',' . $discount . ',' . $coupons . ',' . $integral_money . ',' . $bonus . ',' . $return_amount_price . ',' . $brokerage_amount_price . ',' . $status . ',' . $effective_amount_price . ',' . $is_settlement . ',' . $is_frozen . "\n";
	}

	return $data;
}

function merchants_commission_list()
{
	$adminru = get_admin_ru_id();
	$result = get_filter();

	if ($result === false) {
		$aiax = (isset($_GET['is_ajax']) ? $_GET['is_ajax'] : 0);
		$filter['sort_by'] = empty($_REQUEST['sort_by']) ? 's.server_id' : trim($_REQUEST['sort_by']);
		$filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'ASC' : trim($_REQUEST['sort_order']);
		$where = 'WHERE 1 ';

		if (0 < $adminru['ru_id']) {
			$where .= ' AND s.user_id = \'' . $adminru['ru_id'] . '\'';
		}

		$filter['store_search'] = empty($_REQUEST['store_search']) ? 0 : intval($_REQUEST['store_search']);
		$filter['merchant_id'] = isset($_REQUEST['merchant_id']) ? intval($_REQUEST['merchant_id']) : 0;
		$filter['store_keyword'] = isset($_REQUEST['store_keyword']) ? trim($_REQUEST['store_keyword']) : '';
		$store_search_where = '';

		if ($filter['store_search'] != 0) {
			if ($adminru['ru_id'] == 0) {
				if ($_REQUEST['store_type']) {
					$store_search_where = 'AND msi.shopNameSuffix = \'' . $_REQUEST['store_type'] . '\'';
				}

				if ($filter['store_search'] == 1) {
					$where .= ' AND mis.user_id = \'' . $filter['merchant_id'] . '\' ';
				}
				else if ($filter['store_search'] == 2) {
					$where .= ' AND mis.rz_shopName LIKE \'%' . mysql_like_quote($filter['store_keyword']) . '%\'';
				}
				else if ($filter['store_search'] == 3) {
					$where .= ' AND mis.shoprz_brandName LIKE \'%' . mysql_like_quote($filter['store_keyword']) . '%\' ' . $store_search_where;
				}
			}
		}

		$where .= ' AND mis.merchants_audit = 1 ';
		$filter['page'] = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);
		if (isset($_REQUEST['page_size']) && (0 < intval($_REQUEST['page_size']))) {
			$filter['page_size'] = intval($_REQUEST['page_size']);
		}
		else {
			if (isset($_COOKIE['ECSCP']['page_size']) && (0 < intval($_COOKIE['ECSCP']['page_size']))) {
				$filter['page_size'] = intval($_COOKIE['ECSCP']['page_size']);
			}
			else {
				$filter['page_size'] = 15;
			}
		}

		$sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('merchants_server') . ' as s ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('merchants_shop_information') . ' as mis on s.user_id = mis.user_id ' . $where;
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);
		$filter['page_count'] = 0 < $filter['record_count'] ? ceil($filter['record_count'] / $filter['page_size']) : 1;
		$sql = "SELECT u.user_name, mis.*, msf.*, s.server_id, s.user_id, s.suppliers_desc, s.suppliers_percent  \r\n                FROM " . $GLOBALS['ecs']->table('merchants_server') . ' as s ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('merchants_shop_information') . ' as mis on s.user_id = mis.user_id ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('users') . ' as u on s.user_id = u.user_id ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('merchants_steps_fields') . ' as msf on s.user_id = msf.user_id ' . ' ' . $where . ' ' . ' group by s.user_id ' . ' ORDER BY ' . $filter['sort_by'] . ' ' . $filter['sort_order'];
		$sql .= ' LIMIT ' . (($filter['page'] - 1) * $filter['page_size']) . ', ' . $filter['page_size'] . ' ';
		set_filter($filter, $sql);
	}
	else {
		$sql = $result['sql'];
		$filter = $result['filter'];
	}

	$row = $GLOBALS['db']->getAll($sql);
	$count = count($row);

	for ($i = 0; $i < $count; $i++) {
		$row[$i]['server_id'] = $row[$i]['server_id'];
		$valid = get_merchants_order_valid_refund($row[$i]['user_id']);
		$row[$i]['order_valid_total'] = price_format($valid['total_fee']);

		if (file_exists(MOBILE_DRP)) {
			$row[$i]['order_drp_commission'] = price_format($valid['drp_money']);
		}

		$refund = get_merchants_order_valid_refund($row[$i]['user_id'], 1);
		$row[$i]['order_refund_total'] = price_format($refund['total_fee']);
		$row[$i]['store_name'] = get_shop_name($row[$i]['user_id'], 1);

		if (file_exists(MOBILE_DRP)) {
			$is_settlement = merchants_is_settlement($row[$i]['user_id'], 1);
			$row[$i]['is_settlement'] = $is_settlement['all'];
			$no_settlement = merchants_is_settlement($row[$i]['user_id'], 0);
			$row[$i]['no_settlement'] = $no_settlement['all'];
		}
		else {
			$is_settlement = merchants_is_settlement($row[$i]['user_id'], 1);
			$row[$i]['is_settlement'] = price_format($is_settlement);
			$no_settlement = merchants_is_settlement($row[$i]['user_id'], 0);
			$row[$i]['no_settlement'] = price_format($no_settlement);
		}

		$row[$i]['total_fee_price'] = number_format($valid['total_fee'], 2, '.', '');
		$row[$i]['total_fee_refund'] = number_format($refund['total_fee'], 2, '.', '');
		$row[$i]['is_settlement_price'] = $is_settlement;
		$row[$i]['no_settlement_price'] = $no_settlement;
		$sql = 'SELECT ss.shop_name, ss.shop_address, ss.mobile, ' . 'concat(IFNULL(p.region_name, \'\'), ' . '\'  \', IFNULL(t.region_name, \'\'), \'  \', IFNULL(d.region_name, \'\')) AS region ' . ' FROM ' . $GLOBALS['ecs']->table('seller_shopinfo') . ' AS ss ' . 'LEFT JOIN ' . $GLOBALS['ecs']->table('region') . ' AS p ON ss.province = p.region_id ' . 'LEFT JOIN ' . $GLOBALS['ecs']->table('region') . ' AS t ON ss.city = t.region_id ' . 'LEFT JOIN ' . $GLOBALS['ecs']->table('region') . ' AS d ON ss.district = d.region_id ' . ' WHERE ss.ru_id = \'' . $row[$i]['user_id'] . '\' LIMIT 1';
		$seller_shopinfo = $GLOBALS['db']->getRow($sql);

		if ($seller_shopinfo['shop_name']) {
			$row[$i]['companyName'] = $seller_shopinfo['shop_name'];
			$row[$i]['company_adress'] = '[' . $seller_shopinfo['region'] . '] ' . $seller_shopinfo['shop_address'];
		}

		if ($seller_shopinfo['mobile']) {
			$row[$i]['company_contactTel'] = $seller_shopinfo['mobile'];
		}
		else {
			$row[$i]['company_contactTel'] = $row[$i]['contactPhone'];
		}
	}

	$arr = array('result' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
	return $arr;
}

function get_suppliers_percent()
{
	$sql = 'select percent_id, percent_value from ' . $GLOBALS['ecs']->table('merchants_percent') . ' where 1 order by sort_order asc';
	$res = $GLOBALS['db']->getAll($sql);
	return $res;
}

function merchants_order_list()
{
	$result = get_filter();

	if ($result === false) {
		$aiax = (isset($_GET['is_ajax']) ? $_GET['is_ajax'] : 0);
		$filter['id'] = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
		$filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'o.order_id' : trim($_REQUEST['sort_by']);
		$filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
		$filter['start_time'] = empty($_REQUEST['start_time']) ? '' : local_strtotime(trim($_REQUEST['start_time']));
		$filter['end_time'] = empty($_REQUEST['end_time']) ? '' : local_strtotime(trim($_REQUEST['end_time']));
		$filter['state'] = isset($_REQUEST['state']) ? trim($_REQUEST['state']) : '';
		$where = 'WHERE 1';
		if (isset($filter['state']) && ($filter['state'] != '')) {
			$where .= ' AND is_settlement = \'' . $filter['state'] . '\' ';
		}

		if (!empty($filter['start_time'])) {
			$where .= ' AND o.add_time >= \'' . $filter['start_time'] . '\' ';
		}

		if (!empty($filter['end_time'])) {
			$where .= ' AND o.add_time <= \'' . $filter['end_time'] . '\' ';
		}

		$filter['page'] = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);
		if (isset($_REQUEST['page_size']) && (0 < intval($_REQUEST['page_size']))) {
			$filter['page_size'] = intval($_REQUEST['page_size']);
		}
		else {
			if (isset($_COOKIE['ECSCP']['page_size']) && (0 < intval($_COOKIE['ECSCP']['page_size']))) {
				$filter['page_size'] = intval($_COOKIE['ECSCP']['page_size']);
			}
			else {
				$filter['page_size'] = 15;
			}
		}

		$where .= order_query_sql('finished', 'o.');
		$where .= ' and (select count(*) from ' . $GLOBALS['ecs']->table('order_info') . ' as oi2 where oi2.main_order_id = o.order_id) = 0 ';
		$where .= ' AND (SELECT og.ru_id FROM ' . $GLOBALS['ecs']->table('order_goods') . ' as og' . ' WHERE og.order_id = o.order_id LIMIT 1) = \'' . $filter['id'] . '\' ';
		$sql = 'SELECT o.order_id ' . ' FROM ' . $GLOBALS['ecs']->table('order_info') . ' AS o ' . $where;
		$filter['record_count'] = count($GLOBALS['db']->getAll($sql));
		$filter['page_count'] = 0 < $filter['record_count'] ? ceil($filter['record_count'] / $filter['page_size']) : 1;
		$sql = 'SELECT o.is_frozen,o.order_id, o.main_order_id, o.order_sn, o.add_time, o.order_status, o.shipping_status, o.order_amount, o.money_paid, o.is_delete, o.is_settlement,' . 'o.shipping_time, o.auto_delivery_time, o.pay_status, o.consignee, o.address, o.email, o.tel, o.extension_code, o.extension_id, o.goods_amount, o.shipping_fee, ' . '(' . order_commission_field('o.') . ') AS total_fee, o.discount,o.coupons,o.integral_money,o.bonus,' . order_amount_field('o.') . ' AS order_amount_field,' . 'IFNULL(u.user_name, \'' . $GLOBALS['_LANG']['anonymous'] . '\') AS buyer ' . ' FROM ' . $GLOBALS['ecs']->table('order_info') . ' AS o ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('users') . ' as u on o.user_id = u.user_id ' . $where . ' ORDER BY ' . $filter['sort_by'] . ' ' . $filter['sort_order'] . ' ';
		$sql .= ' LIMIT ' . (($filter['page'] - 1) * $filter['page_size']) . ', ' . $filter['page_size'] . ' ';
		set_filter($filter, $sql);
	}
	else {
		$sql = $result['sql'];
		$filter = $result['filter'];
	}

	$row = $GLOBALS['db']->getAll($sql);
	$count = count($row);

	for ($i = 0; $i < $count; $i++) {
		$row[$i]['formated_order_amount'] = price_format($row[$i]['order_amount'], true);
		$row[$i]['formated_money_paid'] = price_format($row[$i]['money_paid'], true);
		$row[$i]['formated_total_fee'] = price_format($row[$i]['total_fee'], true);
		$row[$i]['short_order_time'] = local_date($GLOBALS['_CFG']['time_format'], $row[$i]['add_time']);
		$row[$i]['ordersTatus'] = $GLOBALS['_LANG']['os'][$row[$i]['order_status']] . '|' . $GLOBALS['_LANG']['ps'][$row[$i]['pay_status']] . '|' . $GLOBALS['_LANG']['ss'][$row[$i]['shipping_status']];
		$row[$i]['formated_discount'] = price_format($row[$i]['discount'], true);
		$row[$i]['formated_coupons'] = price_format($row[$i]['coupons'], true);
		$row[$i]['formated_integral_money'] = price_format($row[$i]['integral_money'], true);
		$row[$i]['formated_bonus'] = price_format($row[$i]['bonus'], true);
		$row[$i]['formated_order_amount_field'] = price_format($row[$i]['order_amount_field'], true);
		$row[$i]['formated_shipping_fee'] = price_format($row[$i]['shipping_fee'], true);

		if ($row[$i]['is_settlement']) {
			$row[$i]['settlement_status'] = '已结算';
		}
		else {
			$row[$i]['settlement_status'] = '未结算';
		}

		$row[$i]['settlement_frozen'] = '';

		if ($row[$i]['is_frozen']) {
			$row[$i]['settlement_frozen'] = '冻结';
		}

		$date = array('suppliers_percent');
		$percent_id = get_table_date('merchants_server', 'user_id = \'' . $filter['id'] . '\' ', $date, $sqlType = 2);
		$date = array('percent_value');
		$percent_value = get_table_date('merchants_percent', 'percent_id = \'' . $percent_id . '\'', $date, $sqlType = 2);

		if ($percent_value == 0) {
			$percent_value = 1;
		}
		else {
			$percent_value = $percent_value / 100;
		}

		$row[$i]['consignee'] = '【' . $row[$i]['consignee'] . '】';
		$row[$i]['return_amount'] = get_order_return_list($row[$i]['order_id']);
		$row[$i]['return_amount'] = !empty($row[$i]['return_amount']) ? $row[$i]['return_amount'] : '0.00';
		$row[$i]['formated_return_amount'] = price_format($row[$i]['return_amount'], true);

		if (file_exists(MOBILE_DRP)) {
			$brokerage_amount = get_order_drp_money($row[$i]['total_fee'], $filter['id'], $row[$i]['order_id']);

			if ($GLOBALS['_CFG']['commission_model']) {
				$order_goods_commission = get_order_goods_commission($row[$i]['order_id']);

				if ($row[$i]['goods_amount'] <= 0) {
					$row[$i]['goods_amount'] = 1;
				}

				$order_commission = ($order_goods_commission * ($brokerage_amount['total_fee'] - $row[$i]['return_amount'])) / $row[$i]['goods_amount'];
				$row[$i]['formated_brokerage_amount'] = price_format($order_commission + $row[$i]['shipping_fee'], true);
				$row[$i]['effective_amount_price'] = $order_commission;
			}
			else {
				$row[$i]['formated_brokerage_amount'] = price_format((($brokerage_amount['total_fee'] - $row[$i]['return_amount']) * $percent_value) + $row[$i]['shipping_fee'], true);
				$row[$i]['effective_amount_price'] = ($brokerage_amount['total_fee'] - $row[$i]['return_amount']) * $percent_value;
			}

			$row[$i]['formated_effective_amount'] = price_format($brokerage_amount['total_fee'] - $row[$i]['return_amount'], true);
			$row[$i]['formated_drp_commission'] = price_format($row[$i]['drp_money'], true);
			$row[$i]['brokerage_amount_price'] = $brokerage_amount['total_fee'] - $row[$i]['return_amount'];
			$row[$i]['formated_drp_commission'] = price_format($brokerage_amount['drp_money'], true);
		}
		else {
			if ($GLOBALS['_CFG']['commission_model']) {
				$order_goods_commission = get_order_goods_commission($row[$i]['order_id']);

				if ($row[$i]['goods_amount'] <= 0) {
					$row[$i]['goods_amount'] = 1;
				}

				$order_commission = ($order_goods_commission * ($row[$i]['total_fee'] - $row[$i]['return_amount'])) / $row[$i]['goods_amount'];
				$row[$i]['formated_brokerage_amount'] = price_format($order_commission + $row[$i]['shipping_fee'], true);
				$row[$i]['effective_amount_price'] = $order_commission;
			}
			else {
				$row[$i]['formated_brokerage_amount'] = price_format((($row[$i]['total_fee'] - $row[$i]['return_amount']) * $percent_value) + $row[$i]['shipping_fee'], true);
				$row[$i]['effective_amount_price'] = ($row[$i]['total_fee'] - $row[$i]['return_amount']) * $percent_value;
			}

			$row[$i]['formated_effective_amount'] = price_format($row[$i]['total_fee'] - $row[$i]['return_amount'], true);
			$row[$i]['brokerage_amount_price'] = $row[$i]['total_fee'] - $row[$i]['return_amount'];
		}

		$row[$i]['formated_effective_amount_price'] = price_format($row[$i]['effective_amount_price'], true);
		$row[$i]['total_fee_price'] = $row[$i]['total_fee'];
		$row[$i]['return_amount_price'] = $row[$i]['return_amount'];
	}

	if ($count) {
		if (file_exists(MOBILE_DRP)) {
			$is_settlement = merchants_is_settlement($filter['id'], 1);
			$is_settlement = $is_settlement['all'];
			$no_settlement = merchants_is_settlement($filter['id'], 0);
			$no_settlement = $no_settlement['all'];
			$all_commission = merchants_is_settlement($filter['id']);
			$row['brokerage_amount']['all'] = $all_commission['all'];
			$row['brokerage_amount']['all_drp'] = $all_commission['all_drp'];
			$row['brokerage_amount']['is_settlement'] = $is_settlement;
			$row['brokerage_amount']['no_settlement'] = $no_settlement;
		}
		else {
			$is_settlement = merchants_is_settlement($filter['id'], 1);
			$no_settlement = merchants_is_settlement($filter['id'], 0);
			$all_commission = merchants_is_settlement($filter['id']);
			$row['brokerage_amount']['is_settlement'] = price_format($is_settlement, true);
			$row['brokerage_amount']['no_settlement'] = price_format($no_settlement, true);
			$row['brokerage_amount']['all'] = price_format($all_commission, true);
			$row['brokerage_amount']['is_settlement_price'] = $is_settlement;
			$row['brokerage_amount']['no_settlement_price'] = $no_settlement;
		}
	}

	$arr = array('orders' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
	return $arr;
}

function get_merchants_order_valid_refund($ru_id, $type = 0)
{
	$where = order_query_sql('finished', 'oi.');

	if ($type == 1) {
		$sql = 'SELECT SUM(oreturn.actual_return) AS total_fee FROM ' . $GLOBALS['ecs']->table('order_info') . ' as oi,' . $GLOBALS['ecs']->table('order_return') . ' as oreturn ' . ' WHERE 1' . ' ' . $where . ' AND oi.order_id = oreturn.order_id AND oreturn.back = 1 AND (SELECT og.ru_id FROM ' . $GLOBALS['ecs']->table('order_goods') . ' as og' . ' WHERE og.order_id = oi.order_id limit 0, 1) = \'' . $ru_id . '\'' . ' AND (select count(*) from ' . $GLOBALS['ecs']->table('order_info') . ' as oi2 where oi2.main_order_id = oi.order_id limit 0, 1) = 0';
		$res = $GLOBALS['db']->getRow($sql);
	}
	else {
		$total_fee = 'SUM((' . order_commission_field('oi.') . ')) AS total_fee ';
		$sql = 'SELECT oi.order_id, oi.order_sn, ' . $total_fee . '  FROM ' . $GLOBALS['ecs']->table('order_info') . ' as oi ' . ' WHERE 1 ' . $where . ' AND (SELECT og.ru_id FROM ' . $GLOBALS['ecs']->table('order_goods') . ' as og' . ' WHERE og.order_id = oi.order_id LIMIT 1) = \'' . $ru_id . '\'' . ' AND (select count(*) from ' . $GLOBALS['ecs']->table('order_info') . ' as oi2 where oi2.main_order_id = oi.order_id LIMIT 1) = 0 LIMIT 1';
		$res = $GLOBALS['db']->getRow($sql);
		if (file_exists(MOBILE_DRP) && $res) {
			$order_drp = get_order_drp_money($res['total_fee'], $ru_id);
			$res['total_fee'] = $order_drp['total_fee'];
			$res['drp_money'] = $order_drp['drp_money'];
		}
	}

	return $res;
}

function get_order_return_list($order_id, $type = 0)
{
	$sql = 'SELECT SUM(actual_return) FROM ' . $GLOBALS['ecs']->table('order_return') . ' WHERE order_id = \'' . $order_id . '\'';
	$actual_return = $GLOBALS['db']->getOne($sql);
	return $actual_return;
}

function merchants_order_list_checked($ids)
{
	$where = 'WHERE 1';
	$where .= ' and o.is_settlement = 0 ';
	$where .= ' and o.order_id ' . db_create_in($ids);
	$where .= order_query_sql('finished', 'o.');
	$where .= ' and (select count(*) from ' . $GLOBALS['ecs']->table('order_info') . ' as oi2 where oi2.main_order_id = o.order_id) = 0 ';
	$sql = 'SELECT o.order_id, o.main_order_id FROM ' . $GLOBALS['ecs']->table('order_info') . ' AS o ' . $where;
	$row = $GLOBALS['db']->getAll($sql);
	return $row;
}

function merchants_is_settlement($ru_id = 0, $state = '')
{
	$where = 'WHERE 1';

	if (is_numeric($state)) {
		$where .= ' AND o.is_settlement = \'' . $state . '\' ';
	}

	$where .= order_query_sql('finished', 'o.');
	$where .= ' AND (SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('order_info') . ' AS oi2 WHERE oi2.main_order_id = o.order_id) = 0 ';
	$where .= ' AND (SELECT og.ru_id FROM ' . $GLOBALS['ecs']->table('order_goods') . ' AS og WHERE o.order_id = og.order_id LIMIT 1) = \'' . $ru_id . '\' ';
	$sql = 'SELECT o.order_id, o.main_order_id, o.order_sn, o.add_time, o.order_status, o.shipping_status, o.order_amount, o.money_paid, o.is_delete, o.is_settlement,' . 'o.shipping_time, o.auto_delivery_time, o.pay_status, o.consignee, o.address, o.email, o.tel, o.extension_code, o.extension_id, o.goods_amount, o.shipping_fee, ' . '(' . order_commission_field('o.') . ') AS total_fee, ' . 'IFNULL(u.user_name, \'' . $GLOBALS['_LANG']['anonymous'] . '\') AS buyer ' . ' FROM ' . $GLOBALS['ecs']->table('order_info') . ' AS o ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('users') . ' AS u ON u.user_id=o.user_id ' . $where;
	$row = $GLOBALS['db']->getAll($sql);
	$count = count($row);

	for ($i = 0; $i < $count; $i++) {
		$row[$i]['formated_order_amount'] = price_format($row[$i]['order_amount'], true);
		$row[$i]['formated_money_paid'] = price_format($row[$i]['money_paid'], true);
		$row[$i]['formated_total_fee'] = price_format($row[$i]['total_fee'], true);
		$row[$i]['short_order_time'] = local_date($GLOBALS['_CFG']['time_format'], $row[$i]['add_time']);
		$date = array('suppliers_percent');
		$percent_id = get_table_date('merchants_server', 'user_id = \'' . $ru_id . '\' ', $date, $sqlType = 2);
		$date = array('percent_value');
		$percent_value = get_table_date('merchants_percent', 'percent_id = \'' . $percent_id . '\'', $date, $sqlType = 2);

		if ($percent_value == 0) {
			$percent_value = 1;
		}
		else {
			$percent_value = $percent_value / 100;
		}

		$row[$i]['return_amount'] = get_order_return_list($row[$i]['order_id']);
		$row[$i]['formated_return_amount'] = price_format($row[$i]['return_amount'], true);

		if (file_exists(MOBILE_DRP)) {
			$brokerage_amount = get_order_drp_money($row[$i]['total_fee'], $ru_id, $row[$i]['order_id']);
			$row[$i]['formated_brokerage_amount'] = price_format(($brokerage_amount['total_fee'] - $row[$i]['return_amount']) * $percent_value, true);
			$row[$i]['formated_effective_amount'] = price_format($brokerage_amount['total_fee'] - $row[$i]['return_amount'], true);

			if ($GLOBALS['_CFG']['commission_model']) {
				$order_goods_commission = get_order_goods_commission($row[$i]['order_id']);

				if ($row[$i]['goods_amount'] <= 0) {
					$row[$i]['goods_amount'] = 1;
				}

				$order_commission = ($order_goods_commission * ($brokerage_amount['total_fee'] - $row[$i]['return_amount'])) / $row[$i]['goods_amount'];
				$row['all_brokerage_amount'] += $order_commission + $row[$i]['shipping_fee'];
			}
			else {
				$row['all_brokerage_amount'] += (($brokerage_amount['total_fee'] - $row[$i]['return_amount']) * $percent_value) + $row[$i]['shipping_fee'];
			}

			$row['all_drp'] += $brokerage_amount['drp_money'];
		}
		else {
			$row[$i]['formated_brokerage_amount'] = price_format(($row[$i]['total_fee'] - $row[$i]['return_amount']) * $percent_value, true);
			$row[$i]['formated_effective_amount'] = price_format($row[$i]['total_fee'] - $row[$i]['return_amount'], true);

			if ($GLOBALS['_CFG']['commission_model']) {
				$order_goods_commission = get_order_goods_commission($row[$i]['order_id']);

				if ($row[$i]['goods_amount'] <= 0) {
					$row[$i]['goods_amount'] = 1;
				}

				$order_commission = ($order_goods_commission * ($row[$i]['total_fee'] - $row[$i]['return_amount'])) / $row[$i]['goods_amount'];
				$row['all_brokerage_amount'] += $order_commission + $row[$i]['shipping_fee'];
			}
			else {
				$row['all_brokerage_amount'] += (($row[$i]['total_fee'] - $row[$i]['return_amount']) * $percent_value) + $row[$i]['shipping_fee'];
			}
		}
	}

	if (file_exists(MOBILE_DRP)) {
		$row['all'] = price_format($row['all_brokerage_amount'], true);
		$row['all_drp'] = price_format($row['all_drp'], true);
		return $row;
	}
	else {
		return number_format($row['all_brokerage_amount'], 2, '.', '');
	}
}

function findNum($str = '')
{
	$str = trim($str);

	if (empty($str)) {
		return '';
	}

	$temp = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
	$result = '';

	for ($i = 0; $i < strlen($str); $i++) {
		if (in_array($str[$i], $temp)) {
			$result .= $str[$i];
		}
	}

	if ($result == '000') {
		$result = 0;
	}

	return $result;
}

function changeSettlement($str)
{
	if ($str == '0') {
		$str = '未结算';
	}
	else {
		$str = '已结算';
	}

	return $str;
}

function commission_download_list($result)
{
	if (empty($result)) {
		return i('没有符合您要求的数据！^_^');
	}

	$data = i('商家名称,店铺名称,公司名称,公司地址,联系方式,订单有效总金额,订单退款总金额,已结算订单金额,未结算订单金额' . "\n");
	$count = count($result);

	for ($i = 0; $i < $count; $i++) {
		$user_name = i($result[$i]['user_name']);
		$store_name = i($result[$i]['store_name']);
		$companyName = i($result[$i]['companyName']);
		$company_adress = i($result[$i]['company_adress']);
		$company_contactTel = i($result[$i]['company_contactTel']);
		$order_valid_total = i($result[$i]['total_fee_price']);
		$order_refund_total = i($result[$i]['total_fee_refund']);
		$is_settlement = i(isset($result[$i]['is_settlement_price']['all_brokerage_amount']) ? $result[$i]['is_settlement_price']['all_brokerage_amount'] : 0);
		$no_settlement = i(isset($result[$i]['no_settlement_price']['all_brokerage_amount']) ? $result[$i]['no_settlement_price']['all_brokerage_amount'] : 0);
		$data .= $user_name . ',' . $store_name . ',' . $companyName . ',' . $company_adress . ',' . $company_contactTel . ',' . $order_valid_total . ',' . $order_refund_total . ',' . $is_settlement . ',' . $no_settlement . "\n";
	}

	return $data;
}

function i($strInput)
{
	return iconv('utf-8', 'gb2312', $strInput);
}

function get_gift_gard_log($id = 0)
{
	$result = get_filter();

	if ($result === false) {
		if (0 < $id) {
			$filter['id'] = $id;
		}

		$sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('gift_gard_log') . ' WHERE gift_gard_id = \'' . $filter['id'] . '\'  AND handle_type=\'toggle_on_settlement\'';
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);
		$filter = page_and_size($filter);
		$sql = 'SELECT a.id,a.addtime,b.user_name,a.delivery_status,a.gift_gard_id FROM' . $GLOBALS['ecs']->table('gift_gard_log') . ' AS a LEFT JOIN ' . $GLOBALS['ecs']->table('admin_user') . ' AS b ON a.admin_id = b.user_id WHERE a.gift_gard_id = \'' . $filter['id'] . '\' AND a.handle_type=\'toggle_on_settlement\'  ORDER BY a.addtime DESC LIMIT ' . $filter['start'] . ',' . $filter['page_size'];
		set_filter($filter, $sql);
	}
	else {
		$sql = $result['sql'];
		$filter = $result['filter'];
	}

	$row = $GLOBALS['db']->getAll($sql);

	foreach ($row as $k => $v) {
		if (0 < $v['addtime']) {
			$row[$k]['add_time'] = local_date('Y-m-d  H:i:s', $v['addtime']);
		}

		if ($v['delivery_status'] == 0) {
			$row[$k]['delivery_status'] = $GLOBALS['_LANG']['no_settlement'];
		}
		else if ($v['delivery_status'] == 1) {
			$row[$k]['delivery_status'] = $GLOBALS['_LANG']['is_settlement'];
		}
		else if ($v['delivery_status'] == 2) {
			$row[$k]['delivery_status'] = '解除冻结';
		}
		else if ($v['delivery_status'] == 3) {
			$row[$k]['delivery_status'] = '冻结';
		}

		if ($v['gift_gard_id']) {
			$row[$k]['gift_sn'] = $GLOBALS['db']->getOne(' SELECT order_sn FROM ' . $GLOBALS['ecs']->table('order_info') . ' WHERE order_id = \'' . $v['gift_gard_id'] . '\'');
		}
	}

	$arr = array('pzd_list' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
	return $arr;
}

define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
require_once ROOT_PATH . 'includes/lib_order.php';
define('SUPPLIERS_ACTION_LIST', 'delivery_view,back_view');
$adminru = get_admin_ru_id();

if ($adminru['ru_id'] == 0) {
	$smarty->assign('priv_ru', 1);
}
else {
	$smarty->assign('priv_ru', 0);
}

$smarty->assign('commission_model', $_CFG['commission_model']);

if ($_REQUEST['act'] == 'list') {
	admin_priv('merchants_commission');
	$smarty->assign('menu_select', array('action' => '17_merchants', 'current' => '03_merchants_commission'));
	$smarty->assign('ur_here', $_LANG['brokerage_amount_list']);
	$smarty->assign('action_link3', array('href' => 'javascript:download_list();', 'text' => $_LANG['export_all_suppliers']));
	$store_list = get_common_store_list();
	$smarty->assign('store_list', $store_list);
	$result = merchants_commission_list();
	$smarty->assign('full_page', 1);
	$smarty->assign('merchants_commission_list', $result['result']);
	$smarty->assign('filter', $result['filter']);
	$smarty->assign('record_count', $result['record_count']);
	$smarty->assign('page_count', $result['page_count']);
	$smarty->assign('sort_suppliers_id', '<img src="images/sort_desc.gif">');

	if (file_exists(MOBILE_DRP)) {
		$smarty->assign('is_dir', 1);
	}
	else {
		$smarty->assign('is_dir', 0);
	}

	assign_query_info();
	$smarty->display('merchants_commission_list.dwt');
}
else if ($_REQUEST['act'] == 'query') {
	check_authz_json('merchants_commission');
	$priv_str = $db->getOne('SELECT action_list FROM ' . $ecs->table('admin_user') . ' WHERE user_id = ' . $_SESSION['admin_id']);

	if ($priv_str != 'all') {
		$smarty->assign('no_all', 0);
		$ser_name = $_LANG['suppliers_list_server'];
	}
	else {
		$smarty->assign('no_all', 1);
	}

	$store_list = get_common_store_list();
	$smarty->assign('store_list', $store_list);
	$result = merchants_commission_list();
	$smarty->assign('merchants_commission_list', $result['result']);
	$smarty->assign('filter', $result['filter']);
	$smarty->assign('record_count', $result['record_count']);
	$smarty->assign('page_count', $result['page_count']);

	if (file_exists(MOBILE_DRP)) {
		$smarty->assign('is_dir', 1);
	}
	else {
		$smarty->assign('is_dir', 0);
	}

	$sort_flag = sort_flag($result['filter']);
	$smarty->assign($sort_flag['tag'], $sort_flag['img']);
	make_json_result($smarty->fetch('merchants_commission_list.dwt'), '', array('filter' => $result['filter'], 'page_count' => $result['page_count']));
}
else if ($_REQUEST['act'] == 'edit_suppliers_ser_name') {
	check_authz_json('merchants_commission');
	$id = intval($_POST['id']);
	$name = json_str_iconv(trim($_POST['val']));
	$sql = "SELECT suppliers_ser_id\r\n            FROM " . $ecs->table('merchants_server') . "\r\n            WHERE suppliers_name = '" . $name . "'\r\n            AND suppliers_ser_id <> '" . $id . '\' ';

	if ($db->getOne($sql)) {
		make_json_error(sprintf($_LANG['suppliers_name_exist'], $name));
	}
	else {
		$sql = 'UPDATE ' . $ecs->table('merchants_server') . "\r\n                SET suppliers_name = '" . $name . "'\r\n                WHERE suppliers_ser_id = '" . $id . '\'';

		if ($result = $db->query($sql)) {
			admin_log($name, 'edit', 'suppliers_ser');
			clear_cache_files();
			make_json_result(stripslashes($name));
		}
		else {
			make_json_result(sprintf($_LANG['agency_edit_fail'], $name));
		}
	}
}
else if ($_REQUEST['act'] == 'remove') {
	check_authz_json('merchants_commission');
	$id = intval($_REQUEST['id']);
	$sql = 'DELETE FROM ' . $ecs->table('merchants_server') . "\r\n            WHERE server_id = '" . $id . '\'';
	$db->query($sql);
	clear_cache_files();
	$url = 'merchants_commission.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
	ecs_header('Location: ' . $url . "\n");
	exit();
}
else if ($_REQUEST['act'] == 'batch') {
	$nowTime = gmtime();

	if (empty($_POST['checkboxes'])) {
		sys_msg($_LANG['no_record_selected']);
	}
	else {
		admin_priv('merchants_commission');
		$ids = $_POST['checkboxes'];

		if (isset($_POST['remove'])) {
			$sql = "SELECT *\r\n                    FROM " . $ecs->table('merchants_server') . "\r\n                    WHERE suppliers_ser_id " . db_create_in($ids);
			$suppliers = $db->getAll($sql);

			foreach ($suppliers as $key => $value) {
				$sql = "SELECT COUNT(*)\r\n                        FROM " . $ecs->table('order_info') . 'AS O, ' . $ecs->table('order_goods') . ' AS OG, ' . $ecs->table('goods') . " AS G\r\n                        WHERE O.order_id = OG.order_id\r\n                        AND OG.goods_id = G.goods_id\r\n                        AND G.suppliers_ser_id = '" . $value['suppliers_ser_id'] . '\'';
				$order_exists = $db->getOne($sql, true);

				if (0 < $order_exists) {
					unset($suppliers[$key]);
				}

				$sql = "SELECT COUNT(*)\r\n                        FROM " . $ecs->table('goods') . "AS G\r\n                        WHERE G.suppliers_ser_id = '" . $value['suppliers_ser_id'] . '\'';
				$goods_exists = $db->getOne($sql, true);

				if (0 < $goods_exists) {
					unset($suppliers[$key]);
				}
			}

			if (empty($suppliers)) {
				sys_msg($_LANG['batch_drop_no']);
			}

			$sql = 'DELETE FROM ' . $ecs->table('merchants_server') . "\r\n                WHERE suppliers_ser_id " . db_create_in($ids);
			$db->query($sql);
			$table_array = array('admin_user', 'delivery_order', 'back_order');

			foreach ($table_array as $value) {
				$sql = 'DELETE FROM ' . $ecs->table($value) . ' WHERE suppliers_ser_id ' . db_create_in($ids) . ' ';
				$db->query($sql, 'SILENT');
			}

			$suppliers_names = '';

			foreach ($suppliers as $value) {
				$suppliers_names .= $value['suppliers_name'] . '|';
			}

			admin_log($suppliers_names, 'remove', 'suppliers_ser');
			clear_cache_files();
			sys_msg($_LANG['batch_drop_ok']);
		}

		if ($_POST['type'] == 'button_remove') {
			sys_msg($_LANG['is not supported']);
		}
		else if ($_POST['type'] == 'button_closed') {
			$ids = $_POST['checkboxes'];
			$result = merchants_order_list_checked($ids);
			$settlement = intval(1);

			if (empty($result)) {
				sys_msg($_LANG['no_order']);
			}
			else {
				foreach ($ids as $k => $v) {
					if (!empty($v)) {
						$db->query(' INSERT INTO' . $ecs->table('gift_gard_log') . ' (`admin_id`,`gift_gard_id`,`delivery_status`,`addtime`,`handle_type`) VALUES (\'' . $_SESSION['admin_id'] . '\',\'' . $v . '\',\'' . $settlement . '\',\'' . $nowTime . '\',\'toggle_on_settlement\')');
						$order_goods = get_order_seller_id($v);
						$amount = get_seller_settlement_amount($v, $order_goods['ru_id']);
						$other['admin_id'] = $_SESSION['admin_id'];
						$other['ru_id'] = $order_goods['ru_id'];
						$other['order_id'] = $v;
						$other['amount'] = $amount;
						$other['add_time'] = $nowTime;
						$other['log_type'] = 2;
						$other['is_paid'] = 1;
						$db->autoExecute($ecs->table('seller_account_log'), $other, 'INSERT');
						$sql = 'UPDATE ' . $ecs->table('seller_shopinfo') . ' SET seller_money = seller_money + ' . $amount . ' WHERE ru_id = \'' . $order_goods['ru_id'] . '\'';
						$db->query($sql);
					}
				}

				$sql = ' UPDATE ' . $ecs->table('order_info') . ' SET is_settlement = \'' . $settlement . '\' WHERE order_id ' . db_create_in($ids);
				$query = $db->query($sql);

				if ($query) {
					clear_cache_files();
					sys_msg($_LANG['batch_closed_success']);
				}
			}
		}
		else if (empty($_POST['type'])) {
			sys_msg($_LANG['choose_batch']);
		}
	}
}
else if (in_array($_REQUEST['act'], array('add', 'edit'))) {
	admin_priv('merchants_commission');
	$smarty->assign('menu_select', array('action' => '17_merchants', 'current' => 'suppliers_list_server'));
	$smarty->assign('action_link', array('href' => 'merchants_commission.php?act=list', 'text' => $_LANG['suppliers_list_server']));

	if ($_REQUEST['act'] == 'add') {
		$smarty->assign('action_link2', array('href' => 'commission_batch.php?act=add', 'text' => $_LANG['suppliers_bacth']));
		$user_list = get_merchants_user_list();
		$suppliers_percent = get_suppliers_percent();
		$smarty->assign('ur_here', $_LANG['add_suppliers_server']);
		$smarty->assign('form_action', 'insert');
		$smarty->assign('suppliers_percent', $suppliers_percent);
		$smarty->assign('user_list', $user_list);
	}
	else if ($_REQUEST['act'] == 'edit') {
		$suppliers = array();
		$id = $_REQUEST['id'];
		$sql = 'SELECT * FROM ' . $ecs->table('merchants_server') . ' WHERE server_id = \'' . $id . '\'';
		$suppliers = $db->getRow($sql);

		if (count($suppliers) <= 0) {
			sys_msg('suppliers does not exist');
		}

		$user_list = get_merchants_user_list();
		$suppliers_percent = get_suppliers_percent();
		$date = array('user_id', 'suppliers_percent', 'suppliers_desc');
		$server = get_table_date('merchants_server', 'server_id = \'' . $id . '\'', $date);
		$smarty->assign('ur_here', $_LANG['edit_suppliers_server']);
		$smarty->assign('form_action', 'update');
		$smarty->assign('user_list', $user_list);
		$smarty->assign('id', $id);
		$smarty->assign('server', $server);
		$smarty->assign('suppliers_percent', $suppliers_percent);
	}

	assign_query_info();
	$smarty->display('merchants_commission_info.dwt');
}
else if (in_array($_REQUEST['act'], array('insert', 'update'))) {
	admin_priv('merchants_commission');

	if ($_REQUEST['act'] == 'insert') {
		$suppliers = array('user_id' => intval($_POST['merchants_name']), 'suppliers_desc' => trim($_POST['suppliers_desc']), 'suppliers_percent' => intval($_POST['merchants_percent']));
		$sql = "SELECT server_id\r\n                FROM " . $ecs->table('merchants_server') . "\r\n                WHERE user_id = '" . $suppliers['user_id'] . '\' ';

		if ($db->getOne($sql)) {
			sys_msg($_LANG['suppliers_name_exist']);
		}

		$db->autoExecute($ecs->table('merchants_server'), $suppliers, 'INSERT');
		$suppliers['server_id'] = $db->insert_id();
		admin_log($suppliers['merchants_name'], 'add', 'merchants_server');
		clear_cache_files();
		$links = array(
			array('href' => 'merchants_commission.php?act=add', 'text' => $_LANG['continue_add_server_suppliers']),
			array('href' => 'merchants_commission.php?act=list', 'text' => $_LANG['back_suppliers_server_list'])
			);
		sys_msg($_LANG['add_suppliers_server_ok'], 0, $links);
	}

	if ($_REQUEST['act'] == 'update') {
		$suppliers = array('id' => trim($_POST['id']));
		$suppliers['new'] = array('user_id' => intval($_POST['merchants_name']), 'suppliers_desc' => trim($_POST['suppliers_desc']), 'suppliers_percent' => intval($_POST['merchants_percent']));
		$sql = "SELECT server_id\r\n                FROM " . $ecs->table('merchants_server') . "\r\n                WHERE user_id = '" . $suppliers['new']['merchants_name'] . "'\r\n                AND server_id <> '" . $suppliers['id'] . '\'';

		if ($db->getOne($sql)) {
			sys_msg($_LANG['suppliers_name_exist']);
		}

		$db->autoExecute($ecs->table('merchants_server'), $suppliers['new'], 'UPDATE', 'server_id = \'' . $suppliers['id'] . '\'');
		clear_cache_files();
		$links[] = array('href' => 'merchants_commission.php?act=list', 'text' => $_LANG['back_suppliers_server_list']);
		sys_msg($_LANG['edit_suppliers_server_ok'], 0, $links);
	}
}
else if ($_REQUEST['act'] == 'order_list') {
	admin_priv('merchants_commission');
	$user_id = (isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0);
	$_SESSION['order_userId'] = $user_id;
	$date = array('suppliers_percent');
	$percent_id = get_table_date('merchants_server', 'user_id = \'' . $user_id . '\' ', $date, $sqlType = 2);
	$date = array('percent_value');
	$percent_value = get_table_date('merchants_percent', 'percent_id = \'' . $percent_id . '\'', $date, $sqlType = 2) . '%';
	$smarty->assign('percent_value', $percent_value);

	if (0 < $adminru['ru_id']) {
		$smarty->assign('no_all', 0);
	}
	else {
		$smarty->assign('no_all', 1);
	}

	$smarty->assign('action_link', array('href' => 'javascript:order_downloadList();', 'text' => $_LANG['export_merchant_commission']));
	$smarty->assign('ur_here', $_LANG['brokerage_order_list']);
	$smarty->assign('full_page', 1);
	$order_list = merchants_order_list($user_id);
	$smarty->assign('user_id', $user_id);
	$smarty->assign('order_list', $order_list['orders']);
	$smarty->assign('filter', $order_list['filter']);
	$smarty->assign('record_count', $order_list['record_count']);
	$smarty->assign('page_count', $order_list['page_count']);
	$smarty->assign('server_id', '<img src="images/sort_desc.gif">');

	if (file_exists(MOBILE_DRP)) {
		$smarty->assign('is_dir', 1);
	}
	else {
		$smarty->assign('is_dir', 0);
	}

	assign_query_info();
	$smarty->display('merchants_order_list.dwt');
}
else if ($_REQUEST['act'] == 'order_query') {
	check_authz_json('merchants_commission');
	$priv_str = $db->getOne('SELECT action_list FROM ' . $ecs->table('admin_user') . ' WHERE user_id = ' . $_SESSION['admin_id']);

	if ($priv_str != 'all') {
		$smarty->assign('no_all', 0);
		$ser_name = $_LANG['suppliers_list_server'];
	}
	else {
		$smarty->assign('no_all', 1);
	}

	$user_id = $_SESSION['order_userId'];
	$order_list = merchants_order_list($user_id);
	$date = array('suppliers_percent');
	$percent_id = get_table_date('merchants_server', 'user_id = \'' . $user_id . '\' ', $date, $sqlType = 2);
	$date = array('percent_value');
	$percent_value = get_table_date('merchants_percent', 'percent_id = \'' . $percent_id . '\'', $date, $sqlType = 2) . '%';
	$smarty->assign('percent_value', $percent_value);
	$smarty->assign('order_list', $order_list['orders']);
	$smarty->assign('filter', $order_list['filter']);
	$smarty->assign('record_count', $order_list['record_count']);
	$smarty->assign('page_count', $order_list['page_count']);
	$sort_flag = sort_flag($order_list['filter']);
	$smarty->assign($sort_flag['tag'], $sort_flag['img']);

	if (file_exists(MOBILE_DRP)) {
		$smarty->assign('is_dir', 1);
	}
	else {
		$smarty->assign('is_dir', 0);
	}

	make_json_result($smarty->fetch('merchants_order_list.dwt'), '', array('filter' => $order_list['filter'], 'page_count' => $order_list['page_count']));
}
else if ($_REQUEST['act'] == 'toggle_on_settlement') {
	check_authz_json('merchants_commission');
	$order_id = intval($_POST['id']);
	$on_sale = intval($_POST['val']);
	$sql = 'SELECT is_settlement,is_frozen FROM ' . $ecs->table('order_info') . ' WHERE order_id = \'' . $order_id . '\'';
	$order_exc = $db->getRow($sql);

	if ($order_exc['is_frozen']) {
		make_json_error('该订单已被冻结，不能进行此操作！');
	}

	if ($order_exc['is_settlement']) {
		make_json_error($_LANG['not_settlement']);
	}

	$nowTime = gmtime();
	$sql = 'UPDATE ' . $ecs->table('order_info') . ' SET is_settlement = \'' . $on_sale . '\' WHERE order_id = \'' . $order_id . '\'';
	$db->query($sql);
	$order_goods = get_order_seller_id($order_id);
	$amount = get_seller_settlement_amount($order_id, $order_goods['ru_id']);
	$other['admin_id'] = $_SESSION['admin_id'];
	$other['ru_id'] = $order_goods['ru_id'];
	$other['order_id'] = $order_id;
	$other['amount'] = $amount;
	$other['add_time'] = $nowTime;
	$other['log_type'] = 2;
	$other['is_paid'] = 1;
	$db->autoExecute($ecs->table('seller_account_log'), $other, 'INSERT');
	$sql = 'UPDATE ' . $ecs->table('seller_shopinfo') . ' SET seller_money = seller_money + ' . $amount . ' WHERE ru_id = \'' . $order_goods['ru_id'] . '\'';
	$db->query($sql);
	$change_desc = sprintf($_LANG['01_admin_settlement'], $_SESSION['admin_name']);
	$user_account_log = array('user_id' => $order_goods['ru_id'], 'user_money' => $amount, 'change_time' => gmtime(), 'change_desc' => $change_desc, 'change_type' => 2);
	$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('merchants_account_log'), $user_account_log, 'INSERT');
	$db->query(' INSERT INTO' . $ecs->table('gift_gard_log') . ' (`admin_id`,`gift_gard_id`,`delivery_status`,`addtime`,`handle_type`) VALUES (\'' . $_SESSION['admin_id'] . '\',\'' . $order_id . '\',\'' . $on_sale . '\',\'' . $nowTime . '\',\'toggle_on_settlement\')');
	clear_cache_files();
	make_json_result($on_sale);
}
else if ($_REQUEST['act'] == 'toggle_on_frozen') {
	check_authz_json('merchants_commission');
	$order_id = intval($_POST['id']);
	$on_sale = intval($_POST['val']);
	$nowTime = gmtime();
	$sql = 'UPDATE ' . $ecs->table('order_info') . ' SET is_frozen = \'' . $on_sale . '\' WHERE order_id = \'' . $order_id . '\'';
	$db->query($sql);

	if ($on_sale == 1) {
		$type = 3;
	}
	else {
		$type = 2;
	}

	$db->query(' INSERT INTO' . $ecs->table('gift_gard_log') . ' (`admin_id`,`gift_gard_id`,`delivery_status`,`addtime`,`handle_type`) VALUES (\'' . $_SESSION['admin_id'] . '\',\'' . $order_id . '\',\'' . $type . '\',\'' . $nowTime . '\',\'toggle_on_settlement\')');
	clear_cache_files();
	make_json_result($on_sale);
}
else if ($_REQUEST['act'] == 'query_merchants_info') {
	check_authz_json('merchants_commission');
	$user_id = (empty($_REQUEST['user_id']) ? 0 : intval($_REQUEST['user_id']));
	$date = array('shoprz_brandName, shopNameSuffix');
	$user = get_table_date('merchants_shop_information', 'user_id = \'' . $user_id . '\'', $date);
	$user['user_id'] = $user_id;
	clear_cache_files();
	make_json_result($user);
}

if ($_REQUEST['act'] == 'commission_download') {
	$filename = date('YmdHis') . '.csv';
	header('Content-type:text/csv');
	header('Content-Disposition:attachment;filename=' . $filename);
	header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
	header('Expires:0');
	header('Pragma:public');
	$commission_list = merchants_commission_list();
	echo commission_download_list($commission_list['result']);
	exit();
}

if ($_REQUEST['act'] == 'merchant_download') {
	$filename = date('YmdHis') . '.csv';
	header('Content-type:text/csv');
	header('Content-Disposition:attachment;filename=' . $filename);
	header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
	header('Expires:0');
	header('Pragma:public');
	$merchants_order_list = merchants_order_list();
	echo order_download_list($merchants_order_list['orders']);
	echo ecs_iconv(EC_CHARSET, 'GB2312', '佣金总金额：');
	echo ecs_iconv(EC_CHARSET, 'GB2312', $merchants_order_list['orders']['brokerage_amount']['all_price']) . "\t\n";
	echo ecs_iconv(EC_CHARSET, 'GB2312', '已结算：');
	echo ecs_iconv(EC_CHARSET, 'GB2312', $merchants_order_list['orders']['brokerage_amount']['is_settlement_price']) . "\t\n";
	echo ecs_iconv(EC_CHARSET, 'GB2312', '未结算：');
	echo ecs_iconv(EC_CHARSET, 'GB2312', $merchants_order_list['orders']['brokerage_amount']['no_settlement_price']) . "\t\n";
	exit();
}
else if ($_REQUEST['act'] == 'handle_log') {
	admin_priv('merchants_commission');
	$smarty->assign('ur_here', $_LANG['handle_log']);
	$user_id = (!empty($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0);
	$smarty->assign('action_link', array('text' => $_LANG['brokerage_order_list'], 'href' => 'merchants_commission.php?act=order_list&id=' . $user_id));
	$id = (!empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0);
	$gift_gard_log = get_gift_gard_log($id);
	$smarty->assign('full_page', 1);
	$smarty->assign('gift_gard_log', $gift_gard_log['pzd_list']);
	$smarty->assign('filter', $gift_gard_log['filter']);
	$smarty->assign('record_count', $gift_gard_log['record_count']);
	$smarty->assign('page_count', $gift_gard_log['page_count']);
	$smarty->assign('order_id', $id);
	$smarty->display('merchants_order_log.dwt');
}

if ($_REQUEST['act'] == 'search_users') {
	$keywords = json_str_iconv(trim($_GET['keywords']));
	$sql = 'SELECT u.user_name,msi.* FROM ' . $ecs->table('merchants_shop_information') . ' AS msi LEFT JOIN ' . $ecs->table('users') . ' AS u ON u.user_id = msi.user_id WHERE user_name LIKE \'%' . mysql_like_quote($keywords) . '%\' OR msi.user_id LIKE \'%' . mysql_like_quote($keywords) . '%\'';
	$row = $db->getAll($sql);
	make_json_result($row);
}

if ($_REQUEST['act'] == 'Ajax_handle_log') {
	$id = (!empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0);
	$gift_gard_log = get_gift_gard_log($id);
	$smarty->assign('gift_gard_log', $gift_gard_log['pzd_list']);
	$smarty->assign('filter', $gift_gard_log['filter']);
	$smarty->assign('record_count', $gift_gard_log['record_count']);
	$smarty->assign('page_count', $gift_gard_log['page_count']);
	make_json_result($smarty->fetch('merchants_order_log.htm'), '', array('filter' => $gift_gard_log['filter'], 'page_count' => $gift_gard_log['page_count']));
}

?>
