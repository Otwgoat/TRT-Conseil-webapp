import React, { useState } from "react";
import { RegisteringRequests } from "./consultant/RegisteringRequests";
import { AdvertissementRequests } from "./consultant/AdvertissementRequests";
import { ApplicationRequests } from "./consultant/ApplicationRequests";
import UpdatePassword from "./UpdatePassword";

export const ConsultantDashboard = () => {
  const [displayingRequests, setDisplayingRequests] = useState("registering");
  const displayUpdatePassword = () => {
    setDisplayingRequests("updatePassword");
  };
  const displayApplicationRequests = () => {
    setDisplayingRequests("application");
  };

  const displayAdvertisementRequests = () => {
    setDisplayingRequests("advertissement");
  };
  const displayRegisteringRequests = () => {
    setDisplayingRequests("registering");
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
        {displayingRequests === "updatePassword" && <UpdatePassword />}
        {displayingRequests === "registering" && <RegisteringRequests />}
        {displayingRequests === "advertissement" && <AdvertissementRequests />}
        {displayingRequests === "application" && <ApplicationRequests />}
      </div>
    </main>
  );
};
