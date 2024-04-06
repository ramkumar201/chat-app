import axios from "axios";
export default ({ requiresAuth = false } = {}) => {
    const options = {
        "baseURL": '/',
        "headers": {
            "Authorization": "",
            "Content-Type": "application/json",
        }
    };
    if (requiresAuth) {
        options.headers.Authorization = 'Bearer ' + localStorage.getItem('token');
    }
    const instance = axios.create(options);
    instance.interceptors.request.use((req) => {
        req.headers['request-startTime'] = new Date().getTime();
        return req
    })
    instance.interceptors.response.use(response => {
        const currentTime = new Date().getTime()
        const startTime = response.config.headers['request-startTime']
        response.headers['request-duration'] = currentTime - startTime
        return response;
    }, error => {
        const originalRequest = error.config;
        console.log(originalRequest);
        if (axios.isCancel(error)) {
            console.log('Cancelled request -> ', error);
            return Promise.reject(error);
        } else {
            switch (error.response.status) {
                case 400:
                    console.log(error.response.config.url + " : This api getting 400 error");
                    break;
                case 401:
                    console.log(error.response.config.url + " : This api getting 401 error");
                    break;
                case 404:
                    console.log(error.response.config.url + " : This api getting 404 error");
                    break;
                case 500:
                    console.log(error.response.config.url + " : This api getting 500 error");
                    break;
                case 502:
                    console.log(error.response.config.url + " : This api getting 502 error");
                    break;
                default:
                    console.log(error.response.config.url + " : This api getting some other error");
                    break;
            }
        }
        return Promise.reject(error);
    });
    return instance;
};
