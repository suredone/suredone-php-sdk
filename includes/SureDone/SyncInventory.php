<?php

require_once(dirname(__FILE__) . '/../SureDone_Startup.php');
require_once(dirname(__FILE__) . '/../../vendor/autoload.php');

/**
* Synchronize Inventory
* @param $verbose bool verbose actions
* @param $username string SureDone username
* @param $token string SureDone token
*/
class SyncInventory {
    protected $username = null;
    protected $token = null;
    protected $ftp_connection = null;

    public function __construct($username, $token)
    {
        $this->username = $username;
        $this->token = $token;
    }

    /**
    * Find stock column number
    * @param $header array list of columns
    */
    public static function find_stock_column($header) {
        $fields = array('stock', 'qty');
        foreach($fields as $field) {
            $position = array_search($field, $header);
            if ($position !== false) {
                return $position;
            }
        }
    }

    /**
    * Sync file(s) from FTP
    * @param $host string FTP host
    * @param $port int FTP port (default 21)
    * @param $username string FTP Auth username
    * @param $password string FTP Auth password
    * @param $directory string FTP directory (default .)
    * @param $file string File name (default get all *.csv)
    */
    public function from_ftp($host, $port = 21, $username = null, $password = null, $directory = '.', $file = null) {
        $this->ftp_connection = ftp_connect($host, $port);
        if (!@ftp_login($this->ftp_connection, $username, $password)) {
            echo "Couldn't connect as $username\n";
            die;
        }

        if (!$file) {
            $files = ftp_nlist($this->ftp_connection, $directory);
            foreach ($files as $file) {
                if (stripos($file, '.csv')) {
                    $this->get_file($directory . '/' . $file);
                }
            }
        } else {
            $this->get_file($directory . '/' . $file);
        }
    }

    protected function get_file($path) {
        $local_file = tempnam(sys_get_temp_dir(), 'sd-sync');
        $temp = fopen($local_file, 'w');

        if (!ftp_fget($this->ftp_connection, $temp, $path, FTP_ASCII)) {
            echo 'Error downloading file';
            die;
        }
        fclose($temp);
        $this->sync($local_file);
        unlink($local_file);
    }

    protected function sync($file) {
        $csvFile = new Keboola\Csv\CsvFile($file);
        $header = $csvFile->getHeader();
        $sku = array_search('sku', $header);
        $stock = self::find_stock_column($header);

        $items = array();
        $i = 0;
        while(true) {
            $i++;
            $response = SureDone_Store::editor_objects('items', $i, 'sku', $this->token, $this->username);
            $response = json_decode($response);
            foreach ($response as $item) {
                if (!is_string($item)) {
                    $items[] = $item;
                }
            }
            if ($i*50 > $response->all) {
                break;
            }
        }

        $i = 0;
        foreach($csvFile as $row) {
            $i++;
            if ($i == 1) {
                continue;
            }
            if (!empty($row[$sku])) {
                $item_sku = $row[$sku];
                $item_sku_variants = $item_sku . '-';
                //var_dump($item_sku);
                //$item = SureDone_Store::get_editor_single_object_by_sku('items', $item_sku, $this->token, $this->username);
                //$item = json_decode($item);
                foreach ($items as $item) {
                    if (strtolower($item->sku) == strtolower($item_sku) || stripos($item->sku, $item_sku_variants) === 0) {
                        $params = array(
                            'identifier' => 'id',
                            'id' => $item->id,
                            'stock' => $row[$stock],
                        );
                        SureDone_Store::post_editor_data('items', 'edit', $params, $this->token, $this->username);
                    }
                }
            }
        }
    }
}
?>
