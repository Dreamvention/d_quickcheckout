<?php
class ModelDQuickcheckoutOrder extends Model {

public function addOrder($data) {
	$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET 
		store_id = '" . (int)$data['store_id'] . "', 
		store_name = '" . $this->db->escape($data['store_name']) . "', 
		store_url = '" . $this->db->escape($data['store_url']) . "', 
		total = '" . (float)$data['total'] . "', 
		affiliate_id = '" . (int)$data['affiliate_id'] . "', 
		commission = '" . (float)$data['commission'] . "', 
		language_id = '" . (int)$data['language_id'] . "', 
		currency_id = '" . (int)$data['currency_id'] . "', 
		currency_code = '" . $this->db->escape($data['currency_code']) . "', 
		currency_value = '" . (float)$data['currency_value'] . "', 
		ip = '" . $this->db->escape($data['ip']) . "', 
		forwarded_ip = '" .  $this->db->escape($data['forwarded_ip']) . "', 
		user_agent = '" . $this->db->escape($data['user_agent']) . "', 
		accept_language = '" . $this->db->escape($data['accept_language']) . "', 
		date_added = NOW(), 
		date_modified = NOW()");
		$order_id = $this->db->getLastId();
		return $order_id;
}
	
// public function addOrder151($data) {
// 	$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET 
// 		store_id = '" . (int)$data['store_id'] . "', 
// 		store_name = '" . $this->db->escape($data['store_name']) . "', 
// 		store_url = '" . $this->db->escape($data['store_url']) . "', 
// 		total = '" . (float)$data['total'] . "', 
// 		affiliate_id = '" . (int)$data['affiliate_id'] . "', 
// 		commission = '" . (float)$data['commission'] . "', 
// 		language_id = '" . (int)$data['language_id'] . "', 
// 		currency_id = '" . (int)$data['currency_id'] . "', 
// 		currency_code = '" . $this->db->escape($data['currency_code']) . "', 
// 		currency_value = '" . (float)$data['currency_value'] . "', 
// 		ip = '" . $this->db->escape($data['ip']) . "', 
// 		date_added = NOW(), 
// 		date_modified = NOW()");
// 		$order_id = $this->db->getLastId();
// 		return $order_id;
// }

public function updateOrder($order_id,$data) {
		$this->event->trigger('pre.order.add', $data);

		$query = "UPDATE `" . DB_PREFIX . "order` SET 
			invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', 
			store_id = '" . (int)$data['store_id'] . "', 
			store_name = '" . $this->db->escape($data['store_name']) . "', 
			store_url = '" . $this->db->escape($data['store_url']) . "', 
			customer_id = '" . (int)$data['customer_id'] . "', 
			customer_group_id = '" . (int)$data['customer_group_id'] . "', 
			firstname = '" . $this->db->escape($data['firstname']) . "', 
			lastname = '" . $this->db->escape($data['lastname']) . "', 
			email = '" . $this->db->escape($data['email']) . "', 
			telephone = '" . $this->db->escape($data['telephone']) . "', 
			fax = '" . $this->db->escape($data['fax']) . "', 
			custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "', 
			payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', 
			payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', 
			payment_company = '" . $this->db->escape($data['payment_company']) . "', 
			payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', 
			payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', 
			payment_city = '" . $this->db->escape($data['payment_city']) . "', 
			payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', 
			payment_country = '" . $this->db->escape($data['payment_country']) . "', 
			payment_country_id = '" . (int)$data['payment_country_id'] . "', 
			payment_zone = '" . $this->db->escape($data['payment_zone']) . "', 
			payment_zone_id = '" . (int)$data['payment_zone_id'] . "', 
			payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', 
			payment_custom_field = '" . $this->db->escape(isset($data['payment_custom_field']) ? serialize($data['payment_custom_field']) : '') . "', 
			payment_method = '" . $this->db->escape($data['payment_method']) . "', 
			payment_code = '" . $this->db->escape($data['payment_code']) . "', 
			shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', 
			shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', 
			shipping_company = '" . $this->db->escape($data['shipping_company']) . "', 
			shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', 
			shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', 
			shipping_city = '" . $this->db->escape($data['shipping_city']) . "', 
			shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', 
			shipping_country = '" . $this->db->escape($data['shipping_country']) . "', 
			shipping_country_id = '" . (int)$data['shipping_country_id'] . "', 
			shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', 
			shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', 
			shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', 
			shipping_custom_field = '" . $this->db->escape(isset($data['shipping_custom_field']) ? serialize($data['shipping_custom_field']) : '') . "', 
			shipping_method = '" . $this->db->escape($data['shipping_method']) . "', 
			shipping_code = '" . $this->db->escape($data['shipping_code']) . "', 
			comment = '" . $this->db->escape($data['comment']) . "', 
			total = '" . (float)$data['total'] . "', 
			affiliate_id = '" . (int)$data['affiliate_id'] . "', 
			commission = '" . (float)$data['commission'] . "',"; 

		if(isset($data['marketing_id'])) { $query = $query. " marketing_id = '" . (int)$data['marketing_id'] . "',"; }

		if(isset($data['tracking'])) { $query = $query. " tracking = '" . $this->db->escape($data['tracking']) . "',"; }

		$query = $query. " language_id = '" . (int)$data['language_id'] . "', 
			currency_id = '" . (int)$data['currency_id'] . "', 
			currency_code = '" . $this->db->escape($data['currency_code']) . "', 
			currency_value = '" . (float)$data['currency_value'] . "', 
			ip = '" . $this->db->escape($data['ip']) . "', 
			forwarded_ip = '" .  $this->db->escape($data['forwarded_ip']) . "', 
			user_agent = '" . $this->db->escape($data['user_agent']) . "', 
			accept_language = '" . $this->db->escape($data['accept_language']) . "', 
			date_added = NOW(), 
			date_modified = NOW()
			WHERE order_id = '" . (int)$order_id . "'";

		$this->db->query($query);

		$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'"); 
        $this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "'");


		// Products
		foreach ($data['products'] as $product) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "'");

			$order_product_id = $this->db->getLastId();

			foreach ($product['option'] as $option) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
			}
		}

		// Gift Voucher
		$this->load->model('checkout/voucher');

		$this->db->query("DELETE FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'"); 
		
		// Vouchers
		foreach ($data['vouchers'] as $voucher) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher SET order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($voucher['description']) . "', code = '" . $this->db->escape($voucher['code']) . "', from_name = '" . $this->db->escape($voucher['from_name']) . "', from_email = '" . $this->db->escape($voucher['from_email']) . "', to_name = '" . $this->db->escape($voucher['to_name']) . "', to_email = '" . $this->db->escape($voucher['to_email']) . "', voucher_theme_id = '" . (int)$voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($voucher['message']) . "', amount = '" . (float)$voucher['amount'] . "'");

			$order_voucher_id = $this->db->getLastId();

			$voucher_id = $this->model_checkout_voucher->addVoucher($order_id, $voucher);

			$this->db->query("UPDATE " . DB_PREFIX . "order_voucher SET voucher_id = '" . (int)$voucher_id . "' WHERE order_voucher_id = '" . (int)$order_voucher_id . "'");
		}

		// Totals	
	 	$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "'");
		foreach ($data['totals'] as $total) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
		}

		$this->event->trigger('post.order.add', $order_id);	

		return $order_id;
	}
	// public function updateOrder152($order_id, $data) {
	// 	$this->db->query("UPDATE `" . DB_PREFIX . "order` SET 
	// 		invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', 
	// 		store_id = '" . (int)$data['store_id'] . "', 
	// 		store_name = '" . $this->db->escape($data['store_name']) . "', 
	// 		store_url = '" . $this->db->escape($data['store_url']) . "', 
	// 		customer_id = '" . (int)$data['customer_id'] . "', 
	// 		customer_group_id = '" . (int)$data['customer_group_id'] . "', 
	// 		firstname = '" . $this->db->escape($data['firstname']) . "', 
	// 		lastname = '" . $this->db->escape($data['lastname']) . "', 
	// 		email = '" . $this->db->escape($data['email']) . "', 
	// 		telephone = '" . $this->db->escape($data['telephone']) . "', 
	// 		fax = '" . $this->db->escape($data['fax']) . "', 
	// 		shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', 
	// 		shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', 
	// 		shipping_company = '" . $this->db->escape($data['shipping_company']) . "', 
	// 		shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', 
	// 		shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', 
	// 		shipping_city = '" . $this->db->escape($data['shipping_city']) . "', 
	// 		shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', 
	// 		shipping_country = '" . $this->db->escape($data['shipping_country']) . "', 
	// 		shipping_country_id = '" . (int)$data['shipping_country_id'] . "', 
	// 		shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', 
	// 		shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', 
	// 		shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', 
	// 		shipping_method = '" . $this->db->escape($data['shipping_method']) . "', 
	// 		shipping_code = '" . $this->db->escape($data['shipping_code']) . "', 
	// 		payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', 
	// 		payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', 
	// 		payment_company = '" . $this->db->escape($data['payment_company']) . "', 
	// 		payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', 
	// 		payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', 
	// 		payment_city = '" . $this->db->escape($data['payment_city']) . "', 
	// 		payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', 
	// 		payment_country = '" . $this->db->escape($data['payment_country']) . "', 
	// 		payment_country_id = '" . (int)$data['payment_country_id'] . "', 
	// 		payment_zone = '" . $this->db->escape($data['payment_zone']) . "', 
	// 		payment_zone_id = '" . (int)$data['payment_zone_id'] . "', 
	// 		payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', 
	// 		payment_method = '" . $this->db->escape($data['payment_method']) . "', 
	// 		payment_code = '" . $this->db->escape($data['payment_code']) . "', 
	// 		comment = '" . $this->db->escape($data['comment']) . "', 
	// 		total = '" . (float)$data['total'] . "', 
	// 		affiliate_id = '" . (int)$data['affiliate_id'] . "', 
	// 		commission = '" . (float)$data['commission'] . "', 
	// 		language_id = '" . (int)$data['language_id'] . "', 
	// 		currency_id = '" . (int)$data['currency_id'] . "', 
	// 		currency_code = '" . $this->db->escape($data['currency_code']) . "', 
	// 		currency_value = '" . (float)$data['currency_value'] . "', 
	// 		ip = '" . $this->db->escape($data['ip']) . "', 
	// 		forwarded_ip = '" .  $this->db->escape($data['forwarded_ip']) . "', 
	// 		user_agent = '" . $this->db->escape($data['user_agent']) . "', 
	// 		accept_language = '" . $this->db->escape($data['accept_language']) . "', 
	// 		date_added = NOW(), 
	// 		date_modified = NOW() 
	// 		WHERE order_id = '" . (int)$order_id . "'");

	// 	$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'"); 
 //       	$this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "'");
	// 	$this->db->query("DELETE FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "'");

	// 	foreach ($data['products'] as $product) { 
	// 		$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "'");
 
	// 		$order_product_id = $this->db->getLastId();

	// 		foreach ($product['option'] as $option) {
	// 			$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
	// 		}
				
	// 		foreach ($product['download'] as $download) {
	// 			$this->db->query("INSERT INTO " . DB_PREFIX . "order_download SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', name = '" . $this->db->escape($download['name']) . "', filename = '" . $this->db->escape($download['filename']) . "', mask = '" . $this->db->escape($download['mask']) . "', remaining = '" . (int)($download['remaining'] * $product['quantity']) . "'");
	// 		}	
	// 	}
	// 	$this->db->query("DELETE FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'"); 
		
	// 	foreach ($data['vouchers'] as $voucher) {
	// 		$this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher SET order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($voucher['description']) . "', code = '" . $this->db->escape($voucher['code']) . "', from_name = '" . $this->db->escape($voucher['from_name']) . "', from_email = '" . $this->db->escape($voucher['from_email']) . "', to_name = '" . $this->db->escape($voucher['to_name']) . "', to_email = '" . $this->db->escape($voucher['to_email']) . "', voucher_theme_id = '" . (int)$voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($voucher['message']) . "', amount = '" . (float)$voucher['amount'] . "'");
	// 	}
		
	// 	$total = 0;	
	// 	$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "'");
		
	// 	foreach ($data['totals'] as $total) {
	// 		$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', text = '" . $this->db->escape($total['text']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
	// 	}	

	// 	return $order_id;
	// }
	// public function updateOrder151($order_id, $data) {
	// 	// compatibility - removed: , reward = '" . (float)$data['reward'] . "',
	// 	$this->db->query("UPDATE `" . DB_PREFIX . "order` SET 
	// 		invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', 
	// 		store_id = '" . (int)$data['store_id'] . "', 
	// 		store_name = '" . $this->db->escape($data['store_name']) . "', 
	// 		store_url = '" . $this->db->escape($data['store_url']) . "', 
	// 		customer_id = '" . (int)$data['customer_id'] . "', 
	// 		customer_group_id = '" . (int)$data['customer_group_id'] . "', 
	// 		firstname = '" . $this->db->escape($data['firstname']) . "', 
	// 		lastname = '" . $this->db->escape($data['lastname']) . "', 
	// 		email = '" . $this->db->escape($data['email']) . "', 
	// 		telephone = '" . $this->db->escape($data['telephone']) . "', 
	// 		fax = '" . $this->db->escape($data['fax']) . "', 
	// 		shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', 
	// 		shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', 
	// 		shipping_company = '" . $this->db->escape($data['shipping_company']) . "', 
	// 		shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', 
	// 		shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', 
	// 		shipping_city = '" . $this->db->escape($data['shipping_city']) . "', 
	// 		shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', 
	// 		shipping_country = '" . $this->db->escape($data['shipping_country']) . "', 
	// 		shipping_country_id = '" . (int)$data['shipping_country_id'] . "', 
	// 		shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', 
	// 		shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', 
	// 		shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', 
	// 		shipping_method = '" . $this->db->escape($data['shipping_method']) . "', 
	// 		payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', 
	// 		payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', 
	// 		payment_company = '" . $this->db->escape($data['payment_company']) . "', 
	// 		payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', 
	// 		payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', 
	// 		payment_city = '" . $this->db->escape($data['payment_city']) . "', 
	// 		payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', 
	// 		payment_country = '" . $this->db->escape($data['payment_country']) . "', 
	// 		payment_country_id = '" . (int)$data['payment_country_id'] . "', 
	// 		payment_zone = '" . $this->db->escape($data['payment_zone']) . "', 
	// 		payment_zone_id = '" . (int)$data['payment_zone_id'] . "', 
	// 		payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', 
	// 		payment_method = '" . $this->db->escape($data['payment_method']) . "', 
	// 		comment = '" . $this->db->escape($data['comment']) . "', 
	// 		total = '" . (float)$data['total'] . "', 
	// 		affiliate_id = '" . (int)$data['affiliate_id'] . "', 
	// 		commission = '" . (float)$data['commission'] . "', 
	// 		language_id = '" . (int)$data['language_id'] . "', 
	// 		currency_id = '" . (int)$data['currency_id'] . "', 
	// 		currency_code = '" . $this->db->escape($data['currency_code']) . "', 
	// 		currency_value = '" . (float)$data['currency_value'] . "', 
	// 		ip = '" . $this->db->escape($data['ip']) . "', 
	// 		date_added = NOW(), 
	// 		date_modified = NOW() 
	// 		WHERE order_id = '" . (int)$order_id . "'");

	// 	$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'"); 
 //       	$this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "'");
	// 	$this->db->query("DELETE FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "'");

	// 	foreach ($data['products'] as $product) { 
	// 		$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "'");
 
	// 		$order_product_id = $this->db->getLastId();

	// 		foreach ($product['option'] as $option) {
	// 			$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
	// 		}
				
	// 		foreach ($product['download'] as $download) {
	// 			$this->db->query("INSERT INTO " . DB_PREFIX . "order_download SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', name = '" . $this->db->escape($download['name']) . "', filename = '" . $this->db->escape($download['filename']) . "', mask = '" . $this->db->escape($download['mask']) . "', remaining = '" . (int)($download['remaining'] * $product['quantity']) . "'");
	// 		}	
	// 	}
	// 	$total = 0;	
	// 	$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "'");
		
	// 	foreach ($data['totals'] as $total) {
	// 		$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', text = '" . $this->db->escape($total['text']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
	// 	}	

	// 	return $order_id;
	// }
}
?>