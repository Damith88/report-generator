<?php

require 'vendor/autoload.php';

// require 'CsvBuilder.php';
// require 'DbConnection.php';
// require 'ReportDefinition.php';

use Rakit\Validation\Validator;

$validator = new Validator();

$filters = [
    'startDate' => '2018-05-01',
    'endDate' => '2018-05-07',
];

// make it
$validation = $validator->make($filters, [
    'startDate'                  => 'required|date:Y-m-d',
    'endDate'                 => 'required|date:Y-m-d'
]);

// then validate
$validation->validate();

if ($validation->fails()) {
    // handling errors
    $errors = $validation->errors();
    echo "<pre>";
    print_r($errors->firstOfAll());
    echo "</pre>";
    exit;
} else {
    // validation passes
    echo "Success!";
}

$dbConfig = require 'db-condfig.php';

$query = 'select b.name, date_format(gmv.date, "%Y-%m-%d"), sum(gmv.turnover) / 1.21 as total
from brands b
inner join gmv on gmv.brand_id = b.id
where gmv.date between :startDate AND :endDate
group by b.id, gmv.date';

$query = 'select date_format(gmv.date, "%Y-%m-%d"), sum(gmv.turnover) / 1.21 as total
from gmv
where gmv.date between :startDate AND :endDate
group by gmv.date';

$reportDef1 = [
    'query' => 'select b.name, date_format(gmv.date, "%Y-%m-%d"), sum(gmv.turnover) / 1.21 as total
    from brands b
    inner join gmv on gmv.brand_id = b.id
    where gmv.date between :startDate AND :endDate
    group by b.id, gmv.date',
    'filterFields' => [
        ['name' => 'startDate', 'type']
    ]
];

$dbConn = DbConnection::createConnection($dbConfig);

$startDate = '2018-05-01';
$endDate = '2018-05-07';
$params = [':startDate' => $startDate, ':endDate' => $endDate];

$data = $dbConn->exec($query, $params, PDO::FETCH_NUM);
$headers = ['Brand', 'Date', 'Total Turnover'];

$csvStr = CsvBuilder::getCsvString(array_merge([$headers], $data));

print_r($csvStr);