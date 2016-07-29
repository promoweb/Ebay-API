<?php

$key = 'TirathVe-Nascentu-PRD-513dad17f-07ddb580';

if(isset($_POST['category_id'])){

$category_id = $_POST['category_id'];
$types = $_POST['listing_type'];
$type_value = '';
$i = 0;
foreach($types as $type){
   $listing_type = 'itemFilter(0).name=ListingType';
   $type_value .= 'itemFilter(0).value('. $i .')=' . $type . '&';

   $i++;
}

$type = $listing_type .'&'. $type_value; 
$url = 'http://svcs.ebay.com/services/search/FindingService/v1?OPERATION-NAME=findItemsByCategory&SECURITY-APPNAME='.$key.'&RESPONSE-DATA-FORMAT=JSON&categoryId='.$category_id.'&'.$type.'paginationInput.entriesPerPage=100';


$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_TIMEOUT, 30);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$return = curl_exec($curl);
curl_close($curl);
$results = json_decode($return, true);

$items = $results['findItemsByCategoryResponse'][0]['searchResult'][0]['item'];
$totalEntries = $results['findItemsByCategoryResponse'][0]['paginationOutput'][0]['totalEntries'][0];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Ebay API</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Ebay API</h2>
  <form role="form" method="POST" action="" style="width: 40%;" >
    <div class="form-group">
      <label for="category_id">Category ID:</label>
      <input type="text" class="form-control" name="category_id" placeholder="Enter category id" value="<?php if($category_id){ echo $category_id;} ?>">
    </div>
    <div class="form-group">
      <label for="pwd">Listing Type:</label>
      <select name="listing_type[]" class="form-control" multiple>
	      <option value="Auction">Auction</option>
		  <option value="AuctionWithBIN">AuctionWithBIN</option>
		  <option value="StoreInventory">StoreInventory</option>
		  <option value="FixedPrice">FixedPrice</option>
	  </select>
    </div>
    <button type="submit" class="btn btn-default">Submit</button>
  </form>
  
    
	
	<?php
	    if(!empty($items)){
		    echo '<br><h2>Items List :</h2><p class="totalEntries"> Total Entries : '. $totalEntries .'</p><div class="item-list col-md-12">';
			foreach($items as $item){
			echo '<div class="item-1 col-md-12">
				<aside class="img-box col-md-3">
					<img src="'. $item['galleryURL'][0] .'" alt="" />
				</aside>
				<div class="content-box col-md-9"> 
					<a href="#" class="link-title"> '. $item['title'][0] .' </a>
					<p class="price">$'. $item['sellingStatus'][0]['currentPrice'][0]['__value__'] .'</p>
					<p class="form-to"> From: '. $item['location'][0] .' </p>
				</div>
			</div>';
			}
			echo '</div>';
		}
	?>
		
  

<style>
.item-list{ border:1px solid #ccc; margin-top: 2%; box-shadow: 0px 0px 5px 0px #ccc;}
.img-box img{width:200px; height:200px;}
.item-1.col-md-12 {border-bottom: 1px solid #ccc; margin: 20px 0; padding: 20px 0;}
.link-title{display: block;font-size: 20px;margin: 15px 0;}
.price{font-size:20px; color:#000;}
.totalEntries{font-size: 16px;}	
</style>

</div>

</body>
</html>

