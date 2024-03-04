import React, { useEffect, useState } from "react";
import applicationsApi from "../../../services/applicationsApi";

export const Applications = () => {
  const [applications, setApplications] = useState([]);
  useEffect(() => {
    const fetchData = async () => {
      const data = await applicationsApi.getAllUserApplications();
      setApplications(data);
      console.log(data);
    };
    fetchData();
  }, []);
  return (
    <div className="dashboardContent">
      <h2>Mes candidatures</h2>
      {applications && applications.length <= 0 && (
        <p>Vous n'avez pas encore postulé à une annonce.</p>
      )}
      <div className="contentContainer">
        {applications &&
          applications.map((application) => (
            <div key={application.id} className="contentItem">
              <h3 className="contentTitle">Annonce n°{application.jobID.id}</h3>
              <p className="boldText">
                {application.jobID.recruiterId.companyName}
              </p>
              <p className="boldText">{application.jobID.city}</p>

              <p>
                {application.approved
                  ? "Status: Approuvée"
                  : "Status: En attente d'approbation"}
              </p>
            </div>
          ))}
      </div>
    </div>
  );
};
