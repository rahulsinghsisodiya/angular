<script type="text/javascript">
    $(document).ready(function () {

        CKEDITOR.config.allowedContent = true;
        CKEDITOR.dtd.$removeEmpty.i = 0;


        $("#cms-form").validate({
            ignore: [],
            rules: {
                "pagename": "required",
                "pagetitle": "required",
                "pagelanguage": "required",
                "pagecontent": {
                    required: function ()
                    {
                        CKEDITOR.instances.pagecontent.updateElement();
                    }
                }
            },
            messages: {
                "pagename": "<?php echo lang('pagename_required_error') ?>",
                "pagetitle": "<?php echo lang('pagetitle_required_error') ?>",
                "pagecontent": "<?php echo lang('pagecontent_required_error') ?>"
            },
            errorPlacement: function (error, element) {
                if (element.attr("id") == "pagecontent") {
                    error.insertAfter("#cke_pagecontent");
                } else if (element.attr("name") === "pagename") {
                    error.appendTo('#pagename-error');
                } else if (element.attr("name") === "pagelanguage") {
                    error.appendTo('#pagelanguage-error');
                } else {
                    error.insertAfter(element);
                }
            }
        });


        $('#pagename').change(function () {
            var pagename = $(this).val();
            window.location.href = "<?php echo base_url(); ?>admin/cms/cmsContent/" + pagename;
        })

        $("#cms-form").submit(function () {
            CKEDITOR.instances.pagecontent.updateElement();
        });


        $('#pagelanguage').on('change', function () {
            var value = this.value;
            var pagename = $("#pagename").val();
            var datastring = 'pagelanguage=' + this.value + '&pagename=' + pagename

                    ;

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?php echo base_url(); ?>admin/cms/getCmsBylanguage",
                data: datastring,
                cache: false,
                success: function (output) {
                    $('#pagetitle:input').val(output.pagetitle);

                    CKEDITOR.instances['pagecontent'].setData(output.pagecontent);
                }
            });
        });


    });
</script>


<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-md-10 col-md-offset-1">
            <div class="box box-info">
                <div class="box-header with-border margin-bottom">
                    <h3 class="box-title"><?php echo empty($form_caption) ? "" : $form_caption; ?></h3>
                </div>

                <?php
                    $errors = validation_errors();
                    if (!empty($errors))
                    {
                        ?>  
                        <div class="alert alert-danger col-md-9 col-md-offset-2">
                            <?php echo $errors; ?>
                        </div>
                        <?php
                    }

                    if (!empty($message))
                    {
                        ?>
                        <div class="alert alert-success col-md-9 col-md-offset-2">
                            <p><?php echo $message; ?></p>
                        </div>      
                        <?php
                    }
                ?>
                <div class="box-body">
                    <?php
                        $attributes = array('id' => 'cms-form', 'class' => 'form-horizontal');
                        echo form_open_multipart('admin/cms/saveCms', $attributes);
                    ?>
                    <input type="hidden" name="pagelanguage" id="pagelanguage" value="<?php echo empty($pagelanguage) ? NULL : $pagelanguage; ?>" />
                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="pagename"><?php echo lang('select_page'); ?></label>
                        <div class="col-lg-8">
                            <?php
                                echo form_dropdown('pagename', $arr_pages, empty($pagename) ? NULL : $pagename, ' id = "pagename" class="form-control select2" ');
                            ?>
                            <div id="pagename-error"></div>
                        </div>
                    </div>

                    <!--                    <div class="form-group">
                                            <label class="col-lg-3 control-label" for="pagelanguage"><?php // echo lang('language');            ?></label>
                                            <div class="col-lg-8">
                    <?php
                        // echo form_dropdown('pagelanguage', $arr_pagelanguage, empty($pagelanguage) ? NULL : $pagelanguage, ' id = "pagelanguage" class="form-control select2" ');
                    ?>
                                                <div id="pagelanguage-error"></div>
                    
                                            </div>
                                        </div>-->

                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="pagetitle"><?php echo lang('page_title'); ?></label>
                        <div class="col-lg-8">
                            <?php
                                $data = array(
                                    'name' => 'pagetitle',
                                    'id' => 'pagetitle',
                                    'value' => set_value('pagetitle', empty($pagetitle) ? NULL : $pagetitle),
                                    'class' => "form-control",
                                );

                                echo form_input($data);
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="pagecontent"><?php echo lang('page_content'); ?></label>
                        <div class="col-lg-8">
                            <?php
                                $pagecontent = empty($pagecontent) ? NULL : $pagecontent;

                                $data = array(
                                    'name' => 'pagecontent',
                                    'id' => 'pagecontent',
                                    'class' => 'ckeditor',
                                    'value' => $pagecontent
                                );

                                echo form_textarea($data);
                            ?>
                        </div>
                    </div>
                    <!--Add image for about us page -->
                    <?php
                        if (strcmp($pagename, 'About') == 0)
                        {
                            ?> 

                            <div class="box-body">
                                <!--                                <div class="form-group">
                                                                    <label class="col-lg-3 control-label" ><?php // echo lang('about_image');   ?></label>
                                                                    <div class="col-lg-8">
                                
                                                                        //<?php
//                                        $data = array(
//                                            'name' => 'imagetitle',
//                                            'id' => 'imagetitle',
//                                            'value' => set_value('imagetitle', empty($imagetitle) ? "" : $imagetitle, FALSE),
//                                            'class' => 'form-control',
//                                            // 'autofocus' => 'autofocus'
//                                        );
//                                        echo form_input($data);
                                ?>
                                                                    </div>
                                                                </div>-->
                                <input type="hidden" name="oldimage" value="<?php echo empty($about_image) ? "" : $about_image; ?> ">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="choose file"><?php echo lang('choose file'); ?></label>
                                    <div class="col-lg-8">

                                        <?php
                                        $data = array(
                                            'name' => 'about_image',
                                            'id' => 'about_image',
                                            'class' => 'form-control',
//                                        'multiple' => "multiple",
                                        );
                                        echo form_upload($data);
                                        ?>
                                    </div>
                                </div>
                            <?php } ?>
                        <!--End of the about image section -->
                        <?php
                            if (strcmp($pagename, 'Contact') == 0)
                            {
                                echo"<div class=\"form-group\" >";
                                echo"  <label class=\"col-lg-3 control-label\" for=\"pagecontent\">" . lang('left_content') . " </label>";
                                echo "<div class=\"col-lg-8\">";

                                $pagecontent = empty($pagecontent) ? NULL : $pagecontent;

                                $data = array(
                                    'name' => 'leftcontent',
                                    'id' => 'leftcontent',
                                    'class' => 'ckeditor',
                                    'value' => empty($leftcontent) ? NULL : $leftcontent
                                );
                                //p($pagename);

                                echo form_textarea($data);

                                echo " </div>
                            </div>";
                            }
                        ?>


                        <div class="box-footer " >
                            <div class="form-group">
                                <label class="col-lg-3 control-label">&nbsp;</label>
                                <div class="col-lg-9">
                                    <?php
                                        echo form_submit('submit', 'Save', ' class="btn bg-blue btn-flat"');
                                    ?>
                                    <?php
                                        echo form_reset('reset', 'Reset', ' class="btn bg-blue btn-flat"');
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                            echo form_close();
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </div>


