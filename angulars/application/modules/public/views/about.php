<div class="container-fluid">
    <div class="container">
        <div class="col-lg-12 page-title">
            <h2><?php echo empty($pagetitle) ? "" : $pagetitle; ?></h2>

        </div>
    </div>
</div>

<div class="container page-details">
    <div class="col-lg-12 row">
        <div class="col-lg-6  page-details">
            <?php echo empty($pagecontent) ? "" : $pagecontent; ?> 
        </div>
        <div class="col-lg-6">

            <div class="owl-carousel">
                <?php
                    if(!empty($about_image)){
                    echo "<div> <img width='500'  src=" . get_about_image_path($about_image) . " alt=\"\"  /> </div>";
                    }
                ?>
            </div>   
        </div>
        <div class="col-lg-12 page-details">
        </div>
    </div>
    <div class="col-lg-12 page-details">

    </div>

</div>

</div>




