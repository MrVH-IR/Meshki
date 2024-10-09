// Function to send selected genre to server and display music player
function sendGenreAndPlayMusic() {
  var selectedGenre = document.getElementById('genreSelect').value;

  if (!selectedGenre) {
    alert("Please select a genre.");
    return;
  }

  // Send request to server
  fetch('profile.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'genre=' + encodeURIComponent(selectedGenre)
  })
  .then(response => response.text())
  .then(data => {
    // Display music player
    document.getElementById('music_player').innerHTML = data;
  })
  .catch(error => {
    console.error('Error:', error);
  });
}

// Create music controls including the Play button
var musicControls = document.createElement('div');
musicControls.id = 'musicControls';
musicControls.innerHTML = `
  <button onclick="sendGenreAndPlayMusic()">Play Music</button>
`;

document.getElementById('recommendedMusic').appendChild(musicControls);
