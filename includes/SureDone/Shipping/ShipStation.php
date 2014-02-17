<?php
require_once(dirname(__FILE__) . '/base.php');

/**
* ShipStation integration
*/
class ShipStation extends BaseShipping
{
    protected $host = "https://data.shipstation.com";
    protected $auth = null;

    protected function ship($order) {
        $ship_order = $this->find_order($order->order);
        if ($ship_order) {
            $this->update_order($order, $ship_order);
        } else {
            $this->add_order($order);
        }
        $this->add_order($order);
    }

    protected function add_order($order) {
        $data = array(
            'OrderStatusID' => 1,
            'OrderNumber' => $order->order,
            'OrderDate' => $order->date,
            'ShipName' => $order->sfirstname . ' ' . $order->slastname . ' ' . $order->sbusiness,
            'ShipStreet1' => $order->saddress1,
            'ShipStreet2' => $order->saddress2,
            'ShipCity' => $order->scity,
            'ShipState' => $order->sstate,
            'ShipPostalCode' => $order->szip,
            'ShipCountryCode' => $order->scountry,
            'ShipPhone' => $order->sphone,
            'AddressVerified' => 0,
            //'ShippingAmount'
            'OrderTotal' => $order->total,
            //'NotesFromBuyer'
            //'NotesToBuyer'
            'ImportKey' => $order->order,
            'InsuranceProvider' => 1,
            'Gift' => $order->shipasgift == 1,
        );
        $ship_order = $this->request("post", "/1.2/Orders", $data);
        $items = explode('*', $order->items);
        $weights = explode('*', $order->weights);
        $titles = explode('*', $order->titles);
        $qtys = explode('*', $order->qtys);
        $prices = explode('*', $order->prices);
        $items_total = count($items);
        for ($i=0; $i<$items_total; $i++) {
            $item_data = array(
                'OrderID' => $ship_order->OrderID,
                'SKU' => $items[$i],
                'Description' => $titles[$i],
                'Quantity' => $qtys[$i],
                'UnitPrice' => strval($prices[$i]),
                'ExtendedPrice' => strval($prices[$i] * $qtys[$i]),
            );
            $this->request("post", "OrderItems", $item_data, 'https://io.shipstation.com/');
        }
        $order->shiptracking = 'PENDING';
        $this->update($order);
    }

    protected function request($method, $url, $data=array(), $host=null) {
        if (!$this->auth) {
            $this->auth = array(
                $this->get_option('shipstation_username'),
                $this->get_option('shipstation_password')
            );
        }
        $options = array('auth' => $this->auth);
        $headers = array(
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        );
        if (!$host) {
            $host = $this->host;
        }
        if ($method == "get") {
            $r = Requests::$method($host . $url, $headers, $options);
        } else {
            $r = Requests::$method($host . $url, $headers, json_encode($data), $options);
        }
        if (in_array($r->status_code, array(200, 201))) {
            return json_decode($r->body)->d;
        }
        $x = json_decode($r->body);
    }

    protected function find_order($orderNumber) {
        return $this->request("get", "/1.1/Orders()?\$filter=(OrderNumber eq '{$orderNumber}')")->results;
    }

    protected function update_order($order, $ship_order) {
        $numbers = array();
        $paid = 0;
        $shipments = $this->request("get", "/1.1/Orders()?\$filter=(OrderNumber eq '{$orderNumber}')")->results;
        foreach($shipments as $ship) {
            if ($ship->TrackingNumber) {
                $numbers[] = $ship->TrackingNumber;
                $paid += $ship->CarrierFee + $ship->InsuranceFee;
            }
        }
        if ($numbers) {
            $order->shiptracking = implode(',', $numbers);
            $order->shippaid = $paid;
            if ($ship->Shipped) {
                $order->shipdate = $ship->ShipDate;
            }
            $this->update($order);
        }

    }
}
?>
