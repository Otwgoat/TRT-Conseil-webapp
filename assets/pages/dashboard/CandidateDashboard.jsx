import React, { useState } from "react";
import { PersonalInfos } from "./PersonalInfos";
import { Applications } from "./candidate/Applications";
import { AddCurriculum } from "./candidate/AddCurriculum";
import { GetCurriculum } from "./candidate/GetCurriculum";

export const CandidateDashboard = () => {
  const [displayContainer, setDisplayContainer] = useState("personalInfos");
  const displayPersonalInfos = () => {
    setDisplayContainer("personalInfos");
  };
  const displayApplications = () => {
    setDisplayContainer("applications");
  };
  const addCurriculum = () => {
    setDisplayContainer("addCurriculum");
  };
  const getCurriculum = () => {
    setDisplayContainer("getCurriculum");
  };
  return (
    <main className="dashboardPage" id="candidatePage">
      <div className="dashboardNav">
        <h1>Mon espace candidat</h1>
        <div className="dashboardNavItem">
          <h2>Mes informations</h2>
          <p onClick={displayPersonalInfos}>
            Modifier mes informations personnelles
          </p>
        </div>
        <div className="dashboardNavItem">
          <h2>Mes candidatures</h2>
          <p onClick={displayApplications}>Consulter mes candidatures</p>
        </div>
        <div className="dashboardNavItem">
          <h2>Mon CV</h2>
          <p onClick={addCurriculum}>Ajouter ou modifier mon CV</p>
          <p onClick={getCurriculum}>Consulter mon CV</p>
        </div>
      </div>
      <div className="dashboardContainer">
        {displayContainer === "personalInfos" && <PersonalInfos />}
        {displayContainer === "applications" && <Applications />}
        {displayContainer === "addCurriculum" && <AddCurriculum />}
        {displayContainer === "getCurriculum" && <GetCurriculum />}
      </div>
    </main>
  );
};
