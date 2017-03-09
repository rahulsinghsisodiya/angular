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
                foreach ($slides as $slide)
                    echo "<div> <img src=" . get_slider_image_path($slide['slidename']) . " alt=\"\"  /> </div>";
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

<script type="text/javascript">
    $(document).ready(function() {
        $('.owl-carousel').owlCarousel({
            autoplay: true,
            dots: true,
            loop: true,
            margin: 0,
            nav: false,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                1000: {
                    items: 1
                }
            }
        })
    });
</script>
