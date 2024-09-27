// Function to send selected genre to server and display music player
function sendGenreAndPlayMusic() {
  var selectedGenre = document.getElementById('genreSelect').value;
  
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

// Add button to the page
var button = document.createElement('button');
button.innerHTML = 'Play Music';
button.onclick = sendGenreAndPlayMusic;
document.getElementById('recommendedMusic').appendChild(button);

// Add player display area
var playerArea = document.createElement('div');
playerArea.id = 'music_player';
document.getElementById('recommendedMusic').appendChild(playerArea);


