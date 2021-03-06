<?php
$songQuery = mysqli_query($con, "SELECT id FROM songs ORDER BY RAND() LIMIT 10");
//create an empty array, the use the while loop below to push the id's from the songs table onto it. 
$resultArray = array();

//loop the songs
while($row = mysqli_fetch_array($songQuery)) {
	array_push($resultArray, $row['id']);
}

//Use Json to take the array from php to javascript or vice-versa.
$jsonArray = json_encode($resultArray); //convert the $resultArray into json, by encoding it.
?>

<script>
	$(document).ready(function() {
		currentPlaylist = <?php echo $jsonArray; ?>;
		audioElement = new Audio();
		setTrack(currentPlaylist[0], currentPlaylist, false);
		updateVolumeProgressBar(audioElement.audio);

		$("#nowPlayingBarContainer").on("mousedown touchstart mousemove touchmove", function(e) {
			e.preventDefault();
		});

		$(".playbackBar .progressBar").mousedown(function() {
			mouseDown = true;
		});

		$(".playbackBar .progressBar").mousemove(function(e) { //e for event
			if(mouseDown == true) {
				//set time of song, depending on position of mouse
				timeFromOffset(e, this);
			}
		});

		$(".playbackBar .progressBar").mouseup(function(e) { //e for event
				timeFromOffset(e, this);
		});


		$(".volumeBar .progressBar").mousedown(function() {
			mouseDown = true;
		});

		$(".volumeBar .progressBar").mousemove(function(e) { //e for event
			if(mouseDown == true) {
				var percentage = e.offsetX / $(this).width();

				if(percentage >= 0 && percentage <= 1) {
				audioElement.audio.volume = percentage;					
				}

			}
		});

		$(".volumeBar .progressBar").mouseup(function(e) { //e for event
			var percentage = e.offsetX / $(this).width();

				if(percentage >= 0 && percentage <= 1) {
				audioElement.audio.volume = percentage;					
				}
		});

		$(document).mouseup(function() {
			mouseDown = false;
		});
	});

	//get time from offset of mouse
	function timeFromOffset(mouse, progressBar) {
		var percentage = mouse.offsetX / $(progressBar).width() * 100; //how far along on X is on "this" element which is .playbackBar, .progressBar
		var seconds = audioElement.audio.duration * (percentage / 100);
		audioElement.setTime(seconds);
	}


	function setTrack(trackId, newPlaylist, play) {

		//ajax call
		$.post("includes/handlers/ajax/getSongJson.php", { songId: trackId }, function(data) {

			var track = JSON.parse(data);

			$(".trackName span").text(track.title);

			//Get the artist name
			$.post("includes/handlers/ajax/getArtistJson.php", { artistId: track.artist }, function(data) {
				var artist = JSON.parse(data);
				$(".artistName span").text(artist.name);
			});

			//get the album name
			$.post("includes/handlers/ajax/getAlbumJson.php", { albumId: track.album }, function(data) {
				var album = JSON.parse(data);
				$(".albumLink img").attr("src", album.artworkPath);
			});

			audioElement.setTrack(track);

			if (play == true) {
				playSong();
			}
		});

	}

	function playSong() {

		if(audioElement.audio.currentTime == 0) {
			$.post("includes/handlers/ajax/updatePlays.php", { songId: audioElement.currentlyPlaying.id });
		}

		$(".controlButton.play").hide();
		$(".controlButton.pause").show();
		audioElement.play();
	}

	function pauseSong() {
		$(".controlButton.play").show();
		$(".controlButton.pause").hide();
		audioElement.pause();
	}

</script>

<div id="nowPlayingBarContainer">
	<div id="nowPlayingBar">
		<div id="nowPlayingLeft">
			<div class="content">
				<span class="albumLink"><img src="assets/images/artwork/sweet.jpg" class="albumArtwork"></span>
				<div class="trackInfo">
					<span class="trackName">
						<span></span>
					</span>
					<span class="artistName">
						<span></span>
					</span>
				</div>
			</div>
		</div>
		<div id="nowPlayingCenter">
			<div class="content playerControls">
				<div class="buttons">
					<button class="controlButton shuffle" title="Shuffle button">
						<img src="assets/images/icons/shuffle.png" alt="Shuffle">
					</button>

					<button class="controlButton previous" title="Previous button">
						<img src="assets/images/icons/previous.png" alt="Previous">
					</button>

					<button class="controlButton play" title="Play button" onclick="playSong()">
						<img src="assets/images/icons/play.png" alt="Play">
					</button>

					<button class="controlButton pause" title="Pause button" style="display: none;" onclick="pauseSong()">
						<img src="assets/images/icons/pause.png" alt="Pause">
					</button>


					<button class="controlButton next" title="Next button">
						<img src="assets/images/icons/next.png" alt="Next">
					</button>

					<button class="controlButton repeat" title="Repeat button">
						<img src="assets/images/icons/repeat.png" alt="Repeat">
					</button>
				</div>
				<div class="playbackBar">
					<span class="progressTime current">0.00</span>
					<div class="progressBar">
						<div class="progressBarBg">
							<div class="progress"></div>
						</div>
					</div>
					<span class="progressTime remaining">0.00</span>

				</div>
			</div>
		</div>
		<div id="nowPlayingRight">
			<div class="volumeBar">
				
				<button class="controlButton volume" title="volumeButton">
					<img src="assets/images/icons/volume.png" alt="Volume">
				</button>

				<div class="progressBar">
						<div class="progressBarBg">
							<div class="progress"></div>
						</div>
					</div>				
			</div>
		</div>
	</div>
</div>