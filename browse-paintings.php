<?php

include 'include/config.php';
include 'functions.php';
$conn = new mysqli($dbServer,$user, $pass, $db) or die("Unable to connect");


//your code for connecting to database, etc. goese here
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Assignment 1 - Page 1</title>

        <link href="css/reset.css" rel="stylesheet">
        <link href="css/styles.css" rel="stylesheet">
        <a href = "https://hopper.wlu.ca/~fanx1620/a4_fanx1620/browse-paintings.php">Link to Hopper</a>



    </head>
    <body>

        <main style="overflow:auto;">

            <section class="leftsection" style="width=600px;  margin-right:100px;">
                <form class="ui form" method="get" action="browse-paintings.php">
                    <h3>Filters</h3>

                    <div >
                        <label style=" padding-right:22px;">Artist</label>
                        <select name="artist">
                            <option value='0'>Select Artist</option>  
                            <?php  
                            // retrieve the names of the artist from database and use
			    // them as the values for <option> elements
                            $sql = "SELECT * FROM Artists;";
                            $result = mysqli_query($conn,$sql);
                            $resultCheck = mysqli_num_rows($result);
                            if ($resultCheck > 0){
                              while ($row = mysqli_fetch_assoc($result)) {
                                $var = utf8_encode($row['LastName']);
                                echo "<option val = '$var'>$var</option>";
                              }
                            }
                            ?>
                        </select>
                    </div>  
                    <div >
                        <label>Museum</label>
                        <select  name="museum">
                            <option value='0'>Select Museum</option>  
                            <?php  
                            // retrieve the list of galleries name  from database and use
			    // them as the values for <option> elements
                            $sql = "SELECT * FROM Galleries;";
                            $result = mysqli_query($conn,$sql);
                            $resultCheck = mysqli_num_rows($result);
                            if ($resultCheck > 0){
                              while ($row = mysqli_fetch_assoc($result)) {
                                $var = utf8_encode($row['GalleryName']);
                                echo "<option val = '$var'>$var</option>";
                              }
                            }
                            ?>
                        </select>
                    </div>   
                    <div >
                        <label style="padding-right:14px;">Shape</label>
                        <select  name="shape">
                            <option value='0'>Select Shape</option>  

                            <?php  
                            $sql = "SELECT * FROM Shapes;";
                            $result = mysqli_query($conn,$sql);


                            $resultCheck = mysqli_num_rows($result);

                            if ($resultCheck > 0){
                              while ($row = mysqli_fetch_assoc($result)) {
                                $var = utf8_encode($row['ShapeName']);
                                echo "<option value='$var'>$var</option>";
                              }
                            }
                            // retrieve the different shapes from database and use
			    // them as the values for <option> elements
                            ?>


                        </select>
                    </div>   
                    <p> &nbsp; &nbsp;  &nbsp;   &nbsp; </p>
                    <button type="submit" id="buttons"> Filter  </button>    

                </form>    </section>


            <section class="rightsection" >
                <h1>Paintings</h1>
                <h3>All Paintings [Top 20]</h3>
                <ul id="paintingsList">

                    <?php  
                      if (!isset($artist) or !isset($museum) or !isset($shapes)) {
                      $sql = "SELECT * FROM Paintings inner join Artists on Paintings.ArtistID = Artists.ArtistID LIMIT 20;";
                  }
                  if ( isset( $_GET['submit'] ) ) {
                    $artist = $_GET['artist'];
                    $museum = $_GET['museum'];
                    $shapes = $_GET['shapes'];

                    // $sql = "SELECT * FROM Paintings inner join Galleries on Paintings.GalleryID = Galleries.GalleryID WHERE GalleryName = $museum LIMIT 20;";

                    if (empty($artist) and empty($museum) and empty($shapes)){
                      $sql = "SELECT * FROM Paintings join Artists on Paintings.ArtistID = Artists.ArtistID LIMIT 20;";
                    } elseif (empty($artist) and empty($museum)) {
                      // Do a full outer Join
                      $sql = "SELECT * FROM Paintings join Shapes on Paintings.ShapeID = Shapes.ShapeID join Artists on Paintings.ArtistID = Artists.ArtistID WHERE ShapeName = '$shapes' LIMIT 20;";
                    } elseif (empty($artist) and empty($shapes)) {

                      $sql = "SELECT * FROM Paintings join Galleries on Paintings.GalleryID = Galleries.GalleryID join Artists on Paintings.ArtistID = Artists.ArtistID WHERE GalleryName = '$museum' LIMIT 20;";
                    } elseif (empty($museum) and empty($shapes)) {

                      $sql = "SELECT * FROM Paintings join Artists on Paintings.ArtistID = Artists.ArtistID WHERE LastName = '$artist' LIMIT 20;";

                    } elseif (empty($artist)){
                      $sql =  "SELECT * FROM Paintings join Shapes on Paintings.ShapeID = Shapes.ShapeID join Galleries on Paintings.GalleryID = Galleries.GalleryID WHERE ShapeName = '$shapes' AND GalleryName = '$museum' LIMIT 20;";

                    } elseif (empty($museum)) {
                      $sql =  "SELECT * FROM Paintings join Artists on Paintings.ArtistID = Artists.ArtistID join Shapes on Paintings.ShapeID = Shapes.ShapeID WHERE LastName = '$artist' AND ShapeName = '$shapes' LIMIT 20;";

                    } elseif (empty($shapes)) {
                      $sql = "SELECT * FROM Paintings join Artists on Paintings.ArtistID = Artists.ArtistID join Galleries on Paintings.GalleryID = Galleries.GalleryID WHERE LastName = '$artist' AND GalleryName = '$museum' LIMIT 20;";
                    }

                  }


                  $result = mysqli_query($conn,$sql);


                  $resultCheck = mysqli_num_rows($result);

                  if ($resultCheck > 0){
                    while ($row = mysqli_fetch_assoc($result)) {
                      $PaintingId = utf8_encode($row['PaintingID']);
                      $imageName = utf8_encode($row['ImageFileName']);
                      $title = utf8_encode($row['Title']);
                      $fname = utf8_encode($row['FirstName']);
                      $lname = utf8_encode($row['LastName']);
                      $details = utf8_encode($row['Details']);
                      $msrp = number_format(utf8_encode($row['MSRP']));

		    	// you need to have a while loop here that goes over the result of a query
			//depending on the question you are working on
			
		    ?>

                    <li class="item">

                        <div class="figure">

                            <a href="single-painting.php?id=<?php  /* you need the 'PaintingID' here */ ?>">
                                <img src="images/art/works/square-medium/<?php /* you need the 'ImageFileName' here */ ?>.jpg">
                            </a>
                        </div>
                        <div class="itemright">
                            <a href="single-painting.php?id=<?php /* you need the 'PaintingID' here */ ?>">
                                <?php /* Title  */ ?></a>

                            <div><span><?php /* FirstName and LastName */ ?></span></div>        


                            <div class="description">
                                <p><?php /* Excerpt */ ?></p>
                            </div>

                            <div class="meta">     
                                <strong><?php /*  MSRP */ ?></strong>        
                            </div>        

                            <div class="extra" >
                                <a class="favorites" href="cart.php?id=<?php /* PaintingID */ ?>">Add to Shopping Cart</a>
                                <span> &nbsp; &nbsp; &nbsp;    </span>
                                <a  class="favorites"   href="favorites.php?id=<?php /* PaintingID  */ ?>">	Add to Wish List</i>
                                </a>         
                                <p>&nbsp;</p>
                            </div>       

                        </div>      
                    </li>

                    <?php
		    //} 
		    ?>

                </ul>
            </section>

        </main>
    </body>
</html>
