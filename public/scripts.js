document.getElementById('postForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const content = document.getElementById('postContent').value;
    if (!content) return;

    const response = await fetch('../src/post.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ content })
    });

    const result = await response.json();
    if (result.success) {
        displayPost(result.post);
        document.getElementById('postContent').value = '';
    }
});

async function loadFeed() {
    const response = await fetch('../src/feed.php');
    const posts = await response.json();
    posts.forEach(displayPost);
}

function displayPost(post) {
    const feed = document.getElementById('feed');
    const postElement = document.createElement('div');
    postElement.className = 'post';
    postElement.innerHTML = post.content.replace(/(http:\/\/[^\s]+)/g, '<a href="$1" target="_blank">$1</a>');
    feed.prepend(postElement);
}

loadFeed();
