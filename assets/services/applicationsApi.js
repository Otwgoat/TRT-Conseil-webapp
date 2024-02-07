import axios from "axios";
import apiPath from "./apiPath";

function getAllUserApplications(id) {
    axios.get(apiPath("candidatures/utilisateur/" + id )).then((response) => {return response.data});
}

function sendApplication(data){
    axios.post(apiPath("candidatures"), data ).then((response) => console.log(response));
      
}

export default {
    getAllUserApplications,
    sendApplication
}