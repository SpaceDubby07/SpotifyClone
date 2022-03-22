<?php 
//Include the header.php file
include("includes/header.php"); 
?>

<h1 class="pageHeadingBig">You Might Also Like</h1>

<div class="gridViewContainer">
	

	<?php 
	//output the albums
		$albumQuery = mysqli_query($con, "SELECT * FROM albums");

		//$albumQuery executes in the while statement looping over all queries from album table in db
		while($row = mysqli_fetch_array($albumQuery)) {
			//an example of how to output html within a php tag
			


			echo "<div class='gridViewItem'>
					<a href='album.php?id=" . $row['id']  ."''>
						<img src='" . $row['artworkPath'] .  "'>

						<div class='gridViewInfo'>"

							. $row['title'] .

						"</div>
					</a>	
				</div>";



		}
	?>

</div>

<?php
//Include the footer.php file
include("includes/footer.php"); 
?>