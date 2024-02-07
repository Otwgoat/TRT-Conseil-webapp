import React, { useState } from "react";
import { PersonalInfos } from "./PersonalInfos";
import { CreateAd } from "./recruiter/CreateAd";
import { GetAdvertissements } from "./recruiter/GetAdvertissements";

export const RecruiterDashboard = () => {
  const [displayContainer, setDisplayContainer] = useState("personalInfos");
  const displayPersonalInfos = () => {
    setDisplayContainer("personalInfos");
  };
  const displayCreateAd = () => {
    setDisplayContainer("createAd");
  };
  const displayGetMyAds = () => {
    setDisplayContainer("getMyAds");
  };

  return (
    <main className="dashboardPage" id="recruiterPage">
      <div className="dashboardNav">
        <h1>Mon espace recruteur</h1>
        <div className="dashboardNavItem">
          <h2>Mes informations</h2>
          <p onClick={displayPersonalInfos}>
            Modifier mes informations personnelles
          </p>
        </div>
        <div className="dashboardNavItem">
          <h2>Mes annonces</h2>
          <p onClick={displayCreateAd}>Publier une nouvelle annonce</p>
          <p onClick={displayGetMyAds}>Consulter mes annonces</p>
        </div>
      </div>
      <div className="dashboardContainer">
        {displayContainer === "personalInfos" && <PersonalInfos />}
        {displayContainer === "createAd" && <CreateAd />}
        {displayContainer === "getMyAds" && <GetAdvertissements />}
      </div>
    </main>
  );
};
