<?php

namespace IWD\PaypalPos\Model\Backup;

class Convertor
{
    private $regExpReplace = [
        '/ auto_increment/i' => ' primary key autoincrement',
        '/ smallint[(][0-9]*[)] /i' => ' integer ',
        '/ tinyint[(][0-9]*[)] /i' => ' integer ',
        '/ int[(][0-9]*[)] /i' => ' integer ',
        '/ bigint[(][0-9]*[)] /i' => ' integer ',
        '/ enum[(][^)]*[)] /i' => '  varchar(255) ',
        '/ UNIQUE KEY /i' => ' KEY ',
        '/current_timestamp\(\)/i' => 'CURRENT_TIMESTAMP'
    ];

    private $regExpRemove = [
        '/ unsigned /',
        '/ ENGINE[ ]*=[ ]*[A-Za-z_][A-Za-z_0-9]*(.*DEFAULT)?/i',
        '/ CHARSET[ ]*=[ ]*[A-Za-z_][A-Za-z_0-9]*/i',
        '/ [ ]*AUTO_INCREMENT=[0-9]* /i',
        '/ character set [^ ]* /i',
        '/ on update [^,]*/i',
        '/ COMMENT[ ][\' | \"][\(\)\|\,-_0-9a-zA-Z \.\/]*[\'|\"]/i',
        '/ COMMENT[=][\' | \"][\(\)\|\,-_0-9a-zA-Z \.\/]*[\'|\"]/i',
        '/ FULLTEXT|KEY[ ]`[a-zA-Z0-9_]*`[ ]\((.*)\)[,|\r\n|\n|\r]/i',
        '/ PRIMARY KEY[ ]\((.*)\)[,|\r\n|\n|\r]/i'
    ];

    /**
     * @param $sql
     * @return string|string[]|null
     */
    public function replaceTableSql($sql)
    {
        $replace = array_keys($this->regExpReplace);
        $sql = preg_replace($replace, $this->regExpReplace, $sql);
        $sql = preg_replace($this->regExpRemove, ' ', $sql);
        $sql = preg_replace('/(,)([,|\r\n|\n|\r ]*\))/i', '$2', $sql);
        return $sql;
    }
}
