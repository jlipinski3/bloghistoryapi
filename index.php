<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<head>
<title>
Blog with API and History State
</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="jquery.sticky.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/spin.js/2.3.2/spin.min.js"></script>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" type="text/css"/>
<style>
body {margin: 10px}
body, td {font: 12px normal lucida sans, arial, sans-serif}
a {color: #ff6600}
a:hover {color: #00cc00}
i.fa {padding-left: 5px; padding-right: 5px}

.blog_filters, .insight_filters {padding-bottom: 1em; z-index: 192; position: relative}
.blog_filters ul, .insight_filters ul {list-style-type: none; margin: 0px; padding: 0px; position: relative}
.blog_filters .blog_filter_list, .insight_filters .blog_filter_list {display: table; table-layout: fixed; width: 100%; border-spacing: 2px 0px}
.blog_filters .blog_filter_list > li, .insight_filters .blog_filter_list > li {display: table-cell; list-style-type: none; padding: 5px; border: 1px solid #808080; font-weight: bold; color: #808080; font-size: 11px; vertical-align: middle; line-height: 16px; text-align: center}
    .blog_filters .blog_filter_list > li .fa, .insight_filters .blog_filter_list > li .fa {font-size: 25px; line-height: 10px; float: left}
    .blog_filters .blog_filter_list > li.active, .blog_filters .blog_filter_list > li:hover, .insight_filters .blog_filter_list > li.active, .insight_filters .blog_filter_list > li:hover {background-color: #808080; color: #ffffff}
.blog_filters .blog_filter_list .has_dropdown, .insight_filters .blog_filter_list .has_dropdown {}
    .blog_filter_list .has_dropdown .show_dropped, .blog_filter_list .has_dropdown.dropped .hide_dropped {display: none;}
    .blog_filter_list .has_dropdown.dropped .show_dropped, .blog_filter_list .has_dropdown .hide_dropped {display: inline-block;}
.blog_filter_list .more_dropdown {background: transparent; display: none; position: absolute; z-index:193; right: 0px; top: 100%; margin: 0; border-top: 2px solid transparent; text-align: left}
    .blog_filter_list .more_dropdown_inner {padding: 16px; padding-right: 0px; padding-top: 0px; border: 1px solid #808080; color: #000000; background: #ffffff}
    .blog_filter_list .more_dropdown .more_cats_wrapper {display: table-cell; vertical-align: top}
    .blog_filter_list .more_dropdown .more_cats_wrapper .more_cats_heading {font-size: 12px; line-height: 130%; text-align: left; text-transform: uppercase; padding-bottom: 8px; padding-top: 16px}
    .blog_filter_list .more_dropdown .more_cats_wrapper .more_cats_col_wrapper ul {list-style-type: none; display: table-cell; vertical-align: top; padding-right: 16px}
    .blog_filter_list .more_dropdown .more_cats_wrapper .more_cats_col_wrapper ul li {margin: 0; padding: 0; padding-top: .2em; padding-bottom: .2em; font-size: 12px; line-height: 130%; list-style-type: none; display: block; width: 172px; font-weight: normal}
        .blog_filter_list .more_dropdown .more_cats_wrapper .more_cats_col_wrapper ul li.active {color: #808080; font-weight: bold}
.blog_filters li, .insight_filters li {cursor: pointer}

.is-sticky .more_dropdown_inner {max-height: 400px; overflow-y: auto}

.blog_entry, .blog_entry div, .blog_entry p {text-align: left}
.blog_entry {border: 1px solid #e8e8e8; background: #f4f4f4; margin-bottom: 1em}
.blog_entry.blog_collapsed {background: #f4f4f4}
.blog_entry .blog_content_wrapper {display: table; width: 100%}
.blog_content_top {background: #e8e8e8; padding-left: 24px; padding-right: 24px; padding-top: .5em; padding-bottom: .5em; text-transform: uppercase; text-align: left; color: #666666;}
.blog_content_top .blog_date {margin: 0; font-size: 11px; font-weight: bold; letter-spacing: .1em}

.blog_content_bottom {padding: 30px; display: table; width: calc(100% - 60px);}
.blog_content_bottom .blog_left, .blog_content_bottom .blog_right {display: table-cell; vertical-align: top}
.blog_content_bottom .blog_right {width: 25%; padding-left: 1em}
.blog_content_bottom .blog_footer {border-top: 1px dotted #cccccc; margin-top: 19px; font-size: 15px; color: #818181}
    .blog_content_bottom .blog_footer a {}
.blog_content_bottom .related_title {color: #808080; letter-spacing: .1em; text-transform: uppercase; padding-top: 15px; padding-bottom: 11px; font-size: 14px}

.blog_left .more_less_bar {margin-top: 18px; padding-bottom: 18px}
    .blog_collapsed .blog_left .more_less_bar {margin-top: 37px;}
        .blog_less_link {display: inline; white-space: nowrap}
        .blog_more_link {display: none; white-space: nowrap}
        .blog_collapsed .blog_more_link {display: inline}
        .blog_collapsed .hide_more {display: none !important}
    .more_less_bar .more_less_left {float: left; white-space: nowrap}
    .more_less_bar .more_less_center {text-align: center; margin: auto; width: 25%; display: none}
    .more_less_bar .more_less_right {float: right}
.blog_left .more_less_center, .blog_left .more_less_right, .blog_left .more_less_left, .blog_left .more_less_center a, .blog_left .more_less_right a, .blog_left .more_less_left a {font-size: 14px; letter-spacing: .1em; text-transform: uppercase; line-height: 22px}
.blog_left .blog_more_link .fa, .blog_left .blog_less_link .fa {font-size: 130%}
.blog_left .title {font-size: 24px; color: #000000; font-weight: bold; font-family: arial; margin-bottom: 16px; margin-top: 8px; cursor: pointer}
.blog_left .blog_utilities {color: #808080; letter-spacing: .1em; text-transform: uppercase;}
    .blog_left .blog_utilities > span {display: inline-block; padding-left: 20px}
    .blog_left .blog_utilities .print_icon, .blog_left .blog_utilities .share_icon {display: inline-block; text-decoration: none; width: 16px; height: 16px; background-transparent; background-position: center center; background-size: 16px; background-repeat: no-repeat; margin-top: 3px; margin-right: 5px; float: left}
    .blog_left .blog_utilities .print_icon {background-image: url(/images/content/blog_icon_print.png); background-image: url(/images/content/blog_icon_print.svg), linear-gradient(transparent, transparent);}
    .blog_left .blog_utilities .share_icon {background-image: url(/images/content/blog_icon_share.png); background-image: url(/images/content/blog_icon_share.svg), linear-gradient(transparent, transparent);}
.blog_left .blog_body, .blog_left .blog_body p {font-size: 15px; line-height: 20px}
    .blog_left .blog_body li {font-size: 14px; line-height: 19px}
    .blog_left .blog_body img {max-width: 100%}
 
.blog_title .blog_title_left {display: none}
.blog_title .blog_title_right {text-align: left; display: table-cell; vertical-align: top}
.blog_title .blog_title_right .blog_category {font-size: 16px; color: #808080}
    .blog_title .blog_title_right .blog_category a {}
    .blog_title .blog_title_right .blog_category a:hover {text-decoration: underline}

.blog_filter_list li {background-color: #ffffff;}

.filter_icon {width: 23px; height: 18px; background-size: 18px 18px; background-repeat: no-repeat; background-position: left center; background-color: transparent; vertical-align: middle; display: none}

.blog_right .blog_contact {padding-bottom: 18px; min-height: 62px}
    .blog_right .blog_contact .image_left {display: table-cell; padding-right: 16px; width: 60px; text-align: left; vertical-align: top}
    .blog_right .blog_contact .image_right {display: table-cell; text-align: left; vertical-align: top}
    .blog_right .blog_contact a {}
    .blog_right .blog_contact a:hover {text-decoration: underline}
    .blog_right .blog_contact .image_container {width: 60px; height: 60px; overflow: hidden; border: 1px solid #dddcdd;}
    .blog_right .blog_contact .image_container img {width: 60px; height: auto;}
    .blog_right .blog_contact p {margin: 0px; font-size: 13px; line-height: 18px;}
    .blog_right .blog_contact .author_title {font-weight: bold}
    .blog_right .blog_quote {font-style: italic; padding-top: 1em; font-size: 130%}
    .blog_right .blog_quote .hanging_quote_left, .blog_right .blog_quote .hanging_quote_right {display: none}
    .blog_right .blog_quote .hanging_quote_left {margin-left: -15px}
    .blog_right .blog_related {padding-top: 1em}
    .blog_right .blog_related div, .blog_right .blog_related p, .blog_right .blog_related li {font-size: 13px; line-height: 18px; }
    .blog_right .blog_related ul {list-style-type: square}
    .blog_right .blog_related li {padding-bottom: .15em; padding-top: .15em;}
    .blog_right .blog_related .blog_related_header {padding-top: 2em; padding-bottom: .5em; font-weight: bold;}
    .blog_right .blog_related .blog_related_all {padding-top: .5em; padding-bottom: .5em; font-weight: bold; text-align: right;}
    .blog_right a {}
    .blog_right a:hover {text-decoration: underline}

.blog_footer {clear: both}
.blog_footer a {}

div.single_post {background-color: #808080; padding: 10px; font-weight: bold; color: #ffffff; margin-bottom: 4px; border-top: 4px solid #ffffff; text-align: center; text-decoration: none; display: none; width: 100%; clear: both;}
a#single_post {background-color: #808080; font-weight: bold; color: #ffffff; text-align: center; text-decoration: none; width: 100%; clear: both;}
a#single_post:hover {text-decoration: underline}
div.single_post.show_msg {display: block}
div#spinner {position: absolute; top: 0px; z-index: 191; height: 100px; width: 100%; padding: 0px; margin: 0px; text-align: center; float: none; clear: both; display: none}
.blog_content_area {position: relative; min-height: 100px}

@media all and (max-width: 1024px){
    .blog_content_bottom {display: block}
    .blog_content_bottom .blog_left, .blog_content_bottom .blog_right {display: block; float: none; width: 100%}
    .blog_content_bottom .blog_left, .blog_content_bottom .blog_right {display: block; width: 100%; padding: 0}
    .blog_content_bottom .blog_right {padding-top: 2em; border-top: 1px dotted #cccccc; margin-top: 1em}
        .blog_right .blog_quote, .blog_right .blog_related {padding-top: .5em;}
    .blog_filter_list .more_dropdown {left: 0px; right: initial}
    .blog_filter_list .more_dropdown .more_cats_wrapper, .blog_filter_list .more_dropdown .more_cats_wrapper
    .more_cats_col_wrapper ul {display: block}
    .blog_filters .blog_filter_list {display: block; margin: 0}
        .blog_filters .blog_filter_list > li {display: inline-block; margin: 0px 1px 1px 0px}
        .blog_filter_list .filter_icon {float: none !important; height: 18px !important;}
}


</style>
</head>
<body id="main">
<div class="blog_filters filter_wrapper" id="top_filters">
    <ul class="blog_filter_list linkTrack" id="blog_filter_list">
        <li class="active" data-filter="all"><div class="filter_icon"></div>All Posts</li><li class="has_dropdown" data-filter="more">
            <span class="show_dropped"><i class="fa fa-angle-down" aria-hidden="true"></i></span>
            <span class="hide_dropped"><i class="fa fa-angle-up" aria-hidden="true"></i></span>
            <div class='filter_icon'></div> More
            <div class="more_dropdown"><div class="more_dropdown_inner">
			<div class="more_cats_wrapper more_first"><div class="more_cats_heading">Topics</div>
				<div class="more_cats_col_wrapper"><ul></ul></div>
			</div>
			<div class="more_cats_wrapper more_second"><div class="more_cats_heading">Authors</div>
				<div class="more_cats_col_wrapper"><ul></ul></div>
			</div>
			</div></div>
        </li>
    </ul>
</div>
<div class="single_post"><a href="<?php echo $_SERVER["PHP_SELF"];?>" id="single_post">The article below is presented as a single post. Click here to view all posts.</a></div>
<div class="blog_content_area"><div id="spinner"></div><div id="blog_content"></div></div>
<script language="javascript">
var blogentry = "<?php if(isset($_GET["post"])){echo $_GET["post"];} ?>";
var single_post_msg = ("<?php if(isset($_GET["post"])){echo $_GET["post"];} ?>"!="");
var blog_name = "Primary_Blog";
var ajax_calls = typeof ajax_calls!="undefined"?ajax_calls:{}; //to be used as ajax call reference for aborting after delivering data
ajax_calls["blog_content"] = [];
var page_params = <?php echo json_encode(array_intersect_key($_GET, (array_flip(preg_grep('/blog_|db|filter|post/i', array_keys($_GET)))))); ?>;
var landing_hash = decodeURIComponent(window.location.hash.substring(1)).toLowerCase().replace(/\s+/g,''); //for going to a specific post - should only happen on initial load
var initialize_blog = true;
var active_filter = "";
var norewrite = (blogentry!="");
var related_count = 5;

blog_templates = {
'Primary_Blog': '<div class="blog_entry blog_expanded" id="-Xprint_idX-">\
<div class="blog_content_wrapper">\
<div class="blog_content_top"><p class="blog_date">-Xpublish_dateX-</p></div>\
<div class="blog_content_bottom">\
<div class="blog_left">\
<div class="blog_title"><div class="blog_title_left check_cat_special"><span class="blog_title_icon -Xcat_specialX- is_cat_special">-Xcat_specialX-</span></div><div class="blog_title_right"><div class="blog_category">-Xcategory_headlineX-</div><h1 class="title">-XtitleX-</h1></div></div>\
<div class="blog_body">-XcontentX-<div class="check_postdisclosure"><br><hr><br><div class="is_postdisclosure" style="font-style: italic">-XdisclosureX-</div></div></div>\
<div class="more_less_bar">\
<div class="more_less_left hide_more"><a class="top_link" href="#" data-attrib="#top_filters">Back to Top <i class="fa fa-angle-double-up" aria-hidden="true"></i></a></div><div class="more_less_right blog_utilities hide_more"><a href="?post=-Xprint_idX-">View Individual Post</a></div><div class="more_less_center"><a href="#" class="hide_more blog_less_link" data-lid="-Xprint_idX-">Read Less <i class="fa fa-angle-up" aria-hidden="true"></i></a></div>\
</div>\
<div class="blog_footer hide_more check_moreinfo">\
<div class="related_title">Related Content</div><a href="-Xmore_info_urlX-" class="is_moreinfo">-Xmore_info_textX-</a></span>\
</div>\
</div>\
<div class="blog_right">\
<div class="blog_contact"><span class="image_left"><div class="image_container check_authimg1"><span class="is_authimg1">-Xauthor_img1X-</span></div></span><span class="image_right"><p class="author_title">-Xauthor1X-</p></span></div>\
<div class="blog_contact check_author2"><span class="image_left"><div class="image_container check_authimg2"><span class="is_authimg2">-Xauthor_img2X-</span></div></span><span class="image_right"><p class="author_title is_author2">-Xauthor2X-</p></span></div>\
<div class="blog_quote top-bordered hide_more check_pullquote"><span class="hanging_quote_left">"</span><span class="is_pullquote">-XpullquoteX-</span><span class="hanging_quote_right">"</span></div>\
<div class="blog_related hide_more"><span class="related_authors_-Xrelated_author1X-"></span><span class="related_authors_-Xrelated_author2X-"></span></div>\
</div>\
</div>\
</div>\
</div>',
}
var menu_created = false;
//template system: replace -XreplaceX-
function templating(template, data_set) {
    return template.replace(/(-X)([A-Z0-9_]*)(X-)/gi, function(x, y, z) {
        try {
            return data_set[z];
        } catch (err) {}
    });
}
function badge_and_scroll(hash_scroll, span_id)
{
    link_count = $("#"+span_id+" a").length;
    if(link_count > 0)
    {
         $("#"+span_id+" a").each(function(i){
            if(hash_scroll!==false && i >= (link_count-1)){$(document).scrollTop($("#"+hash_scroll).offset().top);} //for scrolling to specific post via hashtag
         });
    } else if(hash_scroll!==false){$(document).scrollTop($("#"+hash_scroll).offset().top);}
}
function get_posts(ajax_params, span_id)
{
    $.each(ajax_calls[span_id], function(i,v){v.abort();}); //abort any outstanding requests
    ajax_calls[span_id] = [];
    var sent_data = $.extend({"filter":"all", "blog_name": blog_name, "feed": "json"}, ajax_params); //in case there should be any default params aka "filter: 'all'"
    if(ajax_calls[span_id].length < 2) //ensure there is only one ajax request outstanding
    {
      var hash_scroll = false;
	  if(!menu_created && initialize_blog) //on the first run of this, use php instead of ajax so the search engines have something to crawl
	  {
		 return_data = <?php $_GET["feed"] = "json"; $_GET["filter"] = isset($_GET["filter"])?$_GET["filter"]:""; $_GET["blog_name"] = "Primary_Blog"; include $_SERVER["DOCUMENT_ROOT"] . "/sample_dev/samples/blog/includes/fetch.php"; ?>;
		if(landing_hash != "" && return_data.cats.hasOwnProperty(landing_hash)){sent_data.filter = landing_hash; landing_hash = ""; create_cats(return_data.cats_separate, sent_data); active_tab(sent_data);} //for hash filter (#filter)
		else{on_done(return_data, sent_data, span_id, hash_scroll); on_always(sent_data, ajax_calls[span_id]);} //run extracted functions
	  }
	  else
	  {
		ajax_calls[span_id].push(
		   $.ajax({
		 url: "includes/fetch.php",
		 data: sent_data,
		 dataType: "json",
		 cache: false,
		 beforeSend: function(){ //add spinner while posts are loading...
			//$("#"+span_id).hide();
			$("#spinner").show();
		 }
		   })
		   .done(function(return_data){on_done(return_data, sent_data, span_id, hash_scroll);})
		   .always(function(){on_always(sent_data, ajax_calls[span_id]);})
		 );
	  }
	}
}
function on_done(return_data, sent_data, span_id, hash_scroll){
	if(sent_data.hasOwnProperty("filter") && sent_data.filter!="all" && !return_data.cats.hasOwnProperty(sent_data.filter)){delete sent_data.filter; get_posts(sent_data, span_id);}
    var new_content = "";
            
   $.each(return_data.posts, function(i,v){ //template system -XreplaceX-
	   new_content += (templating(blog_templates[blog_name], v));
   });           
   $("#"+span_id).html(new_content);
   
   $("#"+span_id+" [class*='check_']").each(function(){ //template system: below removes nodes that rely on content to be there
	   var this_post = $(this).closest(".blog_entry");
	   var check_class = $(this).attr("class").match(/check_[\w-]*\b/);
	   var relies_on = check_class[0].replace("check_","is_");
	   if(this_post.find("."+relies_on).html() == ""){$(this).remove();}
   });
   
   $("#"+span_id+" [class*='related_']").each(function(){ //for showing related authors
		var pieces = $(this).attr("class").match("related_(.*?)_(.*)");
		var block = $(this);
		var count = 0;
		
		if(pieces && pieces[1] != "" && return_data.hasOwnProperty(pieces[1]) && pieces[2] != "") //pieces[1] is key "authors", and pieces[2] is encoded author name
		{
			if(return_data[pieces[1]].hasOwnProperty(pieces[2])) //if posts.authors.this_author
			{
				block.append("<ul>");
				$.each(return_data[pieces[1]][pieces[2]]["related"], function(i,v){
					if(i != block.closest(".blog_entry").attr("id")){block.find("ul").append("<li><a href='?filter=" + pieces[2] + "#" + i + "'>" + v + "</a></li>"); count++; return count<related_count;} //add links but dont show current in list
				});
				if(block.find("a").length)
				{
					block.prepend("<div class='blog_related_header'>Additional " + return_data[pieces[1]][pieces[2]]["name"] + " Posts</div>");
					block.append("<div class='blog_related_all'><a href='?filter=" + pieces[2] + "'>See All &raquo;</a></div>");
				}
			}
		}
   });
   
	$("#"+span_id+" img").error(function(){ //remove broken images...important for hash scroll offset
		$(this).closest(".image_container").addClass("swept");
	});
   
	/* //read more foldup
	if(!single_post_msg || !$("#"+blogentry, span_id).length){ //dont do the read more if this is a single post view
		$(".blog_entry .blog_body").each(function(){ //add read more link and hide after second paragraph
			var blog_entry = $(this).closest(".blog_entry");
			if($(this).find("p").length > 2) //dont hide pieces if this is in the #hash url or this is in single post or print mode
			{
				var read_more = "<!--read_more-->";
				$(this).find("p:eq(0)").after(read_more); //only show first paragraph
				blog_entry.find(".more_less_center").html("<a href='#' class='blog_more_link' data-lid='" + sent_data.blog_name + "-Read-More-" + blog_entry.attr("id") + "'>Read More <i class='fa fa-angle-down' aria-hidden='true'></i></a><a href='#' class='hide_more blog_less_link' data-lid='" + sent_data.blog_name + "-Read-Less-" + blog_entry.attr("id") + "'>Read Less <i class='fa fa-angle-up' aria-hidden='true'></i></a>");
				var new_manip = ($(this).html()).replace(/([\s\S]*)(<!--read_more-->)([\s\S]*)/g, "$1<span class='hide_more'>$3</span>"); //theres no way to wrap everything after read more without regex
				$(this).html(new_manip);
			}

			if(landing_hash != blog_entry.attr("id") && blogentry == ""){blog_entry.removeClass("blog_expanded").addClass("blog_collapsed");} //collapse if its not a hash param
		});
	} */
   
	if(landing_hash != "" && $("#"+landing_hash).length){hash_scroll = landing_hash;} //for vanity url post hash
	if(menu_created == false){create_cats(return_data.cats_separate, sent_data);} //only run on initialization

	if($("#"+span_id+" .blog_entry").length && hash_scroll) //conditions to keep showing spinner
	{
		if($("#"+span_id+" img").length) //only show posts after images are loaded if using a hash_scroll
		{
			img_count = 0;
			$("#"+span_id+" img").on("load", function(){ //iterate through images as they load
				img_count++;
				if(img_count >= $("#"+span_id+" img").length - 1){$("#spinner").hide(); $("#"+span_id).show(0, function(){badge_and_scroll(hash_scroll, span_id);});}
					 });
		} else {$("#spinner").hide(); $("#"+span_id).show(0, function(){badge_and_scroll(hash_scroll, span_id);});} //show and scroll if there are no images
	} else {$("#spinner").hide(); $("#"+span_id).show(0, function(){badge_and_scroll(hash_scroll, span_id);});} //show everything right away if no hash_scroll
	
	if(single_post_msg && $("#"+blogentry, "#"+span_id).length){$("div.single_post").addClass("show_msg");}else if(single_post_msg){$("div.single_post").addClass("show_msg").html("You have specified an incorrect post. Please select from one of the categories above.");}  //show single post msg if using a blogentry or post param
}
function on_always(sent_data, check_calls){
	$.each(check_calls, function(i,v){v.abort();}); //abort any outstanding requests
	var clean_url = $.extend({}, sent_data);
	delete clean_url.blog_name; //remove the blog_name from the params for the address bar and history
	delete clean_url.feed; //remove the feed type from the params for the address bar and history
	if(clean_url.filter=="all"){delete clean_url.filter;} //cleanse the url of "all" which is default
	var url_add = $.param(clean_url); //for preserving URL in historyAPI
	var push_url = (location.protocol+'//'+location.host+location.pathname+(url_add!=""?"?"+url_add:""));
	if(menu_created && !initialize_blog){
		if(history.pushState && !sent_data.synthetic){history.pushState(sent_data, document.title, push_url);}
		$("div.single_post").removeClass("show_msg");
		norewrite = false;
	} // modify history to swap in this new url - if supported. dont run on initialization. replace filter all with blank.
	else
	{//only do url rewriting and scroll if not a hard php param
		if(blogentry == ""){if(landing_hash == "" && history.replaceState){history.replaceState(sent_data, document.title, push_url);}} //set the first state to a clean url so it can be gone back to 
		initialize_blog = false;
	}
}
function create_cats(cats_array, initial_params){
	var cats_num = Object.keys(cats_array["cats"]).length;
	var primary_filters = 3; //not counting all or more
	var cats_more_num = cats_num - primary_filters;
	
	var auth_num = Object.keys(cats_array["authors"]).length;
	var auth_cols = Math.ceil(auth_num/cats_more_num);
	auth_cols = auth_cols>3?3:auth_cols; //max cols is 3
	
    var top_filters = $(".blog_filters .blog_filter_list");
    var more_tab = $(".has_dropdown", top_filters);
    
    var total = 0;
    var per_col = 0;

    $.each(cats_array["cats"], function(i,v){ //use key as data-filter for consistency when passing to backend and use value as button text
        if(total < primary_filters){more_tab.before($("<li>").text(v).attr("data-filter", i)); more_tab.prev("li").prepend("<div class='filter_icon'>");}
        else{$(".more_cats_wrapper.more_first .more_cats_col_wrapper ul", more_tab).append($("<li>").text(v).attr("data-filter", i)); per_col++}
		total++;
    });
	
	if(!$(".more_first li", more_tab).length){$(".more_first").hide();} //hide first col of more if empty
	if(auth_num/per_col > auth_cols){per_col = Math.ceil(auth_num/auth_cols);} //shoot for either as many rows as the first cat but max out at 3 cols
	
	var auth_count = 0;
    $.each(cats_array["authors"], function(i,v){ //use key as data-filter for consistency when passing to backend and use value as button text
		if(total < primary_filters){more_tab.before($("<li>").text(v).attr("data-filter", i));} //add authors to regular filter bar first
		else
		{
			if(auth_count == per_col){$(".more_cats_wrapper.more_second .more_cats_col_wrapper", more_tab).append("<ul>"); auth_count = 0;} //create a new ul column
			$(".more_cats_wrapper.more_second .more_cats_col_wrapper ul:last", more_tab).append($("<li>").text(v).attr("data-filter", i));
			auth_count++; 
		}
        total++;
    });
	
    if(total>=primary_filters){$(".more_dropdown", more_tab).css({"max-width": String(top_filters.outerWidth()) + "px", "overflow" : "hidden" });}else{more_tab.hide();} //give more_tab some width and make visible
	
	top_filters.sticky({topSpacing:0}); //make "sticky headers"
	if(initial_params.hasOwnProperty("filter")){active_tab(initial_params);}
    menu_created = true;
}
function active_tab(params)
{
    if(params.filter)
    {
        var $this = $('.blog_filter_list [data-filter~="' + params.filter + '"]');
        var filters_list = $this.closest(".blog_filter_list");

		if($this.length) //valid filter name
		{
			if(!$this.hasClass("has_dropdown")) //not clicking on more dropdown
			{
			    active_filter = params.filter; //need this for history api
				$(".active", filters_list).removeClass("active"); //remove all actives...going to set a new active
				$this.addClass("active"); //set current to active;
				
				if($this.closest(".has_dropdown").length && !$this.closest(".has_dropdown").hasClass("active")){$this.closest(".has_dropdown").find(".more_dropdown").slideDown(250);$this.closest(".has_dropdown").addClass("active dropped");} //if this filter is inside the more dropdown, then drop the menu down on load
				else {filters_list.find(".more_dropdown").slideUp(250);filters_list.find(".has_dropdown").removeClass("active dropped");} //slide up more dropdown anytime a filter is clicked

				if(menu_created){get_posts(params, "blog_content");} //on initial run, get_posts is called on document.ready so dont call it twice
            } 
			else //clicking on more tab
			{
				if($this.find(".more_dropdown").is(":visible")) //if dropdown already dropped
				{
					$this.find(".more_dropdown").slideUp(250);
					$this.removeClass("dropped");
					if(!$this.find("li.active").length){$this.removeClass("active");} //user didnt make a selection inside the dropdown so remove active styling
				}
				else {$this.find(".more_dropdown").slideDown(250); $this.addClass("active dropped");} //drop down the more dropdown
			}
        }
    }
}

function back_to_top(e){e.stopPropagation(); e.preventDefault(); var scrollto = !$(this).data("attrib")?"#top_filters":$(this).data("attrib"); $("html,body").animate({scrollTop: $(scrollto).offset().top},1000);} // Scroll screen to top
    
$(document).scroll(function(){
	$(".blog_entry").each(function(){
		var top = window.pageYOffset;
		var distance = top - $(this).offset().top;
		var session_hash = $(this).attr("id");
		if(distance < 200 && distance > 0 && !norewrite) //scroll to within 'x' pixels
		{
		   if(history.replaceState){history.replaceState({"filter":active_filter}, document.title, "#"+session_hash);} //replace url with hash of blog post
		   //else{window.location.hash = "#"+session_hash;} //commented out was buggy in < ie9
		}
	});
	$("div.blog_filters").each(function() //for resetting url when at top
	{
		var top = window.pageYOffset; var distance = top - $(this).offset().top;
		if(distance < 50 && !norewrite) //scroll to within 'x' pixels
		{
			if(landing_hash == "" && history.replaceState){history.replaceState({"filter":active_filter}, document.title, window.location.href.split("#")[0]);} //if landing_hash should be preserved
			//else if(landing_hash == ""){window.location.hash = "";} //commented out was buggy in < ie9
			landing_hash = ""; //the eagle has landed
		}
	});
});

$(function(){
	var top_filters = $(".blog_filters .blog_filter_list");
	window.onpopstate = function(event){if(event && event.state){ //when using back or forward buttons
		clone_state = $.extend({}, event.state); //must do this for IE since it doesn't allow manipulation of event.state object
		if(clone_state.filter){clone_state.synthetic = true;}
		active_tab(clone_state);
	}else if('state' in window.history){window.location.reload();}} //reload if using hard php param like ?post=
	
	var target = document.getElementById("spinner"); //create the spinner to be toggled during data fetch
	var spinner = new Spinner({position:'relative',color:'#dddcdd'}).spin(target);
	get_posts(page_params,"blog_content", true); //need to call this first to dynamically create categories from blog content
	
		/* on() binders */
        top_filters.on("click", "li", function(e){e.preventDefault(); active_tab({"filter":$(this).data("filter")}); if(top_filters.closest(".sticky-wrapper").hasClass("is-sticky") && !$(this).hasClass("has_dropdown")){back_to_top(e);}}); //activate tab and get_posts when clicking tab
        $(".more_dropdown, .has_dropdown, .blog_filters").mouseleave(function(e){
			if(!$(e.toElement).hasClass("has_dropdown") && !$(e.toElement).hasClass("more_dropdown") && !$(e.toElement).parents(".blog_filters").length && !$(e.toElement).hasClass("blog_filters")){$(".more_dropdown").hide(); if(!$(".more_dropdown").find("li.active").length){$(".has_dropdown", top_filters).removeClass("active dropped");}}
		}); //close dropdown when cursor leaves but not if it goes up to the more button
		
		$(".blog_entry").on("click", ".title, .blog_more_link, .blog_less_link", function(x){ //unhide
			x.preventDefault();
			var blog_entry = $(this).closest(".blog_entry");
			if(blog_entry.hasClass("blog_collapsed")){blog_entry.removeClass("blog_collapsed").addClass("blog_expanded");}
			else if(blog_entry.hasClass("blog_expanded"))
			{
				blog_entry.removeClass("blog_expanded").addClass("blog_collapsed"); 
				$("html,body").animate({scrollTop: $(blog_entry).offset().top},1000); //after hitting read less then fix the screen to the top of current post
			}
			Analytics.autoTrack(this);                          
		});
		$(".blog_entry").on("click", ".blog_utilities a", function(){
			Analytics.autoTrack(this);                          
		});
		$(".top_link").on("click", function(e){e.preventDefault(); back_to_top(e);});
		/* end binders */
});
</script>
<div style="padding: 10px; margin: auto; text-align: center"><a href="includes/fetch.php?feed=rss" target="_blank"><i class="fa fa-rss" aria-hidden="true"></i>RSS FEED</a></div>                  
</body>
</html>
