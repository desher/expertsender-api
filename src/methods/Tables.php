<?php

namespace desher\expertsender\methods;

use desher\expertsender\mappers;
use yii\base\Exception;

class Tables extends AbstractMethod
{
    /**
     * Add a row to table
     * @param $tableName
     * @param $rowData
     * @return bool
     */
    public function addRow($tableName, $rowData)
    {
        $result = $this->connection->post('DataTablesAddRow', $this->getRowXml($tableName, $rowData));
        return $result['code'] === 204;
    }

    /**
     * Delete row from datble
     * @param $tableName
     * @param array $rowData
     * @return bool
     */
    public function deleteRow($tableName, $rowData)
    {
        $xml = $this->connection->getDefaultRequestXml();
        $xml->addChild('TableName', $tableName);
        $pkColumns = $xml->addChild('PrimaryKeyColumns');
        foreach ($rowData as $columnName => $columnValue) {
            $columnXml = $pkColumns->addChild('Column');
            $columnXml->addChild('Name', $columnName);
            $columnXml->addChild('Value', $columnValue);
        }
        $result = $this->connection->post('DataTablesDeleteRow', $xml);
        return $result['code'] === 201;
    }

    /**
     * Find rows by condition
     * @param $tableName
     * @param array $selectColumns columns in result, ex. ['email', 'dog', 'age']
     * @param array $conditions, ex.:
     *  [
     *      ['ColumnName' =>'email', 'Operator' => 'Equals', 'Value' => 'test@text.test'],
     *      ['ColumnName' => 'dog', 'Operator' => 'Equals', 'Value' => 'beagle']
     *  ]
     * @return array
     * @throws Exception
     */
    public function getRows($tableName, $selectColumns, $conditions)
    {
        $xml = $this->connection->getDefaultRequestXml();
        $xml->addChild('TableName', $tableName);
        $columnsXml = $xml->addChild('Columns');
        foreach ($selectColumns as $columnName) {
            $columnsXml->addChild('Column', $columnName);
        }
        $conditionXml = $xml->addChild('WhereConditions');
        foreach ($conditions as $where) {
            $whereXml = $conditionXml->addChild('Where');
            $whereXml->addChild('ColumnName', $where['ColumnName']);
            $whereXml->addChild('Operator', $where['Operator']);
            $whereXml->addChild('Value', $where['Value']);
        }
        $result = $this->connection->post('DataTablesGetData', $xml, false);

        if (!$result['code'] === 200) {
            throw new Exception('Table row request error');
        }
        $lines = explode(PHP_EOL, trim($result['response']));
        unset($lines[0]);
        $data = [];
        foreach ($lines as $line) {
            $data[] = str_getcsv($line, ',', "\r\n");
        }
        return $data;
    }

    protected function getRowXml($tableName, $rowData)
    {
        $xml = $this->connection->getDefaultRequestXml();
        $xml->addChild('TableName', $tableName);

        $dataXml = $xml->addChild('Data');
        $columnsXml = $dataXml->addChild('Columns');

        foreach ($rowData as $columnName => $columnValue) {
            $columnXml = $columnsXml->addChild('Column');
            $columnXml->addChild('Name', $columnName);
            $columnXml->addChild('Value', $columnValue);
        }

        return $xml;
    }
}