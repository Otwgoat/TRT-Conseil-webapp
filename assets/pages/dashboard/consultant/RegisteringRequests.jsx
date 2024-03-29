import React, { useEffect, useState } from "react";
import registeringRequestApi from "../../../services/registeringRequestApi";

export const RegisteringRequests = () => {
  const [registeringRequests, setRegisteringRequests] = useState([]);
  useEffect(() => {
    const fetchData = async () => {
      const data = await registeringRequestApi.getAllRequests();
      setRegisteringRequests(data);
    };
    fetchData();
  }, []);
  const approveRequest = async (id) => {
    await registeringRequestApi.approveRequest(id);
    setRegisteringRequests(
      registeringRequests.filter((request) => request.id !== id)
    );
  };

  const removeRequest = async (id) => {
    await registeringRequestApi.removeRequest(id);
    setRegisteringRequests(
      registeringRequests.filter((request) => request.id !== id)
    );
  };

  return (
    <div className="requestsContainer">
      <h2>Demandes d'approbation d'inscription</h2>
      {registeringRequests.length <= 0 && (
        <p>Aucune nouvelle demande d'inscription.</p>
      )}
      {registeringRequests &&
        registeringRequests.map((request) => (
          <div key={request.id} className="requestItem">
            <h3 className="contentTitle">
              {request.user_id.firstName} {request.user_id.lastName}
            </h3>
            <h3 className="contentTitle">{request.user_id.companyName}</h3>

            {request.user_id.roles[0] === "ROLE_RECRUITER" && (
              <p>
                Adresse de l'employeur:{" "}
                <span className="boldText">
                  {request.user_id.companyAdress}
                </span>
              </p>
            )}

            <p>
              Email de l'utilisateur:{" "}
              <span className="boldText">{request.user_id.email}</span>
            </p>

            {request.user_id.roles[0] === "ROLE_CANDIDAT" && (
              <p>
                Métier de l'utilisateur:{" "}
                <span className="boldText">{request.user_id.job}</span>
              </p>
            )}

            <p>
              Type d'utilisateur:{" "}
              <span className="boldText">
                {request.user_id.roles[0] === "ROLE_CANDIDATE"
                  ? "Candidat"
                  : "Recruteur"}
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
