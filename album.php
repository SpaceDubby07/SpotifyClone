<?php 
//Include the header.php file
include("includes/header.php");

if(isset($_GET['id'])) {
	$albumId = $_GET['id'];
}
else {
	header("Location: index.php");
}

$album = new Album($con, $albumId);
$artist = $album->getArtist();

?>

<div class="entityInfo">
	
	<div class="leftSection">
		<img src="<?php echo $album->getArtworkPath(); ?>">
	</div>

	<div class="rightSection">
		<h2><?php echo $album->getTitle(); ?></h2>
		<p>By <?php echo $artist->getName(); ?></p>
		<p><?php echo $album->getNumberOfSongs(); ?> songs</p>
	</div>

</div>

<div class="tracklistContainer">
	
	<ul class="tracklist">
		<?php 
			$songIdArray = $album->getSongIds();

			$i = 1;

			foreach ($songIdArray as $songId) {
					
				$albumSong = new Song($con, $songId);
				$albumArtist = $albumSong->getArtist();

				/*Multi-line explanation of whats happening below
				Div = trackCount
					the play.white.png image is the play button, show and hide on hover
					the trackNumber class displays the track number in order using the variable $i, which we defined above.
					After the list below we count the $i variable + 1 = 2 then 3 then 4, so on, as long as there is some value to be counted.
				Div = trackInfo
					We are displaying the track and artist name using the 2 variables we defined above $albumSong and $albumArtist.
					$albumSong calls the Song class (Song.php), and uses the function getTitle() to get the title of the song, display it.
					$albumArtist calls the variable $albumSong that we made to reach the Song class, and gets the getArtist() function,
						this allows us to use the getNamr() function from Artist.php class, then display the name. We reference it from the above code (line 11-14 where $artist calls the Album class to get the artist name.)
				Div = trackOptions
					this just displays the option png, show and hide it on hover
				Div = trackDuration
					call $albumSong getDuration() function, which displays the song duration.
				*/	



				echo "<li class='tracklistRow'>
						<div class='trackCount'>
							<img class='play' src='assets/images/icons/play-white.png'> 
							<span class='trackNumber'>$i</span>
						</div>

						<div class='trackInfo'>
							<span class='trackName'>" . $albumSong->getTitle() . "</span>
							<span class='aristName'>" . $albumArtist->getName() . "</span>
						</div>

						<div class='trackOptions'>
							<img class='optionsButton' src='assets/images/icons/more.png'>
						</div>

						<div class='trackDuration'>
							<span class='duration'>" . $albumSong->getDuration() . "</span>
						</div>
					</li>";

					$i = $i + 1; //or $i++

			}
		?>
	</ul>	

</div>

<?php
//Include the footer.php file
include("includes/footer.php"); 
?>