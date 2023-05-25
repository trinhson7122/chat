const base_url = "/api";
const url = "";
//auth
const login = `${base_url}/auth/login`;
const logout = `${base_url}/auth/logout`;
const register = `${base_url}/auth/register`;
//
const csrf_cookie = `${url}/sanctum/csrf-cookie`;
const get_user = `${base_url}/user`;
const get_user_online = `${base_url}/user/user-online`;
const put_user = `${base_url}/user/` // + id;
const fetch_user = `${base_url}/user/all`;
const store_list_message = `${base_url}/list-message`;
const get_list_message_with_me = `${base_url}/list-message-with-me`;
const get_message = `${base_url}/fetchMessages`;
const post_message = `${base_url}/sendMessage`;
const remove_message = `${base_url}/removeMessage`;
const send_file = `${base_url}/sendMessageFile`;
const upload_avatar = `${base_url}/upload_avatar`;

export { 
    login, 
    logout, 
    register, 
    csrf_cookie,
    get_user,
    get_user_online,
    store_list_message,
    get_list_message_with_me,
    get_message,
    post_message,
    remove_message,
    fetch_user,
    put_user,
    send_file,
    upload_avatar,
};