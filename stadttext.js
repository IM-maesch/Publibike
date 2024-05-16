document.addEventListener('DOMContentLoaded', function() {
    // Function to toggle visibility of text sections and update background color
    function toggleTextAndColor(cityId, textId, color) {
        // Hide all text sections
        document.querySelectorAll('.infobox > div').forEach(function(element) {
            element.style.display = 'none';
        });
        // Show the text section corresponding to the clicked city
        document.getElementById(textId).style.display = 'block';
        // Update background color of .infobox container
        document.querySelector('.infobox').style.backgroundColor = color;
    }

    // Event listeners for each city menu item
    document.getElementById('menuchur').addEventListener('click', function() {
        toggleTextAndColor('chur', 'churtext', '#CED766');
    });

    document.getElementById('menubern').addEventListener('click', function() {
        toggleTextAndColor('bern', 'berntext', '#946AEE');
    });

    document.getElementById('menufribourg').addEventListener('click', function() {
        toggleTextAndColor('fribourg', 'fribourgtext', '#73D9EF');
    });

    document.getElementById('menuzurich').addEventListener('click', function() {
        toggleTextAndColor('zurich', 'zurichtext', '#66D793');
    });
});
