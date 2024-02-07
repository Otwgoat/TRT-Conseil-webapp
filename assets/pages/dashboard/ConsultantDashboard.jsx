import React, { useState } from "react";
import { RegisteringRequests } from "./consultant/RegisteringRequests";
import { AdvertissementRequests } from "./consultant/AdvertissementRequests";
import { ApplicationRequests } from "./consultant/ApplicationRequests";

export const ConsultantDashboard = () => {
  const [dislayingRequests, setDislayingRequests] = useState("registering");

  const displayApplicationRequests = () => {
    setDislayingRequests("application");
  };
  const displayAdvertisementRequests = () => {
    setDislayingRequests("advertissement");
  };
  const displayRegisteringRequests = () => {
    setDislayingRequests("registering");
  };
  return (
    <main className="dashboardPage" id="consultantPage">
      <div className="dashboardNav">
        <div className="dashboardNavItem">
          <h2>Demandes d'approbation d'inscription</h2>
          <p onClick={displayRegisteringRequests}>
            Consulter les demandes d'approbation
          </p>
        </div>
        <div className="dashboardNavItem">
          <h2>Demandes d'approbation d'annonce</h2>
          <p onClick={displayAdvertisementRequests}>
            Consulter les demandes d'approbation
          </p>
        </div>
        <div className="dashboardNavItem">
          <h2>Demandes d'approbation de candidature</h2>
          <p onClick={displayApplicationRequests}>
            Consulter les demandes d'approbation
          </p>
        </div>
      </div>
      <div className="dashboardContainer">
        {dislayingRequests === "registering" && <RegisteringRequests />}
        {dislayingRequests === "advertissement" && <AdvertissementRequests />}
        {dislayingRequests === "application" && <ApplicationRequests />}
      </div>
    </main>
  );
};
