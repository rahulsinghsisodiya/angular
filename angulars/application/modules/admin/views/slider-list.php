<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <?php
                
                if (!empty($message))
                {
                    ?>
                    <div class="alert alert-success margin-bottom">
                        <p><?php echo $message; ?></p>
                    </div>
                    <?php
                }
            ?>
            <div class="box box-info margin-bottom">
                <div class="box-header with-border">
                    <div class="pull-left"><h3 class="box-title"><strong><?php echo empty($table_heading) ? '' : $table_heading; ?></strong></h3></div>
                    <div class="pull-right">
                        <a href="<?php echo empty($new_entry_link) ? '' : $new_entry_link; ?>" class="btn bg-blue btn-sm btn-flat">
                            <i class="fa fa-plus"> <?php echo empty($new_entry_caption) ? '' : $new_entry_caption; ?></i>
                        </a>
                    </div>
                </div>

                <div class="box-body">
                    <?php echo $table; ?>
                </div>
            </div>

        </div>
    </div> 
</div> 
