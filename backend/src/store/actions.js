import axiosClient from '../axios';
import state from './state';

export async function getCurrentUser({ commit }, data) {
    return axiosClient.get('/user', data)
        .then(({ data }) => {
            commit('setUser', data)
            return data;
        }).catch((err) => {
            throw err;
        });
}

// Order Statuses
export function getStatuses({commit}) {
    return axiosClient.get('/orders/statuses');
}

// Auth
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

// products
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

export function getProduct({ commit }, id) {
    return axiosClient.get(`/products/${id}`)
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
export function deleteProduct({ commit }, id) {
    return axiosClient.delete(`/products/${id}`);
}

// Orders
export function getOrders({commit, state}, { url = null, search = '', per_page, sort_field, sort_direction} = {}) {
    commit('setOrders', [true]);
    url = url || '/orders';
    const params = {
        per_page: state.orders.limit
    }
    return axiosClient.get(url, {
        params: {
            ...params,
            search, per_page, sort_field, sort_direction 
        }
    }).then((response) => {
        commit('setOrders', [false, response.data]);
    }).catch(() => {
        commit('setOrders', [false]);
    })
}

export function getOrder({commit}, id) {
    return axiosClient.get(`/orders/${id}`);
}

// Usres
export function getUsers({commit}, {url = null, search = '', per_page, sort_field, sort_direction } = {}) {
    commit('setUsers', [true] );
    url = url || '/users';
    const params = {
        per_page: state.orders.limit
    }
    return axiosClient.get(url, {
        params: {
            ...params, search, per_page, sort_field, sort_direction
        }
    }).then((response) => {
        commit('setUsers', [false, response.data]);
    }).catch(() => {
        commit('setUsers', [false]);
    })
}

export function createUser({commit}, user) {
    return axiosClient.post('/users', user)
  }
  
  export function updateUser({commit}, user) {
    return axiosClient.put(`/users/${user.id}`, user)
  }
  
// Customres
export function getCustomers({commit, store}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
    commit('setCustomers', [true]);
    url = url || '/customers';
    const params = {
        per_page: state.customers.limit
    }
    return axiosClient.get(url , {
        params: {
            ...params, search, sort_field, sort_direction
        }
    }).then((response)=>{
        commit('setCustomers', [true, response.data]);
    }).catch(() => {
        commit('setCustomers', [false]);
    })
}

export function getCustomer({commit}, id) {
    return axiosClient.get(`/customers/${id}`);
}

export function deleteCustomer({commit}, customer) {
    return axiosClient.delete(`/customers/${customer.id}`);
}

export function getCountries({commit}) {
    return axiosClient.get('/countries')
        .then(({data}) => {
            commit('setCountries', data);
        })
}

export function createCustomer({commit}, customer) {
    return axiosClient.post('/customers', customer)
  }
  
  export function updateCustomer({commit}, customer) {
    return axiosClient.put(`/customers/${customer.id}`, customer)
  }