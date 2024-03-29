import axios from "axios";
import apiPath from "./apiPath";

function findAllAdvertissements(){
  return axios
  .get(apiPath("annonces"))
  .then(response => {
      const advertissements = response.data ;
      return advertissements;
  })
  
}

function findOneAdvertissement(id){
  return axios
  .get(apiPath("annonce/" + id))
  .then(response => {
      const advertissement = response.data ;
      return advertissement;
  })
  
}

function postAdvertissement(data){
  return axios.post(apiPath("annonces"), data);
   
}

function getMyAdvertissements(){
  return axios
  .get(apiPath("mes-annonces"))
  .then(response => {
      const advertissements = response.data ;
      return advertissements;
  })
  
}

function deleteMyAdvertissement(id){
  return axios
  .delete(apiPath("annonce/" + id))
  .then(response => {
      return response.data;
  })
  
}
export default {
  findAllAdvertissements,
  findOneAdvertissement,
  postAdvertissement,
  getMyAdvertissements,
  deleteMyAdvertissement
}