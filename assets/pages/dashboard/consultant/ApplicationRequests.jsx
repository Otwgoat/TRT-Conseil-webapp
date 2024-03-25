import React, { useEffect, useState } from "react";
import applicationRequestsApi from "../../../services/applicationRequestsApi";

export const ApplicationRequests = () => {
  const [requests, setRequests] = useState([]);

  useEffect(() => {
    const fetchData = async () => {
      const data = await applicationRequestsApi.getAllRequests();
      setRequests(data);
    };
    fetchData();
  }, []);
  const approveRequest = async (id) => {
    await applicationRequestsApi.approveRequest(id);

    return setRequests(requests.filter((request) => request.id !== id));
  };
  const removeRequest = async (id) => {
    await applicationRequestsApi.removeRequest(id);
    return setRequests(requests.filter((request) => request.id !== id));
  };
  return (
    <div className="requestsContainer">
      <h2>Demandes d'approbation de candidature</h2>
      {requests.length <= 0 && <p>Aucune nouvelle demande de candidature</p>}
      {requests &&
        requests.map((request) => (
          <div className="requestItem" key={request.id}>
            <h3 className="contentTitle">
              {request.jobApplication.candidateID.firstName}{" "}
              {request.jobApplication.candidateID.lastName}
            </h3>
            <p>
              Métiers du candidat:{" "}
              <span className="boldText">
                {request.jobApplication.candidateID.job}
              </span>
            </p>
            <p>
              Titre de l'annonce:{" "}
              <span className="boldText">
                {request.jobApplication.jobID.title}
              </span>
            </p>
            <p>
              Lieu:{" "}
              <span className="boldText">
                {request.jobApplication.jobID.city}
              </span>
            </p>
            <p>
              Description de l'annonce:{" "}
              <span className="boldText">
                {request.jobApplication.jobID.description}
              </span>
            </p>
            <div className="requestItemFooter">
              <button
                onClick={() => approveRequest(request.id)}
                className="ctaButton"
              >
                Approuver
              </button>
              <button
                onClick={() => removeRequest(request.id)}
                className="ctaButton redButton"
              >
                Refuser
              </button>
            </div>
          </div>
        ))}
    </div>
  );
};
