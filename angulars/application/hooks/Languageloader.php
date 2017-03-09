<?php

    class Languageloader
    {

        function initialize()
        {
            $ci = & get_instance();
            $ci->load->helper('language');
            $language = getCustomConfigItem("default_language");
            $site_lang = $ci->session->userdata('site_lang');

            if (empty($site_lang))
            {
                $ci->session->set_userdata('site_lang', $language);
                $site_lang = $language;
            }
            $ci->lang->load($site_lang, $site_lang);
        }

    }
    