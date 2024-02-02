import axiosClient from '../axios';

export async function getCurrentUser({ commit }, data) {
    return axiosClient.get('/user', data)
        .then(({ data }) => {
            commit('setUser', data)
            return data;
        }).catch((err) => {
            throw err;
        });
}

export async function login({ commit }, data) {
    return await axiosClient.post('/login', data)
        .then(({ data }) => {
            commit('setUser', data.user);
            commit('setToken', data.token);
            return data;
        }).catch((err) => {
            throw err;
        })
}

export async function logout({ commit }) {
    return await axiosClient.post('/logout')
        .then((response) => {
            commit('setToken', null)
            return response;
        }).catch((err) => {
            throw err;
        })
}

export function getProducts({ commit, state }, { url = null, search = '', per_page, sort_field, sort_direction } = {}) {
    commit('setProducts', [true])
    url = url || '/products'
    const params = {
        per_page: state.products.limit,
    }
    return axiosClient.get(url, {
        params: {
            ...params,
            search, per_page, sort_field, sort_direction
        }
    })
        .then((response) => {
            commit('setProducts', [false, response.data])
        })
        .catch(() => {
            commit('setProducts', [false])
        })
}

export function deleteProduct({ commit }, id) {
    console.log(id);
    return axiosClient.delete(`/products/${id}`);
}


export function createProduct({ commit }, product) {
    if (product.image instanceof File) {
        const form = new FormData();
        form.append('title', product.title);
        form.append('image', product.image);
        form.append('description', product.description || '');
        form.append('published', product.published ? 1 : 0);
        form.append('price', product.price);
        product = form;
    }
    return axiosClient.post('/products', product)
}

export function updateProduct({ commit }, product) {
    const id = product.id;
    if (product.image instanceof File) {
        const form = new FormData();
        form.append('id', product.id);
        form.append('title', product.title);
        form.append('image', product.image);
        form.append('description', product.description || '');
        form.append('published', product.published ? 1 : 0);
        form.append('price', product.price);
        form.append('_method', 'PUT');
        product = form;
    } else {
        product._method = 'PUT'
    }
    return axiosClient.post(`/products/${id}`, product);
}

export function getProduct({ commit }, id) {
    return axiosClient.get(`/products/${id}`)
}