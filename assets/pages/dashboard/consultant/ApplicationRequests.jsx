import React, { useEffect, useState } from "react";
import applicationRequestsApi from "../../../services/applicationRequestsApi";

export const ApplicationRequests = () => {
  const [requests, setRequests] = useState([]);
  useEffect(() => {
    const fetchData = async () => {
      const data = await applicationRequestsApi.getAllRequests();
      setRequests(data);
      console.log(data);
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
            <h3>
              {request.jobApplication.candidateID.firstName}{" "}
              {request.jobApplication.candidateID.lastName}
            </h3>
            <p>{request.jobApplication.candidateID.job}</p>
            <p>{request.jobApplication.jobID.title}</p>
            <p>{request.jobApplication.jobID.city}</p>

            <div className="requestItemFooter">
              <button
                onClick={() => approveRequest(request.id)}
                className="ctaButton"
              >
                Approuver
              </button>
              <button
                onClick={() => removeRequest(request.id)}
                className="ctaButton"
              >
                Refuser
              </button>
            </div>
          </div>
        ))}
    </div>
  );
};
