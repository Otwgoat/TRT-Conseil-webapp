import axios from "axios";
import {jwtDecode} from "jwt-decode";
import apiPath from "./apiPath";
// set the default Authorization header for axios
function authenticate(credentials) {
    console.log(credentials);
    return axios
        .post(apiPath("login_check"), credentials)
        .then(console.log(apiPath("login_check")))
        .then((response) => {
            return response.data.token;
            
        })
        .then(token => {
            // stockage du token généré par l'API dans le localStorage.
            window.localStorage.setItem("authToken", token);
            // On prévient axios qu'on a maintenant un header par défaut sur toutes nos futures requêtes HTTP.
            setAxiosToken(token);

            return true
        });
}


function logout () {
    window.localStorage.removeItem("authToken");
    delete axios.defaults.headers["Authorization"];
}


function setAxiosToken (token) {
    return axios.defaults.headers["Authorization"] = "Bearer " + token;
};

    
function setup () {
    //  Voir si on a un token
    const token = window.localStorage.getItem("authToken");
    // ' Si le token est encore valide
    if(token){
        const jwtData = jwtDecode(token);
        if((jwtData.exp * 1000) > new Date().getTime()){
           setAxiosToken(token);
        } else {
            logout();
        }   
    } else {
        logout();
    }
};    

function isAuthenticated() {
    const token = window.localStorage.getItem("authToken");
    if(token){
        const jwtData = jwtDecode(token);
        if((jwtData.exp * 1000) > new Date().getTime()){
           return true;
        } else {
        return false;
        }   
    }
    return false;

}    
function getUserType(){
    const token = window.localStorage.getItem("authToken");
    if(token){
        const jwtData = jwtDecode(token);
        
        if((jwtData.roles[0]) === "ROLE_ADMIN"){
            return 'admin';
        } else if ((jwtData.roles[0]) === "ROLE_CONSULTANT"){
            return 'consultant';
        } else if ((jwtData.roles[0]) === "ROLE_CANDIDATE") {
            return 'candidate';
        } else if ((jwtData.roles[0]) === "ROLE_RECRUITER") {
            return 'recruiter';
        }
    }
    return false;

}


 

export default {
    authenticate,
    logout,
    setup,
    isAuthenticated,
    getUserType,
    
};