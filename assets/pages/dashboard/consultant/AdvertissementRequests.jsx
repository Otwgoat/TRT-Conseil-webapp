import React, { useEffect, useState } from "react";
import advertissementRequestApi from "../../../services/advertissementRequestApi";

export const AdvertissementRequests = () => {
  const [requests, setRequests] = useState([]);
  useEffect(() => {
    const fetchData = async () => {
      const data = await advertissementRequestApi.getAllRequests();
      setRequests(data);
      console.log(data);
    };
    fetchData();
  }, []);
  const approveRequest = async (id) => {
    await advertissementRequestApi.approveRequest(id);
    return setRequests(requests.filter((request) => request.id !== id));
  };
  const removeRequest = async (id) => {
    await advertissementRequestApi.removeRequest(id);
    return setRequests(requests.filter((request) => request.id !== id));
  };
  return (
    <div className="requestsContainer">
      <h2>Demandes d'approbation d'annonce</h2>
      {requests.length <= 0 && <p>Aucune nouvelle demande d'annonce</p>}
      {requests &&
        requests.map((request) => (
          <div key={request.id} className="requestItem">
            <h3 className="contentTitle">{request.jobID.title}</h3>
            <p className="boldText">{request.jobID.city}</p>
            <p className="boldText">{request.jobID.recruiterId.companyName}</p>
            <p>{request.jobID.description}</p>
            <div className="requestItemFooter">
              <button
                className="ctaButton"
                onClick={() => approveRequest(request.id)}
              >
                Approuver
              </button>
              <button
                className="ctaButton redButton"
                onClick={() => removeRequest(request.id)}
              >
                Refuser
              </button>
            </div>
          </div>
        ))}
    </div>
  );
};
