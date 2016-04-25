<?php $str = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
 

$str .= '<rss xmlns:media="http://search.yahoo.com/mrss/" version="2.0">';

$str .= "<channel>\n";
/*
$str .= "<title>".Yii::$app->params['appName']." - Top Stories</title>\n";
$str .= "<link>http://edition.cnn.com/index.html?eref=edition</link>\n";

$str .= "<description>CNN.com delivers up-to-the-minute news and information on the latest top stories, weather, entertainment, politics and more.</description>\n";
$str .= "<language>en-US</language>\n";
$str .= "<copyright>Copyright 2016 Petrica Nanca.</copyright>\n";
$str .= "<pubDate>Tue, 19 Apr 2016 13:33:53 EDT</pubDate>\n";
$str .= "<ttl>10</ttl>\n";
$str .= "<image>";
$str .= "<title>CNN.com - Top Stories</title>";
$str .= "<link>http://edition.cnn.com/index.html?eref=edition</link>";
$str .= "<url>http://i.cdn.turner.com/cnn/.e/img/1.0/logo/cnn.logo.rss.gif</url>";
$str .= "<width>144</width>";
$str .= "<height>33</height>";
$str .= "<description>CNN.com delivers up-to-the-minute news and information on the latest top stories, weather, entertainment, politics and more.</description>";
$str .= "</image>";


foreach($articles as $article) { 
$str .= "<item>";
$str .= "<title>".$article->title."</title>";
$str .= "<guid>http://edition.cnn.com/2016/04/19/politics/new-york-primary-delegates-donald-trump/index.html</guid>";
$str .= "<link>http://edition.cnn.com/2016/04/19/politics/new-york-primary-delegates-donald-trump/index.html?eref=edition</link>";
$str .= "<description>&lt;Br&gt;&lt;Br&gt;&lt;a href=&quot;http://podcast.cnn.com&quot; rel=&quot;nofollow&quot;&gt;&lt;img src=&quot;http://podcasts.cnn.net/cnnpodcasts/feeds/images/300-amanpour.jpg&quot;&gt;&lt;/a&gt;</description>";
$str .= "<pubDate>Tue, 19 Apr 2016 12:19:41 EDT</pubDate>";
$str .= "<media:thumbnail height=\"51\" width=\"90\" url=\"http://i2.cdn.turner.com/cnn/dam/assets/160419171500-donald-trump-04-17-top-tease.jpg\"/>";
$str .= "<media:content height=\"51\" width=\"90\" medium=\"image\" type=\"image/jpeg\" url=\"http://i2.cdn.turner.com/cnn/dam/assets/160419171500-donald-trump-04-17-top-tease.jpg\"/>";
$str .="</item>";
} 
*/

$str .= "</channel>";
$str .= "</rss>";

echo $str;