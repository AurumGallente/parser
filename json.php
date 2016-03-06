<?php
header("Content-type: application/json; charset=utf-8");
require ('db.php');
$query = "SELECT p.id as id, p.author as author, t.text as tag, mt.metatag as metatag, pg.title as title from posts as p
INNER JOIN posts_tags AS pt ON (pt.post_id=p.id)
LEFT JOIN tags AS t ON (t.id=pt.tag_id)
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
  isset($posts[$r['id']]['tags']) ?  null :  $posts[$r['id']]['tags']=array();
  in_array($r['tag'], $posts[$r['id']]['tags'])? null: array_push($posts[$r['id']]['tags'], $r['tag']);  
  $posts[$r['id']]['title']=$r['title'];  
  isset($posts[$r['id']]['metatags']) ?  null :  $posts[$r['id']]['metatags']=array();
  in_array($r['metatag'], $posts[$r['id']]['metatags'])? null: array_push($posts[$r['id']]['metatags'], $r['metatag']);  
}
echo json_encode($posts, JSON_UNESCAPED_UNICODE);