<?
class dbUtil extends App {
    var $_dbConfig;

    var $_config = array(
        'backupFolder' => 'resources/migrations/backups/'
    );

    function __construct() {
        $this->_dbConfig = $this->lib('Config')->get('databases');
        $this->_config['backupFolder'] = $this->_config['backupFolder'];
    }

    function backup($databaseName) {
        $config = $this->_dbConfig[$databaseName];
        
        exec('mysqldump -u \'' . $config['username'] . '\' -h \'' . $config['host'] . '\' -p\'' . $config['password'] . '\' \'' . $config['databaseName'] . '\' | gzip -6 > ' . $this->_config['backupFolder'] . 'backup_' . $config['databaseName'] . '_' . date('Mj,g:ia') . '_db.sql.gz');
    }
}
