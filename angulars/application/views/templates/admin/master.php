<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo empty($template_title) ? lang('SITENAME') : $template_title; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

        <?php echo $template_css; ?>

        <script type="text/javascript">
            base_url = "<?php echo base_url(); ?>";
        </script>
        <?php echo $template_js; ?>
        <script>
            function blockUI()
            {
                $.blockUI({baseZ: 99999});
            }
            $(document).ready(function() {

                $('.select2').select2({
                    allowClear: true,
                    placeholder: 'select'
                });

                $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                    checkboxClass: 'icheckbox_flat-blue',
                    radioClass: 'iradio_flat-blue'
                });


            });

        </script>   

    </head>
    <?php echo $template_header; ?>
    <?php echo $template_content; ?>
    <?php echo $template_footer; ?>
</body>
</html>