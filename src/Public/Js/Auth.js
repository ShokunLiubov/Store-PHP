const logout = document.querySelector(".logout")

if (logout) {
    logout.addEventListener('click', () => {
        fetch('http://localhost/make-up/auth/logout', {
            method: 'GET',
        })
            .then(response => {
                if (response.ok) {
                    window.location.reload(true)
                } else {
                    console.error('Failed to logout')
                }
            })
            .catch(error => {
                console.error('An error occurred:', error)
            })
    })
}
