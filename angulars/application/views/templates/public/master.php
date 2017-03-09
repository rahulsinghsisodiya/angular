<!DOCTYPE html>
<html class="no-js">
    <head>
        <title><?php echo (!empty($template_name)) ? $template_name : SITENAME; ?></title>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
         
           <script src="http://localhost/work/paint/assets/js/angular.js" type="text/javascript"></script>
        <?php echo $template_css; ?>

        <script>
            var base_url = '<?php echo base_url(); ?>';
        </script>
       
        <?php echo $template_js; ?>
    </head>

    <body>
        <?php echo $template_header; ?>
        <?php echo $template_content; ?>
        <?php echo $template_footer; ?>
    </body>
</html>