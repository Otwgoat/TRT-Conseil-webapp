import axios from "axios";
import apiPath from "./apiPath";

function createUser(data) {
    return axios.post(apiPath("inscription"), data );
}

function updateUser(data, id) {
    return axios.put(apiPath("utilisateurs/" + id), data );
}
function updatePassword (data) {
    return axios.put(apiPath("modifier-mot-de-passe"), data);

}
function getUser(){
    return axios
    .get(apiPath("utilisateur-connecte"))
    .then((response) => {
         return response.data;
})};

function uploadCurriculum (data){
    return axios.put(apiPath("telechargement-cv"), data).then((response) => console.log(response));
}

function getCurriculum (path) {
    
        return axios.get(path);
    };


export default {
    createUser,
    getUser,
    updateUser,
    uploadCurriculum,
    getCurriculum,
    updatePassword
}