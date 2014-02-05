<?php

require_once('config.php');

require_once('base_config.php');

require_once(PATH_SDK_ROOT . 'Core/ServiceContext.php');
require_once(PATH_SDK_ROOT . 'DataService/DataService.php');
require_once(PATH_SDK_ROOT . 'PlatformService/PlatformService.php');
require_once(PATH_SDK_ROOT . 'Utility/Configuration/ConfigurationManager.php');
require_once(SD_PATH_SDK_ROOT . 'includes/SureDone_Startup.php');


class Netsuite {
    protected $dataService;
    protected $sd_username;
    protected $sd_token;

    public function __construct($user_token, $user_secret, $realmid, $sd_username, $sd_token) {
        $this->sd_username = $sd_username;
        $this->sd_token = $sd_token;

        $serviceType = IntuitServicesType::QBO;
        $requestValidator = new OAuthRequestValidator($user_token,
                                                    $user_secret,
                                                    QUICKBOOKS_CONSUMER_KEY,
                                                    QUICKBOOKS_CONSUMER_SECRET);

        $serviceContext = new ServiceContext($realmid, $serviceType, $requestValidator);
        if (!$serviceContext) {
            exit("Problem while initializing ServiceContext.\n");
        }

        $this->dataService = new DataService($serviceContext);
        if (!$this->dataService) {
            exit("Problem while initializing DataService.\n");
        }

    }

    public function sync_orders() {
        $i = 0;
        while(true) {
            $i++;
            $response = SureDone_Store::get_shipped_orders($i, 'shiptracking', $this->sd_token, $this->sd_username);
            $orders = json_decode($response);
            if (!$orders) {
                break;
            }

            foreach ($orders as $order) {
                if (is_string($order)) {
                    continue;
                }

                $invoices = $this->dataService->Query("SELECT * FROM Invoice WHERE DocNumber='{$order->order}'");
                if ($invoice) {
                    continue;
                }
                $customer = $this->add_customer($order);

                $invoice = new IPPInvoice();
                $invoice->DocNumber = $order->order;
                $invoice->TxnDate = $order->date;
                $invoice->PrivateNote = $order->internalnotes;
                $invoice->ShipDate = $order->shipdate;
                $invoice->TrackingNum = $order->shiptracking;

                $items = explode('*', $order->items);
                $titles = explode('*', $order->titles);
                $qtys = explode('*', $order->qtys);
                $prices = explode('*', $order->prices);
                $items_total = count($items);
                for ($i=0; $i<$items_total; $i++) {
                    $line  = new IPPLine();
                    $line->LineNum = $i;
                    $line->Description = htmlentities($items[$i] . ' - ' . $titles[$i]);
                    $line->Amount = $prices[$i] * $qtys[$i];

                    $detail = new IPPSalesItemLineDetail();
                    $detail->UnitPrice = $prices[$i];
                    $detail->Qty = $qtys[$i];
                    $line->SalesItemLineDetail = $detail;
                    $line->DetailType = 'SalesItemLineDetail';
                    $invoice->Line[$i] = $line;
                }

                $ref = new IPPReferenceType();
                $ref->value = $customer->Id;
                $invoice->CustomerRef = $ref;

                $this->dataService->Add($invoice);
                //break 2; // For tests only
            }
        }
    }

    protected function add_customer($order) {
        $customer = new IPPCustomer();
        $customer->DisplayName = 'SureDone Client #' . $order->order;
        $customer->GivenName = $order->bfirstname;
        $customer->MiddleName = $order->bminitial;
        $customer->FamilyName = $order->blastname;
        $customer->CompanyName = $order->bbusiness;

        $email = new IPPEmailAddress();
        $email->Address = $order->email;
        $customer->PrimaryEmailAddr = $email;

        $b_address = new IPPPhysicalAddress();
        $b_address->Line1 = $order->baddress1;
        $b_address->Line2 = $order->baddress2;
        $b_address->Line3 = $order->baddress3;
        $b_address->City = $order->bcity;
        $b_address->Country = $order->bcountryname;
        $b_address->CountrySubDivisionCode = $order->bstate;
        $b_address->PostalCode = $order->bzip;
        $customer->BillAddr = $b_address;

        $s_address = new IPPPhysicalAddress();
        $s_address->Line1 = $order->saddress1;
        $s_address->Line2 = $order->saddress2;
        $s_address->Line3 = $order->saddress3;
        $s_address->City = $order->scity;
        $s_address->Country = $order->scountryname;
        $s_address->CountrySubDivisionCode = $order->sstate;
        $s_address->PostalCode = $order->szip;
        $customer->ShipAddr = $s_address;

        $resultingCustomerObj = $this->dataService->Add($customer);
        return $resultingCustomerObj;
    }
}
?>