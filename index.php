<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);
set_time_limit(5*60);
require('simple_html_dom.php');
$servername = "localhost";
$username = "";
$password = "";
$url = "http://xdgif.ru/page/"; 
$conn = new mysqli($servername, $username, $password);
$conn->set_charset("utf8");
$conn->select_db("parser");
function stripTags(&$element){
  $element = strip_tags($element);
}
$firstPage = 1;
$lastPage = 51;
for($i=$firstPage;$i<=$lastPage;$i++){
  $currentUrl = $url.$i.'/';
  $html = file_get_html($currentUrl);
  $posts = $html->find('.post');
  $title = $html->find('title',0)->plaintext;
  $query = "INSERT INTO `pages` (page, html, url, title) VALUES($i, '{$conn->real_escape_string($html)}', '$currentUrl', '{{$conn->real_escape_string($title)}}')";   
  $conn->query($query);
  $query = "SELECT id FROM pages ORDER BY id DESC LIMIT 1";
  $result = $conn->query($query);
  while ($row = mysqli_fetch_row($result)) {
        $pageId =  ($row[0]);
    }
  foreach($posts as $post){
    $metaTags = array();
    $gif = $post->find('img',0)->src;
    $tags = explode(',',$post->find('.tags', 0));
    array_walk_recursive($tags, 'stripTags');    
    $meta = $html->find('meta');
    foreach($meta as $tag){
      $metaTags[]=$tag->content;
    }
    $author = $post->find('.user',0)->plaintext;  
  }
  $query = "INSERT INTO `posts` (`gif`, `author`) VALUES('$gif', '$author')";
  
  $conn->query($query);
  $query = "SELECT id FROM `posts` ORDER BY id DESC LIMIT 1";
  
  $result = $conn->query($query);  
  while ($row = mysqli_fetch_row($result)) {
        $postId =  ($row[0]);        
    }
    
    $query = "INSERT INTO `pages_posts` (`page_id`, `post_id`) VALUES ($pageId, $postId)";
    $conn->query($query);
    foreach($tags as $tag){
      $query = "INSERT IGNORE  INTO tags (`text`) VALUES('$tag')";
      $conn->query($query);
      $query = "SELECT id FROM `tags` WHERE `text`='$tag' LIMIT 1";
      $result = $conn->query($query);      
      while ($row = mysqli_fetch_row($result)) {       
        $tagId =  ($row[0]);
      }
      $query = "INSERT INTO `posts_tags` (`post_id`, `tag_id`) VALUES ($postId, $tagId)";
      $conn->query($query);
    } 
   
    foreach($metaTags as $k=>$v){
      if(!!$v){
        $query = "INSERT INTO `metaTags` (metatag, page_id) VALUES ('{$conn->real_escape_string($v)}', $pageId)";
        
        $conn->query($query);        
      }
    }
  }
$conn->close();