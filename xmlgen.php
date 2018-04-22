<?php
////////////////////////////////////////////////////////////////////////////////
// Title:     XML Generator Script                                            //
// Author:    Ian carpenter                                                   //
// Filename:  webgen.php                                            	      //
//                                                                            //
// History      Version     Comments                                          //
// ========================================================================== //
// 07/11/2010 V0.01       First release is this script                  	  //
//                                                                            //
////////////////////////////////////////////////////////////////////////////////

$host = 'vw01.vectorwave.ltd.uk';
$database = 'DB_WEBCART';
$user = 'icarpenter';
$password = '';

$connection = mysql_connect($host, $user, $password)
        		or die ("Couldn't connect to server.");
$db = mysql_select_db($database ,$connection)
               	or die ("Couldn't select database.");
$query = "SELECT * FROM gbu0_prod";
$result = mysql_query($query, $connection);
//phpinfo();

$file= fopen("xmlgen.xml", "w");

$_xml .= '<?xml version="1.0" encoding="UTF-8" ?>' . "\r\n";
$_xml .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">' . "\r\n";
$_xml .= '<channel>' . "\r\n";
$_xml .= "\t" . '<title>Vectorwave</title>' . "\r\n";
$_xml .= "\t" . '<link>http://shop.vectorwave.co.uk</link>' . "\r\n";
$_xml .= "\t" . '<description>Vectorwave UK XML</description>' . "\r\n";

while($row = mysql_fetch_array($result)) {
	extract($row);
	$x = strip_tags($desclong);
	$y = preg_replace('/\s{2}/',' ',$x); // strip out spaces
	$new_desclong = preg_replace('/\s\s+/',' ',$y);// strip out double spaces

	$a = str_replace('&', '&amp;',$name); // remove '&'.
	$new_name = substr($a,0,54); // cut the string length to 54.

	$new_url= preg_replace('/[^A-Za-z0-9]/','_',$name);

if($prodview == "A") {	// Check for active products only

		if($pricestatus == "S") { // If Sale Price
			$price = number_format($saleprice,2);
			$featured = "y";
			}
		elseif($pricestatus == "R") { // Regular Price
			$price = number_format($regprice,2);
			$featured = "n";
			}
		$_xml .= "<item>\r\n";
		$_xml .=  "<title>" . $new_name . " Model " . $prodnum . "</title>\r\n";
		$_xml .=  "<link>http://shop.vectorwave.co.uk/prodshow/" . $new_url ."/" . $id . ".html</link>\r\n";
		$_xml .=  "<description>" . str_replace('&', '&amp;',$new_desclong) . "</description>\r\n";
		$_xml .=  "<g:image_link>http://shop.vectorwave.co.uk/media/gbu0/prodlg/" . $imglg . "</g:image_link>\r\n";
		$_xml .=  "<g:price>" . $price . "</g:price>\r\n";
		$_xml .=  "<g:condition>NEW</g:condition>\r\n";
		$_xml .=  "<g:id>" . $id . "</g:id>\r\n";
		$_xml .=  "<g:brand>" . $xbrand . "</g:brand>\r\n";
		$_xml .=  "<g:product_type>Cameras &amp; Optics &gt; Camera &amp; Optic Accessories</g:product_type>\r\n";
		$_xml .=  "<g:product_type>Electronics &gt; Electronics  Accessories</g:product_type>\r\n";
		$_xml .=  "<g:featured_product>" . $featured . "</g:featured_product>\r\n";

		//<g:mpn>GO1234568OOGLE</g:mpn>

	$_xml .=  "</item>\r\n";

	} // Ends if $prodview

} // closes while loop
$_xml .=  '</channel>' . "\r\n";
$_xml .=  '</rss>' . "\r\n";
///////// UNTESTED ////////////////
//$_xml str_replace('&', '&amp',$_xml);
////////////////////////////////////

fwrite($file, $_xml);
fclose($file);

echo "XML has been written.  <a href=\"xmlgen.xml\">View the XML.</a>";

?>
