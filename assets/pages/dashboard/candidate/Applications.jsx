import React, { useEffect, useState } from "react";
import applicationsApi from "../../../services/applicationsApi";

export const Applications = () => {
  const [applications, setApplications] = useState([]);
  useEffect(() => {
    const fetchData = async () => {
      const data = await applicationsApi.getAllUserApplications();
      setApplications(data);
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
              <p>
                Recruteur:{" "}
                <span className="boldText">
                  {application.jobID.recruiterId.companyName}
                </span>
              </p>
              <p>
                Lieu:{" "}
                <span className="boldText"> {application.jobID.city}</span>
              </p>

              <p>
                Statut:{" "}
                <span className="boldText">
                  {application.approved
                    ? "Approuvée"
                    : "En attente d'approbation"}
                </span>
              </p>
            </div>
          ))}
      </div>
    </div>
  );
};
