<?php
function  get_quotations($type, $store_id = null, $limit = 100000)
{
    $quotation_model = registry()->get('loader')->model('quotation');
    return $quotation_model->getQuotations($type, $store_id, $limit);
}