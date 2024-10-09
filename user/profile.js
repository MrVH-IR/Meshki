// Function to send selected genre to server and display music player
function sendGenreAndPlayMusic() {
  var selectedGenre = document.getElementById('genreSelect').value;
  
  if (!selectedGenre) {
    alert('Please select a genre');
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
    // Close the form after action
    //document.getElementById('profilePictureForm').style.display = 'none';
  })
  .catch(error => {
    console.error('Error:', error);
  });
}

// Create profile picture form
//var form = document.createElement('div');
//form.id = 'profilePictureForm';
//form.style.display = 'none'; // Initially hidden
// form.innerHTML = `
//   <button onclick="sendGenreAndPlayMusic()">Play Music</button>
//   <button onclick="document.getElementById('profilePictureForm').style.display='none'">X</button>
//   <button id="downloadButton" onclick="downloadMusic()">Download Music</button>
// `;
// document.getElementById('recommendedMusic').appendChild(form);

// Create music controls including the Play button
var musicControls = document.createElement('div');
musicControls.id = 'musicControls';
musicControls.innerHTML = `
  <button onclick="sendGenreAndPlayMusic()">Play Music</button>
`;

document.getElementById('recommendedMusic').appendChild(musicControls);

// Function to download music
function downloadMusic() {
  var selectedGenre = document.getElementById('genreSelect').value;
  // Implement download logic here
  alert('Downloading music for genre: ' + selectedGenre);
}

// Add event listener to the profile picture to toggle the form
document.querySelector('img').onclick = toggleProfilePictureForm;

// Add player display area
var playerArea = document.createElement('div');
playerArea.id = 'music_player';
document.getElementById('recommendedMusic').appendChild(playerArea);
