<?php
require_once __DIR__ .'/../Repository/StockBatchRepository.php';
class dashboardController{
    public function index(){
        $repository = new StockBatchRepository();
        $batches = $repository->getAll();
        require __DIR__ .'/../../templates/dashboard/index.php';
    }
}
