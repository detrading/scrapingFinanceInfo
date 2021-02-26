<?PHP

//require_once('simple_dom/simple_html_dom.php');

require_once("db_stocks.php");

ini_set('user_agent', 
  'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-GB; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3');

  //var_dump($argv[1]); 

  
	//$htmlContent = file_get_contents("https://markets.businessinsider.com/index/components/s&p_500?p=10");
	$string = 'https://markets.businessinsider.com/index/components/s&p_500?p='.$argv[1];
	$htmlContent = file_get_contents("$string");

	
	$DOM = new DOMDocument();
	$DOM->loadHTML($htmlContent);
	
	$Header = $DOM->getElementsByTagName('th');
	$Detail = $DOM->getElementsByTagName('td');
/*
    //#Get header name of the table
	foreach($Header as $NodeHeader) 
	{
		$aDataTableHeaderHTML[] = trim($NodeHeader->textContent);
	}
	print_r($aDataTableHeaderHTML); die();
*/


    foreach($Detail as $NodeHeader) 
	{
		$aDataTableHeaderHTML[] = trim($NodeHeader->textContent);
	}
	//print_r($aDataTableHeaderHTML);  echo PHP_EOL;// die();
	$maxkey = max(array_keys($aDataTableHeaderHTML));
	$key = 6;
do {
//insert data into database
$name = (string) SQLite3::escapeString($aDataTableHeaderHTML[$key]);
$price =  $aDataTableHeaderHTML[++$key];
$price = str_replace(array("\r", "\n"), '<', $price);
$price = strtok($price,'<');

//var_dump($price); die();


$sql = "INSERT INTO stockData ( Cname,
                             price, datetime_now) VALUES ( '$name', '$price', datetime('now') );";


//$db->exec($sql) or die($db->errorInfo()[2]);
if($db->exec($sql) ) { echo $key, ' | ', $name,' | ', $price, ' | SUCCESS', PHP_EOL;} 
				else { echo $key, ' | ', $name,' | ', $price, ' | FAIL',$db->errorInfo()[2], PHP_EOL; }




$key = $key + 10;
//echo $key, ' | ', $name,' | ', $price, PHP_EOL;
} while ($key <= $maxkey);

//key is less than 467

//print_r($aDataTableHeaderHTML); die();




/*


//get html content from the site.
$dom = file_get_html('https://www.imdb.com/title/tt0108162/reviews?ref_=tt_urv', false);


//collect all user’s reviews into an array
$answer = array();
if(!empty($dom)) {
$divClass = $title = '';$i = 0;
foreach($dom->find('.review-container') as $divClass) {
//title
foreach($divClass->find(".title") as $title ) {
$answer[$i][‘title’] = $title->plaintext;
}
//ipl-ratings-bar
foreach($divClass->find(".ipl-ratings-bar") as $ipl_ratings_bar ) {
$answer[$i][‘rate’] = trim($ipl_ratings_bar->plaintext);
}
//content
foreach($divClass->find('div[class=text show-more__control]') as $desc) {
$text = html_entity_decode($desc->plaintext);
$text = preg_replace("/\&#39;/", "‘", $text);
$answer[$i]['content'] = html_entity_decode($text);
}
$i++;
}
}
print_r($answer); exit;



*/
