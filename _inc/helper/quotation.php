<?php
function  get_quotations($store_id = null, $limit = 100000)
{
    $quotation_model = registry()->get('loader')->model('quotation');
    return $quotation_model->getQuotations($store_id, $limit);
}