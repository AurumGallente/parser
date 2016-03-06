<?php
header("Content-type: application/json; charset=utf-8");
require ('db.php');
$query = "SELECT p.id as id, p.author as author, t.text as tag, mt.metatag as metatag, pg.title as title from posts as p
INNER JOIN posts_tags AS pt ON (pt.post_id=p.id)
INNER JOIN tags AS t ON (t.id=pt.tag_id)
INNER JOIN pages_posts AS pp ON (pp.post_id=p.id)
INNER JOIN pages AS pg ON (pg.id=pp.page_id)
INNER JOIN metaTags AS mt ON (mt.page_id=pg.id)
ORDER BY p.id
";
$result = $conn->query($query);
$posts = array();
$results = array();
while ($row = mysqli_fetch_assoc($result)) {
  
  $results[] = ($row);
}
foreach($results as $r){
  $posts[$r['id']]['author']=$r['author'];
  $posts[$r['id']]['tags'][]=$r['tag'];
  $posts[$r['id']]['title']=$r['title'];
  $posts[$r['id']]['metatag']=$r['metatag'];
  unset($posts['id']);
}
echo json_encode($posts, JSON_UNESCAPED_UNICODE);
//file_put_contents('posts.json', json_encode($posts, JSON_UNESCAPED_UNICODE), FILE_APPEND);