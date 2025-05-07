/*
function sendAjaxRequest(action, data, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            callback(JSON.parse(xhr.responseText));
        }
    };
    const params = new URLSearchParams({ action, ...data }).toString();
    xhr.send(params);
}

function startRandomTweet() {
    const interval = document.getElementById('interval').value;
    const tweetCount = document.getElementById('tweet_count').value;

    sendAjaxRequest('generate_random_tweet', { interval, tweet_count: tweetCount }, function (response) {
        alert(response.message);
    });
}

function stopRandomTweet() {
    sendAjaxRequest('stop_random_tweet', {}, function (response) {
        alert(response.message);
    });
}

*/
function closeModal() {
    document.getElementById('tweetModal').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('tweetModal');
    const modalContainer = document.getElementById('modalContainer');
    if (modal && modalContainer) {
        modalContainer.appendChild(modal); // Move modal to the placeholder
        modal.style.display = 'block';
    }
});