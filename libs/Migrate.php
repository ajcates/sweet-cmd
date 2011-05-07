<?
class Migrate extends App {
    var $migrations = array();
    var $_current = null;

    var $_config = array(
        'folder' => '/resources/migrations/'
    );

    function __construct() {
        $this->_config['folder'] = LOC . $this->_config['folder'];
        $this->findMigrations();
        $this->lib('Migration');
    }

    function up() {
        $this->run($this->getCurrent() + 1, 'up');
        return $this;
    }

    function down() {
        $this->run($this->getCurrent(), 'down');
        return $this;
    }

    function till($numOrName) {
        $num = f_first($this->getMigration($nameOrNum));
        if($num == ($current = $this->getCurrent)) {
            return true;
        }
        $dir = $num > $current ? 'up' : 'down';

        while($num != $this->getCurrent()) {
            $this->run($this->getCurrent(), $dir);
        }
        return $this;
    }

    function findMigrations() {
        foreach(glob($this->_config['folder'] . '*.php') as $file) {
            $fileNameParts = explode('_', substr(f_last(explode('/', $file)), 0, -4));
            $this->migrations[f_first($fileNameParts)] = f_last($fileNameParts); 
        }
        return $this;
    }

    function getMigration($nameOrNum) {
        if(is_numeric($nameOrNum)) {
            $name = $this->migrations[$num = $nameOrNum];
        } else {
            $num = array_search($name = $nameOrNum, $this->migrations);
        }
        require_once($this->_config['folder'] . $num . '_' . $name . '.php');
        return array($num, new $name());
        //return new $name();
    }

    function run($nameOrNum, $dir) {
        f_last(
            $migration = $this->getMigration(trim($nameOrNum))
        )->$dir();
        $this->setCurrent(f_first($migration), $dir);
        return $this;
    }

    function setCurrent($num, $dir=null) {
        if($dir == 'down') {
            $num--;
        }
        $num = intval($num);
        //@todo hook this into the SweetFramework->end() event
        if(file_put_contents($this->_config['folder'] . 'current', ($this->_current = $num)) === false) {
            D::error('Couldn\'t write to the current file in the migrations folder');
        }
        return $num;
    }

    function getCurrent() {
        if(!isset($this->_current)) {
            $this->_current = file_get_contents($this->_config['folder'] . 'current');
        }
        return $this->_current;
    }

    function getName($num=null) {
        if(!isset($num)) {
            $num = $this->getCurrent();
        }
        $num = intval($num);
        return $this->migrations[$num];
    }
}
