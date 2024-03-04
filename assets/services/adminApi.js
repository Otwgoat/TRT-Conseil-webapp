import axios from "axios";
import apiPath from "./apiPath";

function createConsultant (data) {
    return axios.post(apiPath("creation-consultant"), data);
};

export default {
    createConsultant
}