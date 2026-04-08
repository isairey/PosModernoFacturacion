<?php
function  get_transfers($store_id = null, $limit = 100000)
{
    
    $transfer_model = registry()->get('loader')->model('transfer');
    return $transfer_model->getTransfers($store_id, $limit);
}