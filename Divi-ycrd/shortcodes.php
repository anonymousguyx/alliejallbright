<?php
//shortcodes functions



//this one is for testing...it generated a random image where the [picture width="500" height="500"] shortcode is placed
function random_picture($atts) {
   extract(shortcode_atts(array(
      'width' => 400,
      'height' => 200,
   ), $atts));
return '<img src="http://lorempixel.com/'. $width . '/'. $height . '" />';
}


//red button shortcode...use: [redbutton link="http://youtube.com" text="Show video" width="150" height="30" align="left"] -- EXTERNAL LINKS OPENS AUTOMATICALLY IN A NEW TAB
function red_button($atts) {
   extract(shortcode_atts(array(
      'link' => "#",
	  'text' => "TEXT HERE",
	  'width' => "auto",
      'height' => "auto",
	  'align' => "left",

   ), $atts));
   ($align == "center") ? $classtouse = "button_red_sc_center" : $classtouse = "button_red_sc";
return '<span align="'. $align . '"><a href="' . $link . '"><span class="'. $classtouse . '" style="width:' . $width . 'px; height:' . $height . 'px; line-height:' . $height . 'px;">' . $text . '</span></a><span>';
}

//blue title shortcode...use: [bluetitle text="Go faster!" size="24" align="left"]
function blue_title($atts) {
   extract(shortcode_atts(array(
	  'text' => "TEXT HERE",
	  'size' => 18,
	  'align' => "center",
	  'link' => "",
	  'sunder' => false,
   ), $atts));
   if ($sunder) { $csunder = "<hr class='hrscsunder'>"; } else { $csunder = ""; }
   if ($link == "") {
		return '<span class="title_blue_sc" style="font-size:' . $size . 'px;" align="'. $align . '">' . $text . '</span>' . $csunder;
   } else {
		return '<span class="title_blue_sc" style="font-size:' . $size . 'px;" align="'. $align . '"><a href="' . $link . '" rel="nofollow" target="_blank">' . $text . '</a></span>' . $csunder;
   }

}

function show_sponsors() {
	return file_get_contents(get_stylesheet_directory_uri(). '/oursponsors.html');

}

function my_calendar_list_function($atts) {
   extract(shortcode_atts(array(
   		'day' => 0,
	  'calendar' => 1,
	  'show' => 2,
	  'month' => date("m"),
	  'year' => date("Y"),
   ), $atts));
   $month = intval($month);
   global $wpdb;
if ($day != 0) {
	$day = intval($day);
	$cevent = " AND ((DAYOFMONTH(date)=$day) OR (DAYOFMONTH(date_end)=$day AND MONTH(date_end) = $month AND YEAR(date_end) = $year))";
} else {
	$cevent = "";
}
   $my_output = '<br />';
   if ($month == 0) { //long list
			  $query_events = "SELECT date, date_end, title, time, text_for_date, soldout, link FROM wp_spidercalendar_event WHERE calendar = $calendar AND published = 1 AND date > CURDATE() ORDER BY date ASC LIMIT $show";
		$events = $wpdb->get_results($query_events);
		$tamount = count($events);
		$counterregs = 0;
	   foreach ($events AS $event) {
	      $my_output .= "<div class='row-fluid' align='center'>";
		  	$ori_time = strtotime($event->date);
		   $date = mysql2date("l, F j, Y",$event->date);
		   if ($event->date_end != "0000-00-00") {
		   		$date_end = " to " . mysql2date("l, F j, Y",$event->date_end);
		   } else { $date_end = ""; }
		   $title = $event->title;
		   $link = $event->link;
		   $time = $event->time;
		   $text_for_date = $event->text_for_date;
		   $soldout = $event->soldout;
		   $my_output .= "<div class='span8' align='left'>$date $date_end $title</div>";
		   if (($soldout) || ($ori_time < time())) { //soldout
			   $my_output .= "<div class='span2' align='center'><h4>SOLD OUT</h4></div>";
		   } else { //purchased
			   $my_output .= "<div class='span2' align='center'><a href='$link' target='_blank' rel='nofollow'><span class='button_red_sc_reg' style='width:230px; margin-top:5px;'>REGISTER</span></a></div>";
		   }
		   $counterregs ++;
		   if ($counterregs < $tamount) { $my_output .= "</div><hr class='hrcalendar'>"; } else { $my_output .= "</div>"; }

	   }
   } else { //short list
   		if ($cevent == "") $show += 4;
	   $query_events = "SELECT date, date_end, title, time, text_for_date, soldout, link FROM wp_spidercalendar_event WHERE calendar = $calendar AND published = 1 AND MONTH(date) = $month AND YEAR(date) = $year $cevent ORDER BY date ASC LIMIT $show";
	  // print "$query_events";
		$events = $wpdb->get_results($query_events);
	   foreach ($events AS $event) {
	   		  	$ori_time = strtotime($event->date);
		   $date = mysql2date("l, F j, Y",$event->date);
		   $datemysql = mysql2date("Y-m-d",$event->date);
		   if ($event->date_end != "0000-00-00") {
		   		$date_end = " to " . mysql2date("l, F j, Y",$event->date_end);
		   } else { $date_end = ""; }
		   $title = $event->title;
		   $link = $event->link;
		   $time = $event->time;
		   $text_for_date = $event->text_for_date;
		   $soldout = $event->soldout;
		   if ($cevent == "") {
			   $my_output .= "<h4 style='text-align:left;'><a href=\"javascript:showbigcalendar('bigcalendar1', 'http://ridelikeachampion.com/ycrs/wp-admin/admin-ajax.php?action=spiderbigcalendar_month&theme_id=13&calendar=2&select=month&date=$datemysql&many_sp_calendar=1&cur_page_url=http://ridelikeachampion.com/ycrs/&widget=0','1','0')\">$date $date_end $title </a></h4><hr />";
		   } else {
			   $my_output .= "<h4 style='text-align:left;'>$date $date_end $title </h4>$text_for_date";
			   if (($soldout) || ($ori_time < time())) { //soldout
				   $my_output .= "<h4  style='text-align:left;'>SOLD OUT</h4><hr />";
			   } else { //purchased
				   $my_output .= "<span align='left'><a href='$link' target='_blank' rel='nofollow'><span class='button_red_sc_reg' style='width:150PX;'>LEARN MORE</span></a><span><hr />";
			   }
		   }
	   }
	   if ($my_output == '<br />') { $my_output .= "<div align='center'><h4 style='text-align:left;'>No registered events for this month </h4></div>";}
   }



   $my_output .= '';


   return $my_output;

}

function linkblockfunction() {

$blocktitle = get_field('lbblock_title', 966);

$link = get_field('lblink1', 966);
$image = get_field('lbimage1', 966);
$title = get_field('lbtitle1', 966);
$text = get_field('lbtext1', 966);


$link2 = get_field('lblink2', 966);
$image2 = get_field('lbimage2', 966);
$title2 = get_field('lbtitle2', 966);
$text2 = get_field('lbtext2', 966);


$link3 = get_field('lblink3', 966);
$image3 = get_field('lbimage3', 966);
$title3 = get_field('lbtitle3', 966);
$text3 = get_field('lbtext3', 966);

$link4 = get_field('lblink4', 966);
$image4 = get_field('lbimage4', 966);
$title4 = get_field('lbtitle4', 966);
$text4 = get_field('lbtext4', 966);

$vstr = '<div align="center"><span class="title_blue_sc" style="font-size:26px;" align="center">' . $blocktitle . '</span><br /><br /><br />';

$vstr .= '<div align="center"><div class="row-fluid" style="max-width:800px;" align="center"><div class="span3"><a href="' . $link .'" rel="nofollow" target="_blank"><img src="'. $image .'" alt="img-img" width="100" height="100" class="alignnone size-full wp-image-98" /></a><h4>'. $title .'</h4>'. $text .'<span align="center"><a href="'. $link .'" target="_blank" rel="nofollow"><span class="button_red_sc_center" style="width:110px; height:25px; line-height:25px;">CLICK HERE</span></a><span></div>';

$vstr .= '<div class="span3"><a href="' . $link2 .'" rel="nofollow" target="_blank"><img src="'. $image2 .'" alt="img-img" width="100" height="100" class="alignnone size-full wp-image-98" /></a><h4>'. $title2 .'</h4>'. $text2 .'<span align="center"><a href="'. $link2 .'" target="_blank" rel="nofollow"><span class="button_red_sc_center" style="width:110px; height:25px; line-height:25px;">CLICK HERE</span></a><span></div>';

$vstr .= '<div class="span3"><a href="' . $link3 .'" rel="nofollow" target="_blank"><img src="'. $image3 .'" alt="img-img" width="100" height="100" class="alignnone size-full wp-image-98" /></a><h4>'. $title3 .'</h4>'. $text3 .'<span align="center"><a href="'. $link3 .'" target="_blank" rel="nofollow"><span class="button_red_sc_center" style="width:110px; height:25px; line-height:25px;">CLICK HERE</span></a><span></div>';

$vstr .= '<div class="span3"><a href="' . $link4 .'" rel="nofollow" target="_blank"><img src="'. $image4 .'" alt="img-img" width="100" height="100" class="alignnone size-full wp-image-98" /></a><h4>'. $title4 .'</h4>'. $text4 .'<span align="center"><a href="'. $link4 .'" target="_blank" rel="nofollow"><span class="button_red_sc_center" style="width:110px; height:25px; line-height:25px;">CLICK HERE</span></a><span></div>';

$vstr .= '</div></div></div>';



return $vstr;
}



function generate_my_gallery($atts) {
	   extract(shortcode_atts(array(
   		'id' => 1,
	  'has_wrapper' => false,
   ), $atts));

		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_gallery";

		$ret = "";
		$item_row = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id) );
		if ($item_row != null)
		{
			$data = json_decode($item_row->data);
		}
		//print_r($data);
		$numberofitemsbeforeshowmore = 5;
		$str_csm = "";
		$csm = 0;
		$ct = 0;
		if (isset($data->slides) && count($data->slides) > 0)
			{
				$ret .= '<div class="spacerdiv"></div><div align="center" id="myvideogallerycontainer">';
				foreach ($data->slides as $slide)
				{
					$csm ++;
					$ct++;
					$ret .= '<div id="divmyvideos' . $ct . '" ' . $str_csm . '" align="center"><div class="rowfluid" align="center">
<div class="span4" align="center" style="min-height: 165px;">' ;

$video = str_replace("/embed/","/v/", $slide->video);
//reduce title to 1 row
			$ti1l = $slide->title;
			if (strlen($ti1l) > 65) {
			  $ti1lt = substr($ti1l, 0, 65);
			  preg_match('/^(.*)\s/s', $ti1lt, $matches);
			  if ($matches[1]) $ti1lt = $matches[1];
			  $ti1l = $ti1lt .'...';
			}

$ret .= '<a href="'. $video .'"><img rel="shadowbox" src="' . $slide->image. '" alt=" ' . $ti1l . '" width="200" height="110" border="0" style="margin-top: 14px;"></a></div>';

//reduce description to 3 rows
			$des3l = $slide->description;
			if (strlen($des3l) > 260) {
			  $des3lt = substr($des3l, 0, 260);
			  preg_match('/^(.*)\s/s', $des3lt, $matches);
			  if ($matches[1]) $des3lt = $matches[1];
			  $des3l = $des3lt .'...';
			}





$ret .= '<div class="span7" align="left" style="min-height: 165px;"><h4>' . $ti1l . '</h4>' . $des3l . '</div>
</div><div class="spacerdiv"></div><div class="spacerdiv"></div><br /></div>';
if ($ct == $numberofitemsbeforeshowmore) { //show more
	$str_csm = 'style="display:none;"';
}
					//print "image:" .$slide->image;
					//print " video:" . $slide->video;
				//	print " title:" . $slide->title;
				//	print " des:" . $slide->description;
					//print "<hr>";

				}
				$ret .= '<div class="spacerdiv"></div><div class="rowfluid" id="showmorelink"><div class="span12" align="center"><h5>&nbsp;<a href="javascript:showmoremygallery();">SHOW MORE VIDEOS...</a></h5><div class="spacerdiv"></div></div></div><input name="showmorecount" id="showmorecount" type="hidden" value="5" /><input name="showmoretotal" id="showmoretotal" type="hidden" value="' . $ct . '" />';
				$ret .= "</div>";
			}

		return $ret;
	}

  //reading events from active.com
  function activereader($atts) {
     extract(shortcode_atts(array(
        'limit' => 100,
        'var2' => 0,
        'filter' => null
     ), $atts));
     //reading external page
     $todaydate = date("Y-m-d");
     $url = 'http://api.amp.active.com/v2/search?category=event&start_date=' . $todaydate . '..&exclude_children=true&per_page=' . $limit .'&org_id=cd44c34a-adae-40f6-8289-970cfefa5d51&sort=date_asc&api_key=vctps4m443jxnjsa7pcuns5f';
     //echo "URL: $url";
     $json = file_get_contents($url);
     $data = json_decode($json);
$strret = "";
$strret .= "<div id='readeractive'>";

//adding ACF repeater fields for events(names) to hide
$eventstohide = array();
if( have_rows('namestohide') ):
 	// loop through the rows of data
    while ( have_rows('namestohide') ) : the_row();
        $nametohide = get_sub_field('name_to_hide');
        $eventstohide[] = $nametohide;
    endwhile;
endif;

//decoding and printing json
foreach ($data->results as $value) {
  if (!in_array($value->assetName, $eventstohide)) {
        //cleaning dates and times
        $js_startdate = $value->activityStartDate;
        $startdate_array = explode("T",$js_startdate);
        $startdate = $startdate_array[0];
        $date = new DateTime($startdate);
        $date_comp = $date->getTimestamp();
        $startdate = $date->format('F j');
        $js_enddate = $value->activityEndDate;
        $enddate_array = explode("T",$js_enddate);
        $enddate = $enddate_array[0];
        $dateend = new DateTime($enddate);
        $enddate = $dateend ->format('F j, Y');
        if ($value->salesStatus == "registration-open") { $buttont = '<span class="button_red_sc_reg" style="width:175px; margin-top:5px;">REGISTER</span>'; } else { $buttont = '<span class="">SOLD OUT</span>'; }
        if (stripos($value->assetName, "Gift Certificates") !== false) { $buttont = '<span class="button_red_sc_reg">BUY HERE</span>'; }
//        echo $value->assetName . "-" . $filter . "\n";
        if (is_null($filter) || strpos($value->assetName, $filter) !== false) {
          $events[] = array($date_comp, $value->assetName, $startdate, $enddate, $value->place->placeName, $value->registrationUrlAdr, $buttont);
        }
  }
}
//adding ACF repeater fields
if( have_rows('add_missing_event') ):

 	// loop through the rows of data
    while ( have_rows('add_missing_event') ) : the_row();
        $mis_title = get_sub_field('title');
        $date_comp = strtotime(get_sub_field('start_date'));
        $mis_startdate = strtotime(get_sub_field('start_date'));
        $mis_startdate = date('F j, Y', $mis_startdate);
        $mis_enddate = strtotime(get_sub_field('end_date'));
        $mis_enddate = date('F j, Y', $mis_enddate);
        $mis_track = get_sub_field('track');
        $mis_link = get_sub_field('active_link');
        //print "$mis_title - $mis_startdate - $mis_enddate - $mis_track - $mis_link <br>";
     $events[] = array($date_comp, $mis_title, $mis_startdate,   $mis_enddate, $mis_track, $mis_link, '<span class="button_red_sc_reg" style="width:175px; margin-top:5px;">REGISTER</span>');

    endwhile;

else :

    // no rows found

endif;

usort($events, 'date_compare');
//print_r($programs);
//filling the return variable
foreach ($events as $value) {
        $mis_title = $value[1];
        $mis_startdate = $value[2];
        $mis_enddate = $value[3];
        $mis_track = $value[4];
        $mis_link = $value[5];
        $mis_b = $value[6];
        $strret .= '<div align="left"><div class="span8c">';
        $strret .= '<span class="calendareventtitle">'. $mis_title . '</span><br />';
        $strret .= $mis_startdate . " - " . $mis_enddate;
        $strret .= ' &#8594; <a href="https://ridelikeachampion.com/motorcycle-school/explore-the-motorcycle-track/">' . $mis_track . '</a>';
        $strret .= '</div><div class="span2c">';
        $strret .= '<a href="' . $mis_link . '" target="_blank" rel="nofollow">' . $mis_b .'</a>';
        $strret .= '</div></div>';
        $strret .= "<hr class='hrcalendar'>";
}

$strret .= "</div>";
return $strret;
  }


  //reading events from n2td
  function n2tdreader($atts) {
     extract(shortcode_atts(array(
        'limit' => 1000,
        'var2' => 0,
     ), $atts));
     //reading external page
     $todaydate = date("Y-m-d");
     $todayyear = date("Y");

     $url = 'http://connect.n2td.org/rest/1.0/api/events?a=demo&y=' . $todayyear;
     $json = file_get_contents($url);
     $data = json_decode($json);
     $programs = array();
$strret = "<ul>";
//decoding and printing json
foreach ($data as $value) {
        //cleaning dates and times
        $js_startdate = $value->EventDate;
        $startdate_array = explode("T",$js_startdate);
        $startdate = $startdate_array[0];
        $date = new DateTime($startdate);
        $startdate = $date->format('F j');
        $realdate = $date->getTimestamp();
        $Track = $value->Track;
        $City = $value->City;
        $State = $value->State;
        $Course = $value->Course;
        $fulldes = $Track . " " . $Course;
        $programs[] = array($realdate, $startdate, $fulldes, "N2 TRACK DAYS", "http://www.n2td.org/");
        //$strret .= '<a href="http://www.n2td.org/" target="_blank" rel="nofollow">One Day - ' . $startdate . '(' . $realdate . ') - '  . $Track . ' ' . $Course . '</a><br>';
}

//adding ACF repeater fields
if( have_rows('demo_program_block') ):

 	// loop through the rows of data
    while ( have_rows('demo_program_block') ) : the_row();

        $startdate = get_sub_field('date');
        $date = strtotime($startdate);
        $startdate = date('F j', $date);
        $description = get_sub_field('description');
        $provider = get_sub_field('provider');
        $link = get_sub_field('link');
      $programs[] = array($date, $startdate, $description, $provider, $link);

    endwhile;

else :

    // no rows found

endif;
usort($programs, 'date_compare');
//print_r($programs);
//filling the return variable
foreach ($programs as $value) {
        $startdate = $value[1];
        $description = $value[2];
        $provider = $value[3];
        $link = $value[4];
        $strret .= "<li><i>" . $startdate . "</i> - " . $description . " - <a href='" . $link . "' target='_blank' rel='nofollow'>" . $provider . "</a></li>";
}
$strret .= "</ul>";


return $strret;
  }
