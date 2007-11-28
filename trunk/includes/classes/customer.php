<?php
/****************************************************************************
 * CLASS FILE  : customer.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 28 november 2007
 * Description : .....
 *               .....
 */

  class customer {
    var $customer_id, $name, $billing_name1, $billing_name2, $billing_address, $billing_postcode, $billing_city, $billing_country, $billing_phone, $billing_fax;

    function customer($role_id = '') {
        $this->customer_id = $customer_id;

      if (tep_not_null($customer_id)) {
        $customer_id = tep_db_prepare_input($customer_id);

        $customer_query = tep_db_query("select name, billing_name1, billing_name2, billing_address, billing_postcode, billing_city, billing_country, billing_phone, billing_fax from " . TABLE_CUSTOMERS . " where customer_id = '" . (int)$customer_id . "'");
        $customer_result = tep_db_fetch_array($customer_query);

        $this->$name = $customer_result['name'];
        $this->$billing_name1 = $customer_result['billing_name1'];
        $this->$billing_name2 = $customer_result['billing_name2'];
        $this->$billing_address = $customer_result['billing_address'];
        $this->$billing_postcode = $customer_result['billing_postcode'];
        $this->$billing_city = $customer_result['billing_city'];
        $this->$billing_country = $customer_result['billing_country'];
        $this->$billing_phone = $customer_result['billing_phone'];
        $this->$billing_fax = $customer_result['billing_fax'];
      }
    }
  }
?>
