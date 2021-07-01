<?php

require_once(get_template_directory() . esc_attr("/options_divi.php"));
global $options;
$epanel_key = "name";
$epanel_value = "Show RSS Icon";

$custom_options = array(

    array(
        "name" => esc_html__("Show LinkedIn Icon", $themename),
        "id" => $shortname . "_show_linkedin_icon",
        "type" => "checkbox",
        "std" => "on",
        "desc" => esc_html__("Here you can choose to display the LinkedIn Icon on your homepage. ", $themename)
    ),


    array(
        "name" => esc_html__("Show Youtube Icon", $themename),
        "id" => $shortname . "_show_youtube_icon",
        "type" => "checkbox",
        "std" => "on",
        "desc" => esc_html__("Here you can choose to display the Youtube Icon on your homepage. ", $themename)
    ),



    array(
        "name" => esc_html__("LinkedIn Profile Url", $themename),
        "id" => $shortname . "_linkedin_url",
        "std" => "#",
        "type" => "text",
        "validation_type" => "url",
        "desc" => esc_html__("Enter the URL of your LinkedIn Profile. ", $themename)
    ),



    array(
        "name" => esc_html__("Youtube Url", $themename),
        "id" => $shortname . "_youtube_url",
        "std" => "#",
        "type" => "text",
        "validation_type" => "url",
        "desc" => esc_html__("Enter the URL of your Youtube Profile. ", $themename)
    ),

    array(
        "name" => esc_html__("Footer credit", $themename),
        "id" => $shortname . "_footer_creds",
        "std" => "",
        "type" => "textarea",
        "desc" => esc_html__("Enter the credit for the footer. ", $themename)
    ),

);

foreach ($options as $index => $value) {
    if (isset($value[$epanel_key]) && $value[$epanel_key] === $epanel_value) {
        foreach ($custom_options as $custom_index => $custom_option) {
            $options = insertArrayIndex($options, $custom_option, $index + $custom_index + 1);
        }
        break;
    }
}



function insertArrayIndex($array, $new_element, $index) {
    $start = array_slice($array, 0, $index);
    $end = array_slice($array, $index);
    $start[] = $new_element;
    return array_merge($start, $end);
}

return $options;
