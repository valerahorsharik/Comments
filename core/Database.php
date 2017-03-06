<?php

namespace core;

class Database {
    
    /**
     *
     * Consist a \PDO instance.
     * 
     * @var \PDO|null
     */
    protected static $instance = null;

    private function __construct() {
        
    }

    public function __clone() {
        
    }
    
    /**
     * 
     * Create \PDO instance
     * 
     * @return \PDO
     */
    public static function instance() {
        if (self::$instance === null) {
            $config = self::getConfig();
            $opt = array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => TRUE,
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
            );
            $dsn = "mysql:host={$config['host']};dbname={$config['username']};charset=UTF8";
            self::$instance = new \PDO($dsn, $config['username'], $config['password'], $opt);
        }
        return self::$instance;
    }

    public static function __callStatic($method, $args) {
        return call_user_func_array(array(self::instance(), $method), $args);
    }
    
    /**
     * 
     * Execute a prepared $sql query with $args
     * 
     * @param string $sql
     * @param array $args
     * @return \PDOstatement
     */
    public static function run($sql, $args = []) {
        $stmt = self::instance()->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }
    
    /**
     * 
     * Return setting for PDO
     * 
     */
    private static function getConfig(){
        return require_once('config/db.php');  
    }

}
