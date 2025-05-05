jQuery(document).ready(function ($) {

    // Format postcode only if there's no space
    $('input[name="postcode"]').on('input', function () {
        let value = $(this).val().toUpperCase().trim();

        // Only add space if not present before last 3 chars
        if (!/ \w{3}$/.test(value) && value.length > 3 && !value.includes(' ')) {
            let part1 = value.slice(0, -3);
            let part2 = value.slice(-3);
            $(this).val(part1 + ' ' + part2);
        } else {
            $(this).val(value);
        }
    });

    $('#trs-form').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);
        let registration = form.find('input[name="registration"]').val().trim();
        let postcode = form.find('input[name="postcode"]').val().trim();
        let resultsBox = $('#trs-results');

        resultsBox.html('');

        // Simple validation
        if (registration === '' || postcode === '') {
            resultsBox.html('<p class="text-red-600 font-semibold">⚠️ Please fill in both Registration and Postcode.</p>');
            return;
        }

        resultsBox.html('<p class="text-gray-500">🔍 Searching for matching tires...</p>');

        $.ajax({
            url: trs_ajax.url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'trs_fetch_products',
                registration: registration,
                postcode: postcode
            },
            success: function (response) {
                if (response.success) {
                    resultsBox.html(response.data);
                } else {
                    resultsBox.html(`
                        <div class="w-full bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded mt-4">
                            <strong>⚠️ No Tire Found for Your Car</strong><br>
                            Please try a manual tire search from our <a href="/shop" class="underline font-semibold">shop page</a>.
                        </div>
                    `);
                }
            },
            error: function (xhr, status, error) {
                resultsBox.html('<p class="text-red-600 font-semibold">❌ An error occurred. Please try again.</p>');
                console.error('AJAX Error:', error);
            }
        });
    });

});
