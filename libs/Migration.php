<?
// Not really doing much this class is.
class Migration extends App {
    var $Query;

    public function __sweetConstruct() {
        $this->Query = $this->lib('databases/Query');
        //$this->Util = $this->lib('dbUtil');
    }
}
