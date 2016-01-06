<?php
/**
 * Initialized the path and the name of csv file that to be fetched.
 */
$fileName = 'csv/MeBank_Login_Dec12.csv';

/**
 * Get the content and convert to array from the csv file
 */
$array_ips = csv_to_array($fileName);

/**
 * flatten the array into object content
 */
$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($array_ips));

/**
 * Pass the value from the object into a single array
 */
foreach($it as $v) {
    $array_flat_ips[] = $v;
}

/**
 * Remove the duplicate array
 */
//print_r($array_flat_ips);
$array_unique_ips = array_unique($array_flat_ips);

/**
 * Separate ip's with space and group by 50's.
 */

//echo "Total = " . count($array_unique_ips);

$i=0;
foreach($array_unique_ips as $ip) {
    $i++;
    echo $ip . ' ';
    if($i%50 ==0) {
        echo "<br><br><br>";
            echo "$i";
        echo "<br><br><br>";
    }
}


echo "Total count " . $i . '<br>';
/**
 * print the final result of unique ips
 */
echo "<pre>";
echo "<h6>Print unique ips</h6>";
print_r($array_unique_ips);
echo "</pre>";

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
