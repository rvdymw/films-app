<h2>Oceń film</h2>
<form id="rating-form">
    <select name="rating" id="rating">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
    </select>
    <button type="submit">Oceń</button>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ratingForm = document.getElementById('rating-form');
        ratingForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(ratingForm);
            const rating = formData.get('rating');

            fetch('{{ route("films.rate", $film->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ rating }),
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
</script>
