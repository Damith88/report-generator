# report-generator
This is a sample project for generating reports based on a report definition.
This is a pure PHP project not using any PHP framework.

## setup
* copy the content of this repo to web server.
* run `composer install` from the root directory
* create a virtual host pointing to the root directory

## Sample curl request

After setting up the web project you may use a http client like postman to generate the csv.
```
curl --location --request POST 'http://localhost/report-generator/generateCsv.php?reportId=2' \
--form 'startDate="2018-05-01"' \
--form 'endDate="2018-05-07"'
```

## Security
* currently all files (config and php files) are in the same directory. 
We should move publicly accessible content to new folder and make that the web root.
* all report queries are parameterized to prevewnt against sql injections.
* Also input is validated against the validation rules defined in the report definition.

## improvements
* currently there is no post formatting of data after retrieving them from the database. we may  implement that functionality in the `ReportBuilder` class and define the formatting for each display field in report definitions.

## Other Notes
* this is not a REST api, we can make this a rest API if needed.
