<?php

use Rakit\Validation\Validator;

class ReportBuilder
{

    /**
     * @var DbConnection $dbConn
     */
    private $dbConn;

    public function __construct(DbConnection $dbConn)
    {
        $this->dbConn = $dbConn;
    }

    public function getReportData(ReportDefinition $reportDef, $postParams = [])
    {
        $filterParams = $this->getFilterCriteria($reportDef, $postParams);
        $query = $reportDef->getBaseQuery();
        $fetchStyle = $reportDef->getType() === ReportDefinition::TYPE_CSV ? PDO::FETCH_NUM : PDO::FETCH_ASSOC;
        return $this->dbConn->exec($query, $filterParams, $fetchStyle);
    }

    public function getFilterCriteriaValidation(ReportDefinition $reportDef, $postParams = [])
    {
        $validator = new Validator();
        return $validator->make($postParams, $reportDef->getValidationRules());
    }

    public function getFilterCriteria(ReportDefinition $reportDef, $postParams = [])
    {
        $filterFields = array_column($reportDef->getFilterFields(), 'name');
        $filterParams = [];
        foreach ($filterFields as $filterField) {
            if (array_key_exists($filterField, $postParams)) {
                $filterParams[$filterField] = $postParams[$filterField];
            }
        }
        return $filterParams;
    }
}
