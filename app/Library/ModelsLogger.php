<?php

namespace Library;

use Doctrine\DBAL\Logging\SQLLogger;

class ModelsLogger implements SQLLogger
{
    /**
     * Logs a SQL statement somewhere.
     *
     * @param string     $sql    The SQL to be executed.
     * @param array|null $params The SQL parameters.
     * @param array|null $types  The SQL parameter types.
     *
     * @return void
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        // echo $sql.PHP_EOL;
        
        // if(!empty($params)) {
        //     var_dump($params);
        // }
        
        // if(!empty($types)) {
        //     var_dump($types);
        // }
    }

    /**
     * Marks the last started query as stopped. This can be used for timing of queries.
     *
     * @return void
     */
    public function stopQuery()
    {
    }
}