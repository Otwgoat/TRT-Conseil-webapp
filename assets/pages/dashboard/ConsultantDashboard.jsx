import React, { useState } from "react";
import { RegisteringRequests } from "./consultant/RegisteringRequests";
import { AdvertissementRequests } from "./consultant/AdvertissementRequests";
import { ApplicationRequests } from "./consultant/ApplicationRequests";
import UpdatePassword from "./UpdatePassword";

export const ConsultantDashboard = () => {
  const [dislayingRequests, setDislayingRequests] = useState("registering");
  const displayUpdatePassword = () => {
    setDisplayingRequests("updatePassword");
  };
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
        <h1>Mon espace consultant</h1>
        <div className="dashboardNavItem">
          <h2>Mes informations</h2>
          <p onClick={displayUpdatePassword}>Modifier mon mot de passe</p>
        </div>
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
        {dislayingRequests === "updatePassword" && <UpdatePassword />}
        {dislayingRequests === "registering" && <RegisteringRequests />}
        {dislayingRequests === "advertissement" && <AdvertissementRequests />}
        {dislayingRequests === "application" && <ApplicationRequests />}
      </div>
    </main>
  );
};
