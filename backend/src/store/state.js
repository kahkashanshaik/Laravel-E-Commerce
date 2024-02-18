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
    orders: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null
    },
    users: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null
    },
    customers: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null
    },
    countries: [],
    dateOptions: [
        {key: '1d', text: 'Last Day'},
        {key: '1k', text: 'Last Week'},
        {key: '2k', text: 'Last 2 Weeks'},
        {key: '1m', text: 'Last Month'},
        {key: '3m', text: 'Last 3 Months'},
        {key: '6m', text: 'Last 6 Months'},
        {key: 'all', text: 'All Time'},
    ]
}

