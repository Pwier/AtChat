function fetchMessages() {
    var friendId = new URLSearchParams(window.location.search).get("friend_id");
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "fetch_messages.php?friend_id=" + friendId, true);
    xhr.onload = function () {
        if (xhr.status == 200) {
            document.getElementById("chat-box").innerHTML = xhr.responseText;
            scrollToBottom(); // En yeni mesajı göstermek için
        }
    };
    xhr.send();
}

// Sayfa yüklendiğinde ve her 2 saniyede bir yeni mesajları getir
document.addEventListener("DOMContentLoaded", function () {
    fetchMessages();
    setInterval(fetchMessages, 2000);
});

// Mesajları her zaman en altta göstermek için
function scrollToBottom() {
    var chatBox = document.getElementById("chat-box");
    chatBox.scrollTop = chatBox.scrollHeight;
}