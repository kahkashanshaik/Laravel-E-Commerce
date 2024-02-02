export default {
    user: {
        token: sessionStorage.getItem('TOKEN'),
        data: {}
    },
    toast: {
        show: false,
        message: 'testing token value adafhakdhfakdj',
        delay: 5000
    },
    products: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null
    },
}

