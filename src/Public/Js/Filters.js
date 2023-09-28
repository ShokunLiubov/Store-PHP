const form = document.querySelector(".filters");

form.addEventListener("submit", function (event) {
    event.preventDefault();

    const currentUrl = new URL(window.location.href);
    const formData = new FormData(form)
    const urlSearchParams = new URLSearchParams();

    if (currentUrl.searchParams.has('search')) {
        urlSearchParams.append('search', currentUrl.searchParams.get('search'));
    }

    for (const [key, value] of formData) {
        if (value !== "") {
            urlSearchParams.append(key, value)
        }
    }

    const newUrl = `${window.location.pathname}?${urlSearchParams.toString()}`
    window.location.href = newUrl;
});
