
document.querySelector("#followButton").addEventListener('click', function(event) {
        if (this.dataset.isfollowing === '1') {
            let formData = new FormData();
            formData.append('follower',  this.dataset.follower);
            formData.append('following', this.dataset.following);
            fetch('ajax/unfollowUser.php', {
                method: 'POST',
                body: formData
            })
                // .then(response => response.json())
                .then(response => response.json())
                .then(result => {
                    console.log('Success:', result);
                    $("#followerCount").text(result.body['amount'] + " Followers")
                    this.innerHTML = "Follow";
                    this.dataset.isfollowing = '0';
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
        else if(this.dataset.isfollowing === '0') {

            let formData = new FormData();
            formData.append('follower',  this.dataset.follower);
            formData.append('following', this.dataset.following);
            fetch('ajax/followUser.php', {
                method: 'POST',
                body: formData
            })
                // .then(response => response.json())
                .then(response => response.json())
                .then(result => {
                    console.log('Success:', result);
                    $("#followerCount").text(result.body['amount'] + " Followers")
                    this.innerHTML = "Unfollow";
                    this.dataset.isfollowing = '1';
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
        event.preventDefault();
});