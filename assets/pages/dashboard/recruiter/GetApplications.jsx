import React, { useEffect, useState } from "react";
import applicationsApi from "../../../services/applicationsApi";
import Button from "../../../components/Button";

export const GetApplications = (props) => {
  const [applications, setApplications] = useState();
  const jobID = props.jobID;

  useEffect(() => {
    const fetchData = async () => {
      const data = await applicationsApi.getApplicationByAdvertissement(jobID);
      setApplications(data);
    };
    fetchData();
  }, []);
  return (
    <div id="applicationsContainer">
      <h2>Liste des candidats</h2>
      {applications &&
        applications.map((application) => (
          <div key={application.id} className="contentItem">
            <p>Candidature n°{application.id}</p>
            <h3>
              {application.candidateID.lastName}{" "}
              {application.candidateID.firstName}
            </h3>

            <p className="boldText">{application.candidateID.job}</p>
          </div>
        ))}
      <button onClick={props.onClick}>Fermer la liste</button>
    </div>
  );
};
