<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include $_SERVER["DOCUMENT_ROOT"] . "/sample_dev/samples/blog/data/sample_data.php";
$get_posts = $sample_data;
usort($get_posts, function($a, $b){return strtotime($b["post_date_time"])-strtotime($a["post_date_time"]);}); //sort by time with newest first

$url_config = array(
"abs_urls" => array("default" => "http://www.freshed.com/sample_dev/samples/blog/"), 
"blog_urls" => array("default" => "/index.php"), 
"blog_titles" => array("default" => "Freshed Joshua Lipinski Sample Development Blog with History API"), 
"blog_taglines" => array("default" => "Sample development."), 
"blog_rss_links" => array("default" => "http://www.freshed.com/sample_dev/samples/blog/"), 
"bio_abs_url" => "http://www.freshed.com/sample_dev/samples/blog/images/",
"blog_rss_images" => array("default" => ""),
"cat_special" => array("economy", "inflation", "legislation", "markets", "rates", "responsible_investing", "taxes"),
"variant_fields" => array("tweet_text")); 
function swap_abs($abs_url, $input_str){return preg_replace("/([\s*]href|src)(=[\"|'])(?!http)\/*(.*)(\"|')/i", "$1$2".$abs_url."/$3$4", $input_str);} //for sick abs
$fn_swap_abs = "swap_abs";
$gets = array("filter", "blog_name", "count", "start", "feed", "flavor", "post", "blogentry", "variant");
$gets = @array_combine(array_values($gets), array_map(function($v)use($_GET){return !empty($_GET[$v])?$_GET[$v]:false;}, $gets));
$bio_images = $_SERVER["DOCUMENT_ROOT"] . "/sample_dev/samples/blog/images/";
$posts = array("posts" => array());
$keepers = array();
$orphan_cats = array(); //for any categories that do not have a sort ordinal
$blog_prefix = (!$gets["blog_name"]?"Primary_Blog":$gets["blog_name"]) . "_"; //default return
$blog_tier = "";
$blog_prefix_tier = $blog_prefix . ($blog_tier===""?"Prod":"Test");
preg_match("/(.*_)(prod$|test$)/i", $gets["blog_name"], $override_prefix); //check if full blog name with _Prod or _test is passed in url
if(count($override_prefix) > 2){list($blog_prefix_tier, $blog_prefix, $blog_tier) = $override_prefix;} //override if full blog name is passed in url - for test feed

$assign_these = array("abs_urls", "blog_urls", "blog_titles", "blog_rss_links", "blog_rss_images", "blog_taglines"); //assign new blog-specific values without the "s" below
array_walk($assign_these, function($v)use(&$url_config, $blog_prefix){
    $v2 = rtrim($v, "s");
    $url_config[$v2] = isset($url_config[$v][$blog_prefix])?$url_config[$v][$blog_prefix]:$url_config[$v][key($url_config[$v])];
});
foreach($get_posts as $post_key => $post)
{
 if(strtolower($post["tag_group_name"]) == "blog syndication" && strtolower($post["tag_name"]) == strtolower($blog_prefix_tier)) //create separate array of posts to keep
 {
  $keepers[] = $post["media_id"];
 }
 else if(strtolower($post["tag_group_name"]) != "blog syndication" && strtolower(substr($post["tag_name"], 0, strlen($blog_prefix)))===strtolower($blog_prefix)) //only loop if tag has correct prefix
 {
  //do this first! if a variant param is passed, overwrite any variant fields with the "[variant]_" value
  if(!empty($gets["variant"])){$post = array_merge($post, array_combine($url_config["variant_fields"], array_map(function($v)use($post, $gets){return array_key_exists($gets["variant"]."_".$v, $post)?$post[$gets["variant"]."_".$v]:$post[$v];}, $url_config["variant_fields"])));} //set to variant if variant key exists otherwise set to non-variant key
	 
  if(!isset($posts["posts"][$post["media_id"]])){$posts["posts"][$post["media_id"]] = array();}
  $current = &$posts["posts"][$post["media_id"]];
  if(!isset($current["categories"])){$current["categories"] = array();} //create current post's categories array
  if(!isset($posts["cats"])){$posts["cats"] = array();} //create master categories array
  if(!isset($posts["authors"])){$posts["authors"] = array();}
  $cat_name = strtolower(str_replace($blog_prefix,"",$post["tag_name"]));

  if(empty($post["ordinal"])){$orphan_cats[$cat_name] = $post["tag_label"];} //this is an "orphan" category without a sort ordinal
  else
  {
	if(!isset($posts["cats"][(int)$post["ordinal"]])){$posts["cats"][(int)$post["ordinal"]] = array($cat_name, $post["tag_label"]);} //add to master category list to then sort by ordinal later
  //else {$posts["cats"][] = array($cat_name, $post["tag_label"]);}//add to master category list to then sort by ordinal later - remove jlipinski
  }
  
    $current["author1"] = $post["author_name1"]; //pull from fund manager data (pace) if available, otherwise use MM author name
	if(!isset($post["author_name2"])){$post["author_name2"] = "";}
    $current["author2"] = $post["author_name2"]; //pull from fund manager data (in websiteDB from pace) if available, otherwise use MM author name
    
  if(!empty($current["author1"]) && !isset($current["categories"][strtolower(str_replace(" ", "_", $current["author1"]))])) //for adding author names as an automatic category tag
  {
   $posts["authors"][strtolower(str_replace(" ", "_", $current["author1"]))] = $current["author1"];
   $current["categories"][strtolower(str_replace(" ", "_", $current["author1"]))] = $current["author1"];
  }
  if(!empty($current["author2"]) && !isset($current["categories"][str_replace(" ", "_", $current["author2"])])) //author name 2
  {
   $posts["authors"][strtolower(str_replace(" ", "_", $current["author2"]))] = $current["author2"];
   $current["categories"][strtolower(str_replace(" ", "_", $current["author2"]))] = $current["author2"];
  }
  
  $current["categories"][$cat_name] = $post["tag_label"]; //normalize tag name to lowercase and remove prefix 
  $current["print_id"] = strtolower(str_replace(" ", "_", $post["post_headline"]));
  $current["title"] = $post["post_headline"];
  $current["publish_date"] = @date("F j, Y | g:i A", strtotime($post["post_date_time"]));
  $current["related_author1"] = !empty($current["author1"])?strtolower(str_replace(" ", "_", $current["author1"])):""; //for matching up related posts to author category filter
  $current["author_img1"] = (file_exists($bio_images . str_replace(" ", "_", strtolower($post["author_name1"])) . ".jpg")?"<img src='".$url_config["bio_abs_url"].(str_replace(" ", "_", strtolower($post["author_name1"])) . ".jpg")."'>":"");
    $current["related_author2"] = !empty($current["author2"])?strtolower(str_replace(" ", "_", $current["author2"])):""; //for matching up related posts to author category filter
	  $current["author_img2"] = (file_exists($bio_images . str_replace(" ", "_", strtolower($post["author_name2"])) . ".jpg")?"<img src='".$url_config["bio_abs_url"].(str_replace(" ", "_", strtolower($post["author_name2"])) . ".jpg")."'>":"");
  $current["content"] = $current["content_rss"] = $post["post_body"];
  $current["disclosure"] = $post["post_disclosure"];
  $current["pullquote"] = $post["post_pullquote"];
  $current["more_info_text"] = $post["for_more_info_related_txt"];
  $current["more_info_url"] = $post["for_more_info_related_url"];
  
   //rss-only keys. these are removed from json array below.
  $current["post_date_time"] = $post["post_date_time"]; //rss
  $current["content_rss"];
    
  //"special categories": priority 1) active filter, 2) this iterated category...sorted by ordinal in dataservice so first has priority
  if(!isset($current["cat_special"])){$current["cat_special"] = "";}
  if(array_search($gets["filter"], $url_config["cat_special"])){$current["cat_special"] = $gets["filter"];}
  else if(array_search($cat_name, $url_config["cat_special"])!==false && empty($current["cat_special"])){$current["cat_special"] .= $cat_name;}
   
  array_walk($current, function(&$v){if(empty($v)){$v="";}}); //replace "null" with empty string, to be used in javascript template system on the frontend
 }
}
$posts["posts"] = array_values(array_intersect_key($posts["posts"], array_flip($keepers))); //only keep the whitelisted keepers list
if(!empty($posts["posts"]))
{
array_walk($posts["posts"], function(&$v){$v["print_id"] = urlencode($v["print_id"]); ksort($v["categories"]);}); //normalize the print_id for passing via url, also sort categories alphabetically, comes in handy when splitting author and categories below
ksort($posts["cats"]); //sort by ordinal
ksort($orphan_cats); //sort alphabetically by key name
ksort($posts["authors"]); //sort alphabetically by key name

$posts["cats"] = array_combine(array_map(function($v){return $v[0];}, $posts["cats"]), array_map(function($v){return $v[1];}, $posts["cats"])); //re-map to tag_name:tag_label
//function lastname_sort($a, $b){$aLast = @end(explode(' ', strtok($a, ","))); $bLast = @end(explode(' ', strtok($b, ","))); return strcasecmp($aLast, $bLast);} 
//uasort($posts["authors"], "lastname_sort"); //quick function for last name sort

$cat_keepers = array_flip(array_unique(array_reduce(array_map("array_keys", array_column($posts["posts"], "categories")), "array_merge", []))); //these are all category keys used in keeper posts
$posts["cats"] = array_merge($posts["cats"], $orphan_cats);
$posts["authors"] = array_change_key_case($posts["authors"], CASE_LOWER);
$posts["cats"] = array_intersect_key($posts["cats"], $cat_keepers); //remove cats that aren't used
$posts["authors"] = array_intersect_key($posts["authors"], $cat_keepers); //remove authors that aren't used

$posts["cats_separate"] = array("cats" => $posts["cats"], "authors" => $posts["authors"]); //add authors as a category
$posts["cats"] = array_merge($posts["cats"], $posts["authors"]);

if($gets["feed"] !== false) //feed is the only required parameter
{
 $un_posts = $posts; //need an unfiltered copy to do things like related content after the filter
    //the good thing is we now HAVE EVERYTHING and filters are applied below...makes filtering much easier and api calls easy to manage
     //filter by category     
 if($gets["filter"] && $gets["filter"] != "all"){$posts["posts"] = array_filter($posts["posts"], function($v) use ($gets){return in_array(strtolower($gets["filter"]), array_keys($v["categories"]));});}//lookup filter param after normalizing to lowercase
  
 //for related content by author...gotta do this here instead of on the front end because the front end data will already be filtered
 array_walk($posts["authors"], function(&$v, $k) use ($un_posts){
    $author_filtered = array_filter($un_posts["posts"], function($vv) use ($k){return in_array(strtolower($k), array_keys($vv["categories"]));}); //loop through posts and filter out this author
    $v = array("name" => $v, "related" => array_combine(array_map(function($vvv){return $vvv["print_id"];}, $author_filtered), array_map(function($vvv){return $vvv["title"];}, $author_filtered))); //related posts print_id:title
 });
 
 //for doing the category headline with the active category first, then cats sorted by ordinal, and the authors last
 array_walk($posts["posts"], function(&$v, $k)use($gets, $posts){
	$pull_sorted = array_intersect($posts["cats_separate"]["cats"], $v["categories"]); //change this to $posts["cats"] to include authors as well
	$active_filter = isset($pull_sorted[$gets["filter"]])?$pull_sorted[$gets["filter"]]:""; //remove active filter but dont remove "all"
	array_walk($pull_sorted, function(&$vv, $kk)use($active_filter){$vv = ($vv!=$active_filter?"<a href='?filter=" . $kk . "'>" . $vv . "</a>":"<b>" . $vv . "</b>");});
	$v["category_headline"] = implode(" | ", $pull_sorted);
 });	 

 if($gets["count"]){if(!$gets["start"]){$start = 0;}$posts["posts"] = array_slice($posts["posts"], $start, $gets["count"]);} //for only returning a few items or for future pagination
 if(!empty($gets["post"]) || !empty($gets["blogentry"])){$posts["posts"] = array_filter($posts["posts"], function($v) use ($gets){return ($v["print_id"] == $gets["post"] || $v["print_id"] == $gets["blogentry"]);});}  //filter by post (print_id)
 
 $access = true;
  if(count($override_prefix) > 2 && stristr($blog_tier, "test")) //if trying to access test feed by override, require http authentication
  {
    if(!($_SERVER["PHP_AUTH_USER"] == "testfeed" && $_SERVER["PHP_AUTH_PW"] == "f33dm3!"))
    {
      header("WWW-Authenticate: Basic realm=\"Private Area\"");
      header("HTTP/1.0 401 Unauthorized");
      echo "Need creds.\n";
      $access = false;
    }
  } 
 
 if($access && $gets["feed"] == "json")
 {
     $json_remove = array("post_date_time", "content_rss"); //these are used for rss makes no point to pass extra data
     array_walk($posts["posts"], function(&$v) use ($json_remove){$v = array_diff_key($v, array_flip($json_remove));});
    echo json_encode($posts); //output ONLY if filter param has been set
 }
 else if($access && $gets["feed"] == "rss")
 {
	$rss_items = implode("", array_map(function($v)use($url_config, $fn_swap_abs){
		$post_url_encoded = htmlspecialchars($url_config["abs_url"].$url_config["blog_url"]."?post=" . $v["print_id"], ENT_XML1);
		return !empty($v["content_rss"])?"<item>\n<title>" . htmlspecialchars($v["title"], ENT_XML1) . "</title>\n<link>". $post_url_encoded . "</link>\n<guid>". $post_url_encoded . "</guid>\n<pubDate>" . date('r', strtotime($v["post_date_time"])) . "</pubDate>\n<description>" .  htmlspecialchars($v["content_rss"], ENT_XML1) . "</description>\n<author>" . htmlspecialchars($v["author1"], ENT_XML1) . "</author>" . (!empty($v["author2"])?"\n<author>" . htmlspecialchars($v["author2"], ENT_XML1) . "</author>":"") . "\n<content:encoded>" . 
<<<EOT
<![CDATA[
{$v["content"]}
]]>
EOT
. "</content:encoded>\n</item>\n":"";}, $posts["posts"]));
     
    ob_end_clean(); //xml is invalid even if whitespace is published above
    header('Content-Type: application/xml');
    $ts = gmdate("D, d M Y H:i:s") . " GMT";
    header("Expires: $ts");
    header("Last-Modified: $ts");
    header("Pragma: no-cache");
    header("Cache-Control: no-cache, must-revalidate");
    echo '<?xml version="1.0" encoding="UTF-8"?' . ">\n";
    ?><rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/">
  <channel>
    <title><?php echo $url_config["blog_title"]; ?></title>
    <link><?php echo $url_config["blog_rss_link"]; ?></link>
    <atom:link href="<?php echo htmlspecialchars("http://".str_replace("-origin","",$_SERVER["HTTP_HOST"]).$_SERVER["REQUEST_URI"], ENT_XML1); ?>" rel="self" type="application/rss+xml" />
    <ttl>5</ttl>
    <description><?php echo $url_config["blog_tagline"]; ?></description>
    <language>en-us</language>
	 <pubDate><?php echo date('r', strtotime("-3 minutes")); ?></pubDate>
    <lastBuildDate><?php echo date('r', strtotime("-2 minutes")); ?></lastBuildDate>
<?php echo $rss_items; ?>
  </channel>
</rss><?php
  }
 }
}
?>
 