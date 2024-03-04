import axios from "axios";
import apiPath from "./apiPath";

function getAllRequests() {
    return axios.get(apiPath("requetes")).then((reponse) => {
        return reponse.data;
    })
};

function approveRequest(id) {
    return axios.put(apiPath("requete/" + id)).then((reponse) => {
        return reponse.data;
    })
};
function removeRequest(id) {
    return axios.delete(apiPath("requete/" + id)).then((reponse) => {
        return reponse.data;
    })
};


export default {
    getAllRequests,
    approveRequest,
    removeRequest

}