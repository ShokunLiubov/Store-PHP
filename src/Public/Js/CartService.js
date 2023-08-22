class CartService {

    static baseURL = 'http://localhost/make-up/cart';

    static async fetchCart(endpoint, method) {
        try {
            const response = await fetch(`${this.baseURL}/${endpoint}`, {
                method,
            });

            const data = await response.text();
            this.displayCartContent(data);
            cartAttachEventHandlers();

        } catch (error) {
            console.error('An error occurred:', error);
        }
    }

    static displayCartContent(data) {
        document.querySelector('.cart-content').innerHTML = data;
    }

    static async hideCart() {
        const response = await fetch(`${this.baseURL}/hide`, {
            method: 'GET',
        });

        await response.text();
        this.displayCartContent('');
    }

    static async showCart(productId) {
        await this.fetchCart('show', 'GET');
    }

    static async addToCart(productId) {
        await this.fetchCart(`${productId}`, 'GET');
    }

    static async remove(productId) {
        await this.fetchCart(`remove/${productId}`, 'GET');
    }

    static async decrement(productId) {
        await this.fetchCart(`decrement/${productId}`, 'GET');

    }

    static async increment(productId) {
        await this.fetchCart(`increment/${productId}`, 'GET');
    }
}
