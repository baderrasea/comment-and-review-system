document.getElementById('reviewForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const rating = document.querySelector('input[name="rating"]:checked')?.value;
    const comment = document.getElementById('comment').value;
    
    if (!rating || !comment) {
        alert('Please provide a rating and a comment.');
        return;
    }
    
    document.getElementById('result').innerHTML = `
        <h3>Your Review</h3>
        <p><strong>Rating:</strong> ${rating} stars</p>
        <p><strong>Comment:</strong> ${comment}</p>
    `;
    
    document.getElementById('reviewForm').reset();
});

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.read-more').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            var commentPreview = this.previousElementSibling;
            var commentFull = commentPreview.querySelector('.comment-full');
            
            if (commentFull.style.display === 'none') {
                commentFull.style.display = 'block';
                this.textContent = 'Read Less';
            } else {
                commentFull.style.display = 'none';
                this.textContent = 'Read More';
            }
        });
    });
});

// document.getElementById('reviewForm').addEventListener('submit', function(event) {
//     const rating = document.querySelector('input[name="rating"]:checked')?.value;
//     const comment = document.getElementById('comment').value;

//     if (!rating || !comment) {
//         alert('Please provide a rating and a comment.');
//         event.preventDefault();
//     }
// });