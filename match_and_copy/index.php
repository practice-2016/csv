<?php
/**
 * Arrange and set up csv for download
 */
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=file.csv");
// Disable caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
function outputCSV($data) {
    $output = fopen("php://output", "w");
    foreach ($data as $row) {
        fputcsv($output, $row); // here you can change delimiter/enclosure
    }
    fclose($output);
}

/**
 * Initialized the path and the name of csv file that to be fetched.
 */
$sheet1 = 'csv1/Sheet 1.csv';
$mainSheets = 'csv1/main.csv';

/**
 * Get the content and convert to array from the csv file
 */
$arraySheet1 = csv_to_array($sheet1);
$arrayMainSheets = csv_to_array($mainSheets);

//set header
$csv_header = array(
    'Company',
    'User ID',
    'Username',
    'User type',
    'IP address',
    'Time-stamp',
    'Access Country',
    'Access State'
);

$content = getContentCsv($arrayMainSheets, $arraySheet1, $csv_header);
outputCSV( $content );


/**
 * This function is to get the csv file and return as array.
 * @param string $filename
 * @param string $delimiter
 * @return array|bool
 */
function csv_to_array($filename='', $delimiter=',')
{
    if(!file_exists($filename) || !is_readable($filename))
        return FALSE;

    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE)
    {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
        {
            if(!$header)
                $header = $row;
            else
                $data[] = array_combine($header, $row);
        }
        fclose($handle);
    }
    return $data;
}

/**
 * Compare and compose new csv file
 */
function getContentCsv($arrayMainSheets, $arraySheet1, $csv_header) {

    //file 1 data or the sheet 1 csv init
    $file1_ip       = '';
    $file1_domain   = '';
    $file1_location = '';

    // file 2 data or the main csv init
    $file2_company          = '';
    $file2_user_id          = '';
    $file2_user_name        = '';
    $file2_user_type        = '';
    $file2_ip_address       = '';
    $file2_time_stamp       = '';
    $file2_access_country   = '';
    $file2_access_state     = '';

    //basic init
    $counter = 0;
    $data = array();
    $data[0] = $csv_header;

    /**
     * Main for loop
     */
    for($i=0; $i<count($arrayMainSheets); $i++) {

        $isIpExist = FALSE;
        $counter++;

        for($j=0; $j<count($arraySheet1); $j++) {

            //file 1 data or the sheet 1 csv
            $file1_ip       = $arraySheet1[$j]['IP'];
            $file1_domain   = $arraySheet1[$j]['Domain'];
            $file1_location = $arraySheet1[$j]['Location'];

            // file 2 data or the main csv
            $file2_company          = $arrayMainSheets[$i]['Company'];
            $file2_user_id          = $arrayMainSheets[$i]['User ID'];
            $file2_user_name        = $arrayMainSheets[$i]['Username'];
            $file2_user_type        = $arrayMainSheets[$i]['User type'];
            $file2_ip_address       = $arrayMainSheets[$i]['IP address'];
            $file2_time_stamp       = $arrayMainSheets[$i]['Time-stamp'];
            $file2_access_country   = $arrayMainSheets[$i]['Access Country'];
            $file2_access_state     = $arrayMainSheets[$i]['Access State'];

            /**
             * Check main csv ip address if matched with sheet 1 ip address
             * if matched then isExist set to true
             */
            if($file2_ip_address == $file1_ip) {
                $isIpExist = TRUE;
                break;
            }
        }

        /**
         * Copy location to country if main sheet match with ip of sheet 1
         */
        if($isIpExist == TRUE) {
            $file2_access_country = $file1_location;
        }

        /**
         * add data for printing csv
         */
        $data[$counter] = array(
            $file2_company,
            $file2_user_id,
            $file2_user_name,
            $file2_user_type,
            $file2_ip_address,
            $file2_time_stamp,
            $file2_access_country,
            $file2_access_state
        );
    }

    return $data;
}


