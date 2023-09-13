
    const form = document.querySelector(".filtersStore");

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        const formData = new FormData(form)
        const urlSearchParams = new URLSearchParams();

        for (const [key, value] of formData) {
            if (value !== "") {
                urlSearchParams.append(key, value)
            }
        }

        const newUrl = `${window.location.pathname}?${urlSearchParams.toString()}`
        window.location.href = newUrl;
    })
