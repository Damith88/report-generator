<?php

require 'vendor/autoload.php';

handleRequest($_GET + $_POST);

function handleRequest($requestParams) {
    $dbConfig = require 'db-condfig.php';
    $reportId = (int) $requestParams['reportId'];
    if ($reportId <= 0) {
        reportNotFound($reportId);
    }
    $reportDefs = json_decode(file_get_contents('./report-defs.json'), true);
    $reportDef = array_filter($reportDefs, function($def) use ($reportId) {
        return $def['id'] === $reportId;
    });
    if (count($reportDef) !== 1) {
        reportNotFound($reportId);
    }
    $reportDef = ReportDefinition::fromFile(array_pop($reportDef)['filePath']);
    $dbConn = DbConnection::createConnection($dbConfig);
    $reportBuilder = new ReportBuilder($dbConn);
    $filterParams = $reportBuilder->getFilterCriteria($reportDef, $requestParams);
    $validation = $reportBuilder->getFilterCriteriaValidation($reportDef, $filterParams);
    $validation->validate();
    if ($validation->fails()) {
        handleBadRequest($validation);
    }
    $headers = $reportDef->getHeaderFields();
    $reportData = $reportBuilder->getReportData($reportDef, $filterParams);
    responseCsv(array_merge([$headers], $reportData));
}

function responseCsv($reportData) {
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="report.csv";');
    
    CsvBuilder::printCsvString($reportData);
}

function reportNotFound($reportId) {
    http_response_code(404);
    die("Report not found. reportId: $reportId");
}

function handleBadRequest($validation) {
    http_response_code(404);
    $errors = $validation->errors();
    echo '<pre>';
    print_r($errors->firstOfAll());
    echo '</pre>';
    exit;
}
