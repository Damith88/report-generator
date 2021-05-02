<?php

use PHPUnit\Framework\TestCase;

class CsvBuilderTest extends TestCase
{
    /**
     * @dataProvider csvTestDataProvider
     */
    public function testGetCsvString($data, $csvString)
    {
        $this->assertSame(
            $csvString . "\n",
            CsvBuilder::getCsvString($data)
        );
    }

    /**
     * @dataProvider csvTestDataProvider
     */
    public function testPrintCsvString($data, $csvString)
    {
        ob_start();
        CsvBuilder::printCsvString($data);
        $actual = ob_get_contents();
        ob_end_clean();
        $this->assertSame(
            $csvString . "\n",
            $actual
        );
    }

    public function csvTestDataProvider() {
        return [
            [
                [['AAA', 'BBB', 'CCC']],
                'AAA,BBB,CCC'
            ],
            [
                [['AAA', 'BBB ', 'CCC  123']],
                'AAA,"BBB ","CCC  123"'
            ],
            [
                [['AAA, CCC', 'BB\'B ', 'CC"C  123']],
                '"AAA, CCC","BB\'B ","CC""C  123"'
            ]
        ];
    }
}