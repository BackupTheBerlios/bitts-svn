<?php
/****************************************************************************
 * CLASS FILE  : customer.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 19 november 2013
 * Description : Customer class
 *
 */

  class customer {
    private $id, $name, $id_external, $billing_name1, $billing_name2, $billing_address, $billing_postcode, $billing_city, $billing_country, $billing_email_address, $billing_phone, $billing_fax, $listing;

    public function __construct($id = 0) {
      $database = $_SESSION['database'];
      $this->id = $id;
      $this->listing = array();

      if ($id != 0) {
        // Retrieve customer by id
        $id = $database->prepare_input($id);
        $customer_query = $database->query("select customers_name, customers_id_external, customers_billing_name1, customers_billing_name2, customers_billing_address, customers_billing_postcode, customers_billing_city, customers_billing_country, customers_billing_email_address, customers_billing_phone, customers_billing_fax, customers_billing_show_logo from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$id . "'");
        $customer_result = $database->fetch_array($customer_query);

        if (tep_not_null($customer_result)) {
          // Customer exists
          $this->fill($customer_result['customers_name'],
                      $customer_result['customers_id_external'],
                      $customer_result['customers_billing_name1'],
                      $customer_result['customers_billing_name2'],
                      $customer_result['customers_billing_address'],
                      $customer_result['customers_billing_postcode'],
                      $customer_result['customers_billing_city'],
                      $customer_result['customers_billing_country'],
                      $customer_result['customers_billing_email_address'],
                      $customer_result['customers_billing_phone'],
                      $customer_result['customers_billing_fax'],
                      $customer_result['customers_billing_show_logo']==1);
        }
      } else {
        // We probably created an empty Customer object to retrieve the entire customer listing
        $this->listing = $this->get_array();
      }
    }

    public function __get($varname) {
      switch ($varname) {
        case 'id':
          return $this->id;
      	case 'name':
          return $this->name;
        case 'id_external':
          return $this->id_external;
        case 'billing_name1':
          return $this->billing_name1;
        case 'billing_name2':
          return $this->billing_name2;
        case 'billing_address':
          return $this->billing_address;
        case 'billing_postcode':
          return $this->billing_postcode;
        case 'billing_city':
          return $this->billing_city;
        case 'billing_country':
          return $this->billing_country;
        case 'billing_email_address':
          return $this->billing_email_address;
        case 'billing_phone':
          return $this->billing_phone;
        case 'billing_fax':
          return $this->billing_fax;
        case 'billing_show_logo':
          return $this->billing_show_logo;
        case 'listing':
          return $this->listing;
        case 'listing_empty':
          return sizeof($this->listing) == 0;
      }
      return null;
    }

    public function fill($name = '', $id_external = '', $billing_name1 = '', $billing_name2 = '', $billing_address = '', $billing_postcode = '', $billing_city = '', $billing_country = '', $billing_email_address = '', $billing_phone = '', $billing_fax = '', $billing_show_logo = true) {
      $this->name = $name;
      $this->id_external = $id_external;
      $this->billing_name1 = $billing_name1;
      $this->billing_name2 = $billing_name2;
      $this->billing_address = $billing_address;
      $this->billing_postcode = $billing_postcode;
      $this->billing_city = $billing_city;
      $this->billing_country = $billing_country;
      $this->billing_email_address = $billing_email_address;
      $this->billing_phone = $billing_phone;
      $this->billing_fax = $billing_fax;
      $this->billing_show_logo = $billing_show_logo;
    }

    private function get_array() {
      $database = $_SESSION['database'];
      $customer_array = array();

      $index = 0;
      $customers_query = $database->query("select customers_id from " . TABLE_CUSTOMERS . " order by customers_name");
      while ($customers_result = $database->fetch_array($customers_query)) {
        $customer_array[$index] = new customer($customers_result['customers_id']);
        $index++;
      }

      return $customer_array;
    }

    public function save($id = 0, $name = '', $id_external = '', $billing_name1 = '', $billing_name2 = '', $billing_address = '', $billing_postcode = '', $billing_city = '', $billing_country = '', $billing_email_address = '', $billing_phone = '', $billing_fax = '', $billing_show_logo = true) {
      // Create new customer, fill and save it

      if ($id != 0) {
        // Create, fill and save customer
        $customer = new customer($id);
        $customer->fill($name, $id_external, $billing_name1, $billing_name2, $billing_address, $billing_postcode, $billing_city, $billing_country, $billing_email_address, $billing_phone, $billing_fax, $billing_show_logo);
        $customer->save();
      } else {
        $database = $_SESSION['database'];
        // Insert a new entry if one does not exist or update the existing one
        if (!$this->id_exists($this->id)) {
          // The entry does not exist
          $database->query("insert into " . TABLE_CUSTOMERS . " (customers_id, customers_name, customers_id_external, customers_billing_name1, customers_billing_name2, customers_billing_address, customers_billing_postcode, customers_billing_city, customers_billing_country, customers_billing_email_address, customers_billing_phone, customers_billing_fax, customers_billing_show_logo) values ('" . $this->id . "', '" . $database->input($this->name) . "', '" . $database->input($this->id_external) . "', '" . $database->input($this->billing_name1) . "', '" . $database->input($this->billing_name2) . "', '" . $database->input($this->billing_address) . "', '" . $database->input($this->billing_postcode) . "', '" . $database->input($this->billing_city) . "', '" . $database->input($this->billing_country) . "', '" . $database->input($this->billing_email_address) . "', '" . $database->input($this->billing_phone) . "', '" . $database->input($this->billing_fax) . "', '" . ($this->billing_show_logo?1:0) . "')");
        } else {
          // The entry exists, update the contents
          $activity_query = $database->query("update " . TABLE_CUSTOMERS . " set customers_id='" . $this->id . "', customers_name='" . $database->input($this->name) . "', customers_id_external='" . $database->input($this->id_external) . "', customers_billing_name1='" . $database->input($this->billing_name1) . "', customers_billing_name2='" . $database->input($this->billing_name2) . "', customers_billing_address='" . $database->input($this->billing_address) . "', customers_billing_postcode='" . $database->input($this->billing_postcode) . "', customers_billing_city='" . $database->input($this->billing_city) . "', customers_billing_country='" . $database->input($this->billing_country) . "', customers_billing_email_address='" . $database->input($this->billing_email_address) . "', customers_billing_phone='" . $database->input($this->billing_phone) . "', customers_billing_fax='" . $database->input($this->billing_fax) . "', customers_billing_show_logo='" . ($this->billing_show_logo?1:0) . "' where customers_id = '" . (int)$this->id . "'");
        }
      }
    }

    public function delete($id = 0) {
      if ($id != 0) {
        // Create and delete customer
        $customer = new customer($id);
        $customer->delete();
      } else {
        $database = $_SESSION['database'];
        $id = $database->prepare_input($id);
        $customer_query = $database->query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$this->id . "'");
        // Reset id, otherwise one might think this customer (still) exists in db
        $this->id = 0;
      }
    }

    public function has_dependencies($id = 0) {
      if ($id != 0) {
        // Create and investigate customer
        $customer = new customer($id);
        return $customer->has_dependencies();
      } else {
        $database = $_SESSION['database'];
        $this->id = $database->prepare_input($this->id);
        $project_query = $database->query("select 1 from " . TABLE_PROJECTS . " where customers_id = '" . (int)$this->id . "'");
        $project_result = $database->fetch_array($project_query);
        return tep_not_null($project_result);
      }
    }

    public function validate_id($value) {
      $value = str_replace(",", ".", $value);
      $value = '0' . $value;
      return (substr_count($value, ".") == 0 &&
              is_numeric($value) &&
              (int)$value > 0);
      return false;
    }

    public function id_exists($id) {
      $database = $_SESSION['database'];
      $id = $database->prepare_input($id);
      $customer_query = $database->query("select 1 from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$id . "'");
      $customer_result = $database->fetch_array($customer_query);
      return tep_not_null($customer_result);
    }

    public function get_next_id() {
      $database = $_SESSION['database'];
      $customer_query = $database->query("select max(customers_id)+1 as customers_next_id from " . TABLE_CUSTOMERS);
      $customer_result = $database->fetch_array($customer_query);
      return (int)$customer_result['customers_next_id'];
    }
  }
?>
