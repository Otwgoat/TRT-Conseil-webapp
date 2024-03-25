import axios from "axios";
import apiPath from "./apiPath";

function getAllUserApplications() {
    return axios.get(apiPath("candidatures/utilisateur/")).then((response) => {return response.data});
}

function sendApplication(data){
    return axios.post(apiPath("candidatures"), data );
      
}

function getApplicationByJobAndUser(jobID){
    return axios.get(apiPath("candidature-id-utilisateur/" + jobID ))
    .then((response) => {
        return response.data.length;
    });
} 

function getApplicationByAdvertissement(jobID){
    return axios.get(apiPath("annonce/" + jobID + "/candidatures"))
    .then((response) => {
        return response.data;
    })
}


export default {
    getAllUserApplications,
    sendApplication,
    getApplicationByJobAndUser,
    getApplicationByAdvertissement,
    
}