<script type="text/javascript">
    $(document).ready(function () {
        $('.fancybox').fancybox();
    });
</script>
<div class="container-fluid">
    <div class="container">
        <div class="col-lg-12 page-title">
            <h2><?php echo lang('Gallery'); ?></h2>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="container">
        <div class="col-lg-12">
            <?php
                if(!empty($arr_images))
                {
                    foreach ($arr_images as $image)
                    {
                        $imagename = base_url() . $upload_path .$image["imagename"];
                        $imagethumbname = base_url() . $upload_path .$image["imagethumbname"];
                        
                        if(is_file($upload_path .$image["imagethumbname"]))
                        {
                        ?>
                            <div class="col-lg-4 gallery">
                                <a class="fancybox" href="<?php echo $imagename; ?>" data-fancybox-group="gallery" title="<?php echo $image["imagetitle"]; ?>">
                                    <img src="<?php echo $imagethumbname; ?>" alt="<?php echo $image["imagetitle"]; ?>" />
                                </a>
                            </div>
                        <?php
                        }
                    }
                }
            ?>
        </div>
    </div>
</div>        
