function performSearch() {
    const searchInput = document.getElementById("searchInput").value;

    // Show an error message regardless of the input
    const errorMessage = document.getElementById("error-message");
    errorMessage.style.display = "block";
    errorMessage.textContent = `No results found for "${searchInput}". Please try again.`;
}
