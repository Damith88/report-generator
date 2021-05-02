# report-generator
This is a sample project for generating reports based on a report definition.
This is a pure PHP project not using any framework.

## Sample curl request

```
curl --location --request POST 'http://localhost/report-generator/generateCsv.php?reportId=2' \
--form 'startDate="2018-05-01"' \
--form 'endDate="2018-05-07"'
```