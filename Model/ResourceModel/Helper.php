<?php

namespace IWD\PaypalPos\Model\ResourceModel;

class Helper extends \Magento\Backup\Model\ResourceModel\Helper
{
    /**
     * @param string $tableName
     * @param int $count
     * @param int $offset
     * @return string
     */
    public function getPartInsertSql($tableName, $count = null, $offset = null)
    {
        $sql = null;
        $connection = $this->getConnection();
        $tableName = $connection->getTableName($tableName);
        //Magento commerce - remove created_in from a query
        $select = 'SELECT * FROM '.$tableName;
        if ($count) {
            $select .= ' LIMIT '.(int)$count;
        }
        if ($offset) {
            $select .= ' OFFSET '.(int)$offset;
        }
        $query = $connection->query($select);
        //old sqlite not support multiple insert
        while (true == ($row = $query->fetch())) {
            $sql .= sprintf('INSERT INTO %s VALUES ', $connection->quoteIdentifier($tableName));
            $sql .= $this->_quoteRow($tableName, $row);
            $sql .= ';' . "\n";
        }
        return $sql;
    }

    /**
     * @param $str
     * @return string
     * addslashes() should NOT be used to quote your
     * strings for SQLite queries; it will lead
     * to strange results when retrieving your data.
     */
    private function escapeForSqlite($str)
    {
        $str = substr($str, 1, -1);
        $str = \SQLite3::escapeString(stripslashes($str));
        return  "'".$str."'";
    }

    /**
     * Quote Table Row
     *
     * @param string $tableName
     * @param array $row
     * @return string
     */
    protected function _quoteRow($tableName, array $row)
    {
        $connection = $this->getConnection();
        $describe = $connection->describeTable($tableName);
        $dataTypes = ['bigint', 'mediumint', 'smallint', 'tinyint'];
        $rowData = [];
        foreach ($row as $key => $data) {
            if ($data === null) {
                $value = 'NULL';
            } elseif (in_array(strtolower($describe[$key]['DATA_TYPE']), $dataTypes)) {
                $value = $data;
            } else {
                $value = $this->escapeForSqlite($connection->quoteInto('?', $data));
            }
            $rowData[] = $value;
        }

        return sprintf('(%s)', implode(',', $rowData));
    }
}
