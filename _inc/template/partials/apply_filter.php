<?php
if (from()) : 
    $from = date('Y-m-d 00:00:00', strtotime(from())); 
    $to = to() ? date('Y-m-d 23:59:59', strtotime(to())) : date('Y-m-d 23:59:59', strtotime(from()));
    ?>

    <div class="apply-filter">
        <a href="<?php echo relative_url().$query_string; ?>" class="btn btn-xs btn-info" title="Remove this filter">
            <?php 
            $ftype = '';
            if (isset($request->get['ftype'])):?>
                <span class="label label-warning w-50">
                    <?php switch ($request->get['ftype']) {
                        case 'today':
                            $ftype = 'today';
                            echo 'Today';
                            break;
                        case 'week':
                            $ftype = 'week';
                            echo 'Last 7 Days';
                            break;
                        case 'month':
                            $ftype = 'month';
                            echo 'Last 30 Days';
                            break;
                        case 'year':
                            $ftype = 'year';
                            echo 'Last 365 Days';
                            break;
                    }?>
                </span>&nbsp;
            <?php endif;?>
            <strong><?php echo format_date(date('Y-m-d', strtotime($from))); ?>
            </strong>&nbsp;<i>To</i> &nbsp;
            <strong><?php echo format_date(date('Y-m-d 23:59:59', strtotime($to))); ?></strong> 
            <i class="fa fa-fw fa-close text-red"></i>
        </a>
    </div>
<?php endif; ?>