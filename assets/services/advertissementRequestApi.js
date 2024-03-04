import axios from "axios";
import apiPath from "./apiPath";

function getAllRequests(){
    return axios.get(apiPath("requete-annonces")).then((response) => {return response.data});
};
function approveRequest(id){
    return axios.put(apiPath("requete-annonce/" + id)).then((response) => {return response.data});
}
function removeRequest(id){
    return axios.delete(apiPath("requete-annonce/" + id)).then((response) => {return response.data});
}

export default {
    getAllRequests,
    approveRequest,
    removeRequest
}