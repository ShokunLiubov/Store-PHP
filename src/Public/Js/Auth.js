const logout = document.querySelector(".logout")
console.log('Blue')
if (logout) {
    logout.addEventListener('click', () => {
        console.log('click')
        fetch('http://localhost/make-up/auth/logout', {
            method: 'GET',
        })
            .then(response => {
                console.log(response)
                if (response.ok) {
                    console.log(window.location, 'ok')
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
