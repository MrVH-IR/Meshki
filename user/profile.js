console.log('فایل profile.js بارگذاری شد');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM کاملاً بارگذاری شده است');

    const menuToggle = document.querySelector('.menu-toggle');
    const menu = document.querySelector('.menu');

    console.log('menuToggle:', menuToggle);
    console.log('menu:', menu);

    if (menuToggle && menu) {
        menuToggle.addEventListener('click', function(event) {
            console.log('منو کلیک شد');
            event.preventDefault();
            this.classList.toggle('active');
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
            console.log('وضعیت نمایش منو:', menu.style.display);
        });
    } else {
        console.error('عناصر منو پیدا نشدند');
    }

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

    // Make sendGenreAndPlayMusic globally accessible
    window.sendGenreAndPlayMusic = sendGenreAndPlayMusic;
});
function sendCode() {
  // ارسال درخواست به profile.php با روش POST
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "profile.php", true); 
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function() {
      if (xhr.readyState === 4 && xhr.status === 200) {
          alert("Verification code sent to your email.");
      }
  };
  xhr.send("send_code=true"); // ارسال داده برای مشخص کردن درخواست ارسال کد
}