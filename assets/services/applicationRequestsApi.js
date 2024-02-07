import axios from "axios";
import apiPath from "./apiPath";

function getAllRequests() {
    return axios.get(apiPath("candidature-requetes")).then((response) => {return response.data})
}
function approveRequest(id){
    return axios.put(apiPath("candidature-requete/" + id)).then((response) => {return response.data});
}
function removeRequest(id){
    return axios.delete(apiPath("candidature-requete/" + id)).then((response) => {return response.data});
}

export default {
    getAllRequests,
    approveRequest,
    removeRequest
}