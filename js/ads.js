const ads = [
    "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
    "Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
    "Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris."
];

function displayRandomAd() {
    const adIndex = Math.floor(Math.random() * ads.length);
    return ads[adIndex];
}

function handleAdDisplay(action) {
    if (action === 'new' || action === 'delete') {
        const ad = displayRandomAd();
        document.getElementById('ad-container').textContent = ad;
    }
}

// Usage
handleAdDisplay('new');  // Call when a new iframe is created
handleAdDisplay('delete');  // Call when an iframe is deleted
